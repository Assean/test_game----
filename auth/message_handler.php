<?php
/**
 * message_handler.php - 處理客服訊息發送與狀態更新
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_logic.php';

header('Content-Type: application/json');

if (!Auth::isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

$action = $_POST['action'] ?? '';
$user_id = $_SESSION['user_id'];

if ($action === 'send') {
    $content = trim($_POST['content'] ?? '');
    if (empty($content)) {
        echo json_encode(['success' => false, 'message' => 'Content cannot be empty.']);
        exit;
    }

    $is_admin_reply = Auth::isAdmin() ? 1 : 0;
    $receiver_id = $_POST['receiver_id'] ?? null;

    try {
        $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, content, is_admin_reply) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $receiver_id, $content, $is_admin_reply]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error.']);
    }
} elseif ($action === 'fetch') {
    // 獲取對話紀錄
    $other_id = $_POST['other_id'] ?? null;
    try {
        if (Auth::isAdmin() && $other_id) {
            // 管理員查看與特定用戶的對話
            $stmt = $pdo->prepare("
                SELECT m.*, u.username as sender_name 
                FROM messages m 
                JOIN users u ON m.sender_id = u.id 
                WHERE (m.sender_id = ? AND m.is_admin_reply = 0) 
                   OR (m.is_admin_reply = 1 AND m.receiver_id = ?)
                ORDER BY m.created_at ASC
            ");
            $stmt->execute([$other_id, $other_id]);
        } else {
            // 普通用戶查看自己的對話
            $stmt = $pdo->prepare("
                SELECT m.*, u.username as sender_name 
                FROM messages m 
                JOIN users u ON m.sender_id = u.id 
                WHERE m.sender_id = ? OR m.receiver_id = ? 
                ORDER BY m.created_at ASC
            ");
            $stmt->execute([$user_id, $user_id]);
        }
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $messages]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error.']);
    }
}
?>