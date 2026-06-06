<?php
/**
 * auth/reset_handler.php — 重設密碼處理（新功能 4）
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

$token    = trim($_POST['token'] ?? '');
$password = $_POST['password'] ?? '';
$confirm  = $_POST['confirm_password'] ?? '';

if (empty($token)) {
    echo json_encode(['success' => false, 'message' => '無效的重設連結']);
    exit;
}

$pwMsg = Validator::passwordStrengthMessage($password);
if ($pwMsg) {
    echo json_encode(['success' => false, 'message' => $pwMsg]);
    exit;
}

if ($password !== $confirm) {
    echo json_encode(['success' => false, 'message' => '兩次密碼不一致']);
    exit;
}

try {
    $stmt = $pdo->prepare(
        "SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW() AND used = 0 LIMIT 1"
    );
    $stmt->execute([$token]);
    $reset = $stmt->fetch();

    if (!$reset) {
        echo json_encode(['success' => false, 'message' => '重設連結無效或已過期']);
        exit;
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $pdo->prepare("UPDATE users SET password = ? WHERE id = ?")
        ->execute([$hashed, $reset['user_id']]);

    $pdo->prepare("UPDATE password_resets SET used = 1 WHERE id = ?")
        ->execute([$reset['id']]);

    echo json_encode(['success' => true, 'message' => '密碼已成功重設，請重新登入']);
} catch (\Throwable $e) {
    jsonError('系統錯誤，請稍後再試', $e, 'reset_handler');
}
?>
