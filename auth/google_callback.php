<?php
/**
 * auth/google_callback.php — Google OAuth2 回調處理（新功能 1）
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_logic.php';
require_once __DIR__ . '/../includes/google_api.php';
require_once __DIR__ . '/../includes/error_handler.php';

$google = new GoogleAPI(GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, GOOGLE_REDIRECT_URI);
$is_verify = isset($_SESSION['user_id']) && isset($_GET['state']) && $_GET['state'] === 'verify';

// 注意：在 getLoginUrl 中我們需要加入 state=verify
if (empty($_GET['code'])) {
    header("Location: ../login.php?error=google_failed");
    exit;
}

try {
    // 1. 授權碼換 Access Token
    $tokenData = $google->getAccessToken($_GET['code']);
    if (!$tokenData) {
        header("Location: ../login.php?error=google_token");
        exit;
    }

    // 2. 取得使用者資訊
    $gUser = $google->getUserInfo($tokenData['access_token']);
    if (!$gUser) {
        header("Location: ../login.php?error=google_user");
        exit;
    }

    $googleId = $gUser['id'];
    $email    = $gUser['email']    ?? '';
    $name     = $gUser['name']     ?? '';
    $avatar   = $gUser['picture']  ?? '';

    // 2.5 處理驗證模式 (Verification Mode)
    if ($is_verify) {
        $pdo->prepare("UPDATE users SET google_id = ?, avatar_url = ? WHERE id = ?")
            ->execute([$googleId, $avatar, $_SESSION['user_id']]);
        
        $_SESSION['google_verified_details'] = json_encode($gUser);
        
        header("Location: ../profile.php?success=verified");
        exit;
    }

    // 3. 檢查 google_id 是否已綁定
    $stmt = $pdo->prepare("SELECT * FROM users WHERE google_id = ? LIMIT 1");
    $stmt->execute([$googleId]);
    $existingUser = $stmt->fetch();

    if ($existingUser) {
        // 已綁定 → 直接登入
        if ($existingUser['is_banned']) {
            header("Location: ../login.php?error=banned");
            exit;
        }

        // 新功能：管理員白名單自動授權與跳轉
        $isAdmin = in_array($email, ADMIN_WHITELIST_EMAILS);
        if ($isAdmin) {
            $pdo->prepare("UPDATE users SET role_id = ? WHERE id = ?")
                ->execute([ROLE_ADMIN, $existingUser['id']]);
            $existingUser['role_id'] = ROLE_ADMIN;
        }

        // 更新頭像
        $pdo->prepare("UPDATE users SET avatar_url = ? WHERE id = ?")
            ->execute([$avatar, $existingUser['id']]);
        $existingUser['avatar_url'] = $avatar;
        
        $auth->setSession($existingUser);

        // 如果是從管理員頁面來的，跳轉至後台
        if (isset($_GET['state']) && $_GET['state'] === 'admin_login' && $existingUser['role_id'] == ROLE_ADMIN) {
            header("Location: ../admin/portal.php");
        } else {
            header("Location: ../profile.php");
        }
        exit;
    }

    // 4. 檢查 email 是否已有帳號 → 關聯 Google ID
    if ($email) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $byEmail = $stmt->fetch();

        if ($byEmail) {
            $pdo->prepare("UPDATE users SET google_id = ?, avatar_url = ? WHERE id = ?")
                ->execute([$googleId, $avatar, $byEmail['id']]);
            $byEmail['google_id']  = $googleId;
            $byEmail['avatar_url'] = $avatar;
            $auth->setSession($byEmail);
            header("Location: ../profile.php?success=google_linked");
            exit;
        }
    }

    // 5. 全新帳號 → 儲存到 Session 引導至註冊頁
    $_SESSION['pending_google_id']       = $googleId;
    $_SESSION['pending_google_email']    = $email;
    $_SESSION['pending_google_username'] = preg_replace('/\s+/', '_', $name);
    $_SESSION['pending_google_avatar']   = $avatar;
    header("Location: ../register.php?from=google");
    exit;

} catch (\Throwable $e) {
    logError($e, 'google_callback');
    header("Location: ../login.php?error=system");
    exit;
}
?>
