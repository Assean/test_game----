<?php
/**
 * discord_callback.php - 處理 Discord OAuth2 回傳 (綁定帳號)
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth_logic.php';
require_once __DIR__ . '/includes/discord_api.php';

if (!Auth::isLoggedIn()) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['code'])) {
    header("Location: profile.php?error=discord_failed");
    exit;
}

$discordApi = new DiscordAPI(DISCORD_CLIENT_ID, DISCORD_CLIENT_SECRET, DISCORD_REDIRECT_URI, DISCORD_WEBHOOK_URL);

// 1. 交換 Token
$tokenData = $discordApi->getAccessToken($_GET['code']);

if (!isset($tokenData['access_token'])) {
    header("Location: profile.php?error=discord_token_error");
    exit;
}

// 2. 獲取使用者資訊
$discordUser = $discordApi->getUserDetails($tokenData['access_token']);

if (!isset($discordUser['id'])) {
    header("Location: profile.php?error=discord_user_error");
    exit;
}

// 3. 綁定 Discord ID
if ($auth->linkDiscord($_SESSION['user_id'], $discordUser['id'])) {
    // 發送 Webhook 通知
    $discordApi->sendWebhook("🔗 玩家 **{$_SESSION['username']}** 已成功綁定 Discord 帳號 ({$discordUser['username']})");
    header("Location: profile.php?success=discord_linked");
} else {
    header("Location: profile.php?error=discord_link_failed");
}
?>
