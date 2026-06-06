<?php
/**
 * logout.php - 安全登出邏輯
 */

require_once __DIR__ . '/config/config.php';

// 1. 清除所有 Session 變數
session_unset();

// 2. 銷毀 Session
session_destroy();

// 3. 清除 Session Cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 4. 重導向回首頁
header("Location: index.php");
exit;
?>