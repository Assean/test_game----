<?php
/**
 * redeem_handler.php - 處理序號兌換
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

$code = strtoupper(trim($_POST['code'] ?? ''));
$user_id = $_SESSION['user_id'];

if (empty($code)) {
    echo json_encode(['success' => false, 'message' => '請輸入序號']);
    exit;
}

try {
    // 1. 檢查序號是否存在且有效
    $stmt = $pdo->prepare("SELECT * FROM codes WHERE code = ? AND (expires_at IS NULL OR expires_at > NOW()) LIMIT 1");
    $stmt->execute([$code]);
    $codeData = $stmt->fetch();

    if (!$codeData) {
        echo json_encode(['success' => false, 'message' => '序號無效或已過期']);
        exit;
    }

    if ($codeData['used_count'] >= $codeData['max_uses']) {
        echo json_encode(['success' => false, 'message' => '序號兌換次數已達上限']);
        exit;
    }

    // Bug 9 修復：檢查此使用者是否已對此序號兑換過（使用 code_redemptions 表）
    $stmt = $pdo->prepare("SELECT id FROM code_redemptions WHERE user_id = ? AND code_id = ?");
    $stmt->execute([$user_id, $codeData['id']]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => '您已兑換過此序號']);
        exit;
    }

    $pdo->beginTransaction();

    // 2. 根據獎勵類型進行發放
    if ($codeData['reward_type'] === 'points') {
        $amount = (int) $codeData['reward_value'];
        $stmt = $pdo->prepare("UPDATE users SET points = points + ? WHERE id = ?");
        $stmt->execute([$amount, $user_id]);
    }

    // 3. 更新序號使用次數與紀錄
    $stmt = $pdo->prepare("UPDATE codes SET used_count = used_count + 1 WHERE id = ?");
    $stmt->execute([$codeData['id']]);

    $stmt = $pdo->prepare("INSERT INTO code_redemptions (user_id, code_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $codeData['id']]);

    $pdo->commit();
    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    if ($pdo->inTransaction())
        $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => '系統錯誤']);
}
?>