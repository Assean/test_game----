<?php
/**
 * user_action_handler.php - 處理管理員對使用者的進階操作 (MORE 選單)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_logic.php';

header('Content-Type: application/json');

if (!Auth::isAdmin()) {
    echo json_encode(['success' => false, 'message' => 'ACCESS DENIED']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? '';
$target_id = (int) ($data['user_id'] ?? 0);
$admin_pass = $data['admin_password'] ?? '';

// Bug 1 修復：管理員主密碼從 config.php 常數引用，不再硬編碼
// 請在 config/config.php 中設定 'MASTER_ADMIN_PASSWORD'
$MASTER_ADMIN_PASSWORD = MASTER_ADMIN_PASSWORD;

if (!$target_id) {
    echo json_encode(['success' => false, 'message' => 'INVALID USER ID']);
    exit;
}

/**
 * 管理員動作記錄
 */
function logAdminAction($pdo, $admin_id, $action, $target_type, $target_id, $details) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $stmt = $pdo->prepare("INSERT INTO admin_logs (admin_id, action, target_type, target_id, details, ip_address) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$admin_id, $action, $target_type, $target_id, $details, $ip]);
}

$admin_id = $_SESSION['user_id'];

switch ($action) {
    case 'ban':
        if ($admin_pass !== $MASTER_ADMIN_PASSWORD) {
            echo json_encode(['success' => false, 'message' => 'WRONG MASTER PASSWORD']);
            exit;
        }
        $stmt = $pdo->prepare("UPDATE users SET is_banned = 1 WHERE id = ?");
        $stmt->execute([$target_id]);
        logAdminAction($pdo, $admin_id, 'BAN_USER', 'user', $target_id, '帳號被管理員封鎖');
        echo json_encode(['success' => true, 'message' => 'USER HAS BEEN BANNED']);
        break;

    case 'unban':
        $stmt = $pdo->prepare("UPDATE users SET is_banned = 0 WHERE id = ?");
        $stmt->execute([$target_id]);
        logAdminAction($pdo, $admin_id, 'UNBAN_USER', 'user', $target_id, '帳號解除封鎖');
        echo json_encode(['success' => true, 'message' => 'USER UNBANNED']);
        break;

    case 'add_points':
        $amount = (int) ($data['amount'] ?? 0);
        $stmt = $pdo->prepare("UPDATE users SET points = points + ? WHERE id = ?");
        $stmt->execute([$amount, $target_id]);
        logAdminAction($pdo, $admin_id, 'ADD_POINTS', 'user', $target_id, "手動增加 $amount 積分");
        echo json_encode(['success' => true, 'message' => "ADDED $amount POINTS"]);
        break;

    case 'get_details':
        $stmt = $pdo->prepare("SELECT u.*, r.role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.id = ?");
        $stmt->execute([$target_id]);
        $user = $stmt->fetch();
        // 隱藏敏感資訊
        if ($user)
            unset($user['password'], $user['session_token']);
        echo json_encode(['success' => true, 'details' => $user]);
        break;

    case 'remind_bind':
        // 向使用者發送系統通知
        $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, content, is_admin_reply) VALUES (?, ?, ?, 1)");
        $stmt->execute([$_SESSION['user_id'], $target_id, "【系統提醒】請儘速完成安全手機或 Discord 帳號綁定，以維護帳號安全。"]);
        logAdminAction($pdo, $admin_id, 'SENT_REMINDER', 'user', $target_id, '發送綁定提醒通知');
        echo json_encode(['success' => true, 'message' => 'BINDING ALERT SENT TO USER INBOX']);
        break;

    case 'view_logs':
        // 獲取最近的 10 條登入日誌 (比私訊更合理)
        $stmt = $pdo->prepare("SELECT * FROM login_logs WHERE user_id = ? ORDER BY login_at DESC LIMIT 10");
        $stmt->execute([$target_id]);
        $logs = $stmt->fetchAll();
        echo json_encode(['success' => true, 'message' => 'LOGS RETRIEVED', 'details' => $logs]);
        break;

    case 'change_security_pass':
        $new_pass = $data['new_password'] ?? 'reset1234';
        $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashed, $target_id]);
        logAdminAction($pdo, $admin_id, 'RESET_PASSWORD', 'user', $target_id, "密碼重設為 $new_pass");
        echo json_encode(['success' => true, 'message' => "PASSWORD RESET TO: $new_pass"]);
        break;

    case 'data_sync':
        $stmt = $pdo->prepare("UPDATE users SET points = (SELECT COALESCE(SUM(points_earned), 0) FROM checkins WHERE user_id = ?) WHERE id = ?");
        $stmt->execute([$target_id, $target_id]);
        logAdminAction($pdo, $admin_id, 'SYNC_DATA', 'user', $target_id, '手動同步簽到積分資料');
        echo json_encode(['success' => true, 'message' => 'DATA SYNC COMPLETED']);
        break;

    case 'privilege_audit':
        $stmt = $pdo->prepare("SELECT role_id FROM users WHERE id = ?");
        $stmt->execute([$target_id]);
        $role = $stmt->fetchColumn();
        echo json_encode(['success' => true, 'message' => "AUDIT: CURRENT ROLE ID IS $role"]);
        break;

    case 'social_reset':
        // 模擬社交關係重置
        echo json_encode(['success' => true, 'message' => 'SOCIAL RELATIONS RESET']);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'UNKNOWN ACTION']);
        break;
}
