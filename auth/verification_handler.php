<?php
/**
 * auth/verification_handler.php — 處理新版驗證系統邏輯
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_logic.php';

if (!Auth::isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => '請先登入']);
    exit;
}

$action = $_GET['action'] ?? '';
$user_id = $_SESSION['user_id'];

switch ($action) {
    case 'system_verify':
        // 系統進度條跑完後的後端確認
        try {
            $stmt = $pdo->prepare("UPDATE users SET system_verified = 1 WHERE id = ?");
            $stmt->execute([$user_id]);
            $_SESSION['system_verified'] = 1;
            
            // 系統通知
            $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, content, type) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, '系統驗證成功', '您已通過系統級真人驗證！', 'system']);
            
            echo json_encode(['success' => true, 'message' => '系統驗證成功']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => '資料庫錯誤']);
        }
        break;

    // Email 驗證已於三階升級中移除

    default:
        echo json_encode(['success' => false, 'message' => '無效操作']);
        break;
}
?>
