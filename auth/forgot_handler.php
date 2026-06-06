<?php
/**
 * auth/forgot_handler.php — 忘記密碼處理（新功能 4）
 * 產生重設 Token 並顯示重設連結（無郵件服務先直接顯示）
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_logic.php';
require_once __DIR__ . '/../includes/validator.php';
require_once __DIR__ . '/../includes/error_handler.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => '無效請求']);
    exit;
}

if (!Auth::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
    echo json_encode(['success' => false, 'message' => 'CSRF 驗證失敗']);
    exit;
}

$email = trim($_POST['email'] ?? '');

if (!Validator::validateEmail($email)) {
    echo json_encode(['success' => false, 'message' => 'Email 格式不正確']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, username FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        // 安全起見，不揭露帳號是否存在
        echo json_encode(['success' => true, 'message' => '若此 Email 已註冊，重設連結將顯示如下。']);
        exit;
    }

    // 產生安全 Token
    $token     = bin2hex(random_bytes(32));
    $expiresAt = date('Y-m-d H:i:s', time() + 3600); // 1 小時有效

    // 刪除舊 Token
    $pdo->prepare("DELETE FROM password_resets WHERE user_id = ?")
        ->execute([$user['id']]);

    // 插入新 Token
    $pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)")
        ->execute([$user['id'], $token, $expiresAt]);

    // 產生重設連結
    $baseUrl   = rtrim(GOOGLE_REDIRECT_URI, '/auth/google_callback.php'); // 取得 baseUrl
    $resetLink = "http://localhost/old_htdocs/%E5%85%B6%E4%BB%961/test_game%E9%81%8A%E6%88%B2%E5%AE%98%E7%B6%B2/reset_password.php?token=$token";

    echo json_encode([
        'success' => true,
        'message' => '重設連結已產生，請複製以下連結（開發環境直接顯示）：',
        'reset_link' => $resetLink
    ]);
} catch (\Throwable $e) {
    jsonError('系統錯誤，請稍後再試', $e, 'forgot_handler');
}
?>
