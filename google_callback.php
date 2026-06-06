<?php
/**
 * google_callback.php - 處理 Google 登入回傳
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth_logic.php';

if (!isset($_GET['code'])) {
    header("Location: login.php?error=google_failed");
    exit;
}

$code = $_GET['code'];

// 1. 交換 Token
$token_url = "https://oauth2.googleapis.com/token";
$params = [
    'code' => $code,
    'client_id' => GOOGLE_CLIENT_ID,
    'client_secret' => GOOGLE_CLIENT_SECRET,
    'redirect_uri' => GOOGLE_REDIRECT_URI,
    'grant_type' => 'authorization_code'
];

$ch = curl_init($token_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
$response = curl_exec($ch);
curl_close($ch);

$token_data = json_decode($response, true);

if (!isset($token_data['access_token'])) {
    header("Location: login.php?error=google_token_error");
    exit;
}

$access_token = $token_data['access_token'];

// 2. 獲取使用者資訊
$user_info_url = "https://www.googleapis.com/oauth2/v2/userinfo?access_token=" . $access_token;
$user_info = file_get_contents($user_info_url);
$google_user = json_decode($user_info, true);

if (!isset($google_user['id'])) {
    header("Location: login.php?error=google_user_error");
    exit;
}

// 3. 執行登入或註冊邏輯
$res = $auth->loginWithGoogle($google_user['id'], $google_user['email'], $google_user['name']);

if ($res === true) {
    header("Location: profile.php");
} elseif ($res === "BANNED") {
    header("Location: login.php?error=banned");
} else {
    header("Location: login.php?error=google_final_error");
}
?>
