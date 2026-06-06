<?php
/**
 * auth/notification_handler.php — 通知中心 API（新功能 3）
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_logic.php';
require_once __DIR__ . '/../includes/error_handler.php';

if (!Auth::isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => '未登入']);
    exit;
}

$action  = $_GET['action'] ?? $_POST['action'] ?? 'list';
$user_id = $_SESSION['user_id'];

try {
    switch ($action) {
        case 'list':
            $stmt = $pdo->prepare(
                "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 20"
            );
            $stmt->execute([$user_id]);
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;

        case 'unread_count':
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
            $stmt->execute([$user_id]);
            echo json_encode(['success' => true, 'count' => (int)$stmt->fetchColumn()]);
            break;

        case 'mark_read':
            if (!Auth::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                echo json_encode(['success' => false, 'message' => 'CSRF 驗證失敗']);
                exit;
            }
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?")
                    ->execute([$id, $user_id]);
            } else {
                $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?")
                    ->execute([$user_id]);
            }
            echo json_encode(['success' => true]);
            break;

        default:
            echo json_encode(['success' => false, 'message' => '未知動作']);
    }
} catch (\Throwable $e) {
    jsonError('系統錯誤，請稍後再試', $e, 'notification_handler');
}
?>
