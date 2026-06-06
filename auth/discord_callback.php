<?php
/**
 * auth/discord_callback.php — Discord OAuth2 回調處理（Bug 13 修復 + 移至 auth/）
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_logic.php';
require_once __DIR__ . '/../includes/discord_api.php';
require_once __DIR__ . '/../includes/error_handler.php';

$discord = new DiscordAPI(DISCORD_CLIENT_ID, DISCORD_CLIENT_SECRET, DISCORD_REDIRECT_URI, DISCORD_WEBHOOK_URL);

if (empty($_GET['code'])) {
    header("Location: ../login.php?error=discord_failed");
    exit;
}

try {
    // 1. 授權碼換 Access Token
    $tokenData = $discord->getAccessToken($_GET['code']);
    $is_verify = isset($_SESSION['user_id']) && isset($_GET['state']) && $_GET['state'] === 'verify';

    if (!isset($tokenData['access_token'])) {
        header("Location: ../login.php?error=discord_token");
        exit;
    }

    // 2. 取得 Discord 使用者資訊
    $dUser       = $discord->getUserDetails($tokenData['access_token']);
    $discordId   = $dUser['id']       ?? '';
    $discordEmail = $dUser['email']   ?? '';
    $discordName  = $dUser['username'] ?? '';

    if (empty($discordId)) {
        header("Location: ../login.php?error=discord_user");
        exit;
    }

    // 2.5 處理驗證模式 (Verification Mode)
    if ($is_verify) {
        $pdo->prepare("UPDATE users SET discord_id = ? WHERE id = ?")
            ->execute([$discordId, $_SESSION['user_id']]);
        
        $_SESSION['discord_verified_details'] = json_encode($dUser);
        
        header("Location: ../profile.php?success=verified");
        exit;
    }

    // 3. 檢查 discord_id 是否已綁定帳號
    $stmt = $pdo->prepare("SELECT * FROM users WHERE discord_id = ? LIMIT 1");
    $stmt->execute([$discordId]);
    $existingUser = $stmt->fetch();

    if ($existingUser) {
        // 已綁定 → 直接登入
        if ($existingUser['is_banned']) {
            header("Location: ../login.php?error=banned");
            exit;
        }

        // 新功能：管理員白名單自動授權與跳轉
        $isAdmin = in_array($discordName, ADMIN_WHITELIST_DISCORD_NAMES);
        if ($isAdmin) {
            $pdo->prepare("UPDATE users SET role_id = ? WHERE id = ?")
                ->execute([ROLE_ADMIN, $existingUser['id']]);
            $existingUser['role_id'] = ROLE_ADMIN;
        }
        
        $auth->setSession($existingUser);

        // 如果是從管理員頁面來的，跳轉至後台
        if (isset($_GET['state']) && $_GET['state'] === 'admin_login' && $existingUser['role_id'] == ROLE_ADMIN) {
            header("Location: ../admin/portal.php");
        } else {
            header("Location: ../profile.php");
        }
        exit;
    }

    // 4. 若已登入 → 綁定到現有帳號
    if (isset($_SESSION['user_id'])) {
        $auth->linkDiscord($_SESSION['user_id'], $discordId);
        $discord->sendWebhook("🔗 **{$_SESSION['username']}** 已成功綁定 Discord 帳號 ({$discordName})");
        header("Location: ../profile.php?success=discord_linked");
        exit;
    }

    // 5. 未登入且未綁定 → 引導至註冊
    $_SESSION['pending_discord_id']       = $discordId;
    $_SESSION['pending_discord_email']    = $discordEmail;
    $_SESSION['pending_discord_username'] = $discordName;
    header("Location: ../register.php?from=discord");
    exit;

} catch (\Throwable $e) {
    logError($e, 'discord_callback');
    header("Location: ../login.php?error=system");
    exit;
}
?>
