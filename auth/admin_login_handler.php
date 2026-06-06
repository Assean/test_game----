<?php
/**
 * admin_login_handler.php - 處理管理員兩段階驗證
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_logic.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid approach.']);
    exit;
}

// CSRF 驗證
if (!isset($_POST['csrf_token']) || !Auth::verifyCsrfToken($_POST['csrf_token'])) {
    echo json_encode(['success' => false, 'message' => 'CSRF validation failed.']);
    exit;
}

$stage = intval($_POST['stage'] ?? 1);

if ($stage === 1) {
    // 階段 1：驗證帳密
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND role_id = ? LIMIT 1");
        $stmt->execute([$username, ROLE_ADMIN]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // 通過第一階段，存入暫存 Session
            $_SESSION['admin_auth_pending_id'] = $user['id'];
            echo json_encode(['success' => true, 'stage' => 1]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Identity mismatch.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error.']);
    }
} else if ($stage === 2) {
    // 階段 2：驗證虛擬郵件與安全碼
    if (!isset($_SESSION['admin_auth_pending_id'])) {
        echo json_encode(['success' => false, 'message' => 'Auth sequence broken.']);
        exit;
    }

    $id = $_SESSION['admin_auth_pending_id'];
    $admin_email = $_POST['admin_email'] ?? '';
    $security_code = $_POST['security_code'] ?? '';

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role_id = ? LIMIT 1");
        $stmt->execute([$id, ROLE_ADMIN]);
        $user = $stmt->fetch();

        if ($user && $user['admin_email'] === $admin_email && $user['security_code'] === $security_code) {
            // 通過二階段，正式登入
            session_regenerate_id(true);

            // 產生並更新 Session Token (核心安全需求)
            $token = bin2hex(random_bytes(16));
            $stmt_token = $pdo->prepare("UPDATE users SET session_token = ? WHERE id = ?");
            $stmt_token->execute([$token, $user['id']]);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['session_token'] = $token;
            $_SESSION['discord_id'] = $user['discord_id'];
            $_SESSION['roblox_id'] = $user['roblox_id'];

            // 清除暫存
            unset($_SESSION['admin_auth_pending_id']);

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Security protocol breach.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error.']);
    }
}
?>