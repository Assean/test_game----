<?php
/**
 * checkin_handler.php - 處理每日簽到
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';

require_once __DIR__ . '/../includes/auth_logic.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => '未登入']);
    exit;
}

if (!Auth::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
    echo json_encode(['success' => false, 'message' => '安全驗證失敗 (CSRF)']);
    exit;
}

$user_id = $_SESSION['user_id'];
$today = date('Y-m-d');
$points_reward = 100;

try {
    // 1. 檢查今日是否已簽到
    $stmt = $pdo->prepare("SELECT id FROM checkins WHERE user_id = ? AND checkin_date = ?");
    $stmt->execute([$user_id, $today]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => '今日已簽到']);
        exit;
    }

    // 1.5 檢查連續簽到天數 (Streak)
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    $stmt = $pdo->prepare("SELECT streak FROM checkins WHERE user_id = ? AND checkin_date = ?");
    $stmt->execute([$user_id, $yesterday]);
    $lastCheckin = $stmt->fetch();

    $streak = 1;
    if ($lastCheckin) {
        $streak = (int)$lastCheckin['streak'] + 1;
    }

    // 獎勵規則
    $base_points = 100;
    $bonus_points = 0;
    $milestone_msg = "";

    if ($streak === 7) {
        $bonus_points = 500;
        $milestone_msg = "連續簽到 7 天達成！額外贈送 500 積分！";
    } elseif ($streak === 14) {
        $bonus_points = 1000;
        $milestone_msg = "連續簽到 14 天達成！額外贈送 1000 積分！";
    } elseif ($streak === 30) {
        $bonus_points = 3000;
        $milestone_msg = "傳奇里程碑：連續簽到 30 天！額外贈送 3000 積分！";
    }

    $total_reward = $base_points + $bonus_points;

    $pdo->beginTransaction();

    // 2. 寫入簽到紀錄
    $stmt = $pdo->prepare("INSERT INTO checkins (user_id, checkin_date, points_earned, streak) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $today, $total_reward, $streak]);

    // 3. 更新使用者總積分
    $stmt = $pdo->prepare("UPDATE users SET points = points + ? WHERE id = ?");
    $stmt->execute([$total_reward, $user_id]);

    // 4. 發送通知
    $notif_title = "簽到成功 (第 $streak 天)";
    $notif_content = "您已成功簽到，獲得 $base_points 積分。";
    if ($bonus_points > 0) {
        $notif_content .= " " . $milestone_msg;
    }
    
    $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, content, type) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $notif_title, $notif_content, 'reward']);

    $pdo->commit();
    echo json_encode([
        'success' => true, 
        'streak' => $streak, 
        'points' => $total_reward,
        'message' => $milestone_msg ?: "簽到成功！"
    ]);

} catch (PDOException $e) {
    if ($pdo->inTransaction())
        $pdo->rollBack();
    error_log('[CHECKIN ERROR] ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => '系統忙碌中，請稍後再試']);
}
?>