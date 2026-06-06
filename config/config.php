<?php
/**
 * config.php - 第三方 API 設定檔
 *
 * ⚠️ 部署前必填設定：
 *   1. DISCORD_CLIENT_ID / DISCORD_CLIENT_SECRET  → Discord Developer Portal
 *   2. GOOGLE_CLIENT_ID / GOOGLE_CLIENT_SECRET    → Google Cloud Console (APIs & Services)
 *   3. ROBLOX_API_KEY / ROBLOX_UNIVERSE_ID        → Roblox Creator Portal (Open Cloud)
 *   4. DISCORD_WEBHOOK_URL                        → Discord 頻道設定 > 整合 > Webhook
 *   5. MASTER_ADMIN_PASSWORD                      → 請設定強密碼（至少 12 字元含大小寫數字符號）
 *   6. SMTP_USER / SMTP_PASS                      → 您的發信郵件帳號與授權碼
 */

// SMTP 郵件發送設定 (新功能)
define('SMTP_HOST', 'smtp.gmail.com');       // 郵件伺服器 (預設 Gmail)
define('SMTP_PORT', 587);                    // 端口 (TLS 587 / SSL 465)
define('SMTP_USER', 'your-email@gmail.com'); // 您的發信信箱
define('SMTP_PASS', 'your-app-password');    // 您的發信授權碼
define('SMTP_FROM', 'TEST GAME 驗證中心');    // 發件人顯示名稱

// Discord OAuth2 設定
define('DISCORD_CLIENT_ID',     '1493566605546750072');
define('DISCORD_CLIENT_SECRET', 'OC9a7nFlgNKNSsdC95r6w9VuImhkVu9a');
define('DISCORD_REDIRECT_URI',  'http://localhost/old_htdocs/%E5%85%B6%E4%BB%961/test_game%E9%81%8A%E6%88%B2%E5%AE%98%E7%B6%B2/auth/discord_callback.php');
define('DISCORD_WEBHOOK_URL',   'YOUR_DISCORD_WEBHOOK_URL');

// Google OAuth2 設定
define('GOOGLE_CLIENT_ID',     '723190336692-6sbsjmvfov5q542j4mfm3elf91odgkpt.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
define('GOOGLE_REDIRECT_URI',  'http://localhost/old_htdocs/%E5%85%B6%E4%BB%961/test_game%E9%81%8A%E6%88%B2%E5%AE%98%E7%B6%B2/auth/google_callback.php');

// Roblox Open Cloud API 設定
define('ROBLOX_API_KEY',      'YOUR_ROBLOX_API_KEY');
define('ROBLOX_UNIVERSE_ID',  'YOUR_ROBLOX_UNIVERSE_ID');

// 遊戲獎勵設定
define('CHECKIN_POINTS',  100);
define('REGISTER_POINTS', 500);

// 權限等級定義
define('ROLE_USER',      1);
define('ROLE_DEVELOPER', 2);
define('ROLE_ADMIN',     3);
define('ROLE_HELPER',    4);
define('ROLE_INTERN',    5);

// 管理員白名單 (新功能：社交登入自動授權)
define('ADMIN_WHITELIST_EMAILS', [
    'y20120816s@gmail.com',
    'sean1234560816@gmail.com',
    'seanpage008166@gmail.com'
]);
define('ADMIN_WHITELIST_DISCORD_NAMES', [
    'sean073270',
    'rayray_33990'
]);

// Bug 5 修復：Super Admin 使用資料庫 user_id 進行二次驗證，防止同名帳號繞過
// 請將此值設為 sean_admin 在資料庫中實際的 id（執行 migrate_db.php 後確認）
define('SUPER_ADMIN_ID', 1);

// Bug 1 修復：管理員主密碼移至此處定義
// ⚠️ 請設定強密碼：至少 12 字元，包含大小寫字母、數字及符號
define('MASTER_ADMIN_PASSWORD', 'Ch@nge_M3_N0w!2026');

// Session 安全設定
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', 1);
}

// Bug 6 修復：移除重複的 session_start()，只保留一個條件式呼叫
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>