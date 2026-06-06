<?php
/**
 * shop_handler.php - 處理商品購買
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

$item_id = (int) ($_POST['item_id'] ?? 0);
$user_id = $_SESSION['user_id'];

if ($item_id <= 0) {
    echo json_encode(['success' => false, 'message' => '無效的商品 ID']);
    exit;
}

try {
    // 1. 取得商品資訊
    $stmt = $pdo->prepare("SELECT * FROM shop_items WHERE id = ?");
    $stmt->execute([$item_id]);
    $item = $stmt->fetch();

    if (!$item) {
        echo json_encode(['success' => false, 'message' => '找不到該商品']);
        exit;
    }

    if ($item['stock'] !== -1 && $item['stock'] <= 0) {
        echo json_encode(['success' => false, 'message' => '商品已售罄']);
        exit;
    }

    // 2. 取得使用者積分
    $stmt = $pdo->prepare("SELECT points FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user_points = $stmt->fetchColumn();

    if ($user_points < $item['price']) {
        echo json_encode(['success' => false, 'message' => '積分不足']);
        exit;
    }

    $pdo->beginTransaction();

    // 3. 扣除積分
    $stmt = $pdo->prepare("UPDATE users SET points = points - ? WHERE id = ?");
    $stmt->execute([$item['price'], $user_id]);

    // 4. 更新庫存 (如果有限)
    if ($item['stock'] !== -1) {
        $stmt = $pdo->prepare("UPDATE shop_items SET stock = stock - 1 WHERE id = ?");
        $stmt->execute([$item_id]);
    }

    // 5. 紀錄購買 (可在此新增 purchases 紀錄表)
    // 這裡先簡單發送到 Discord Webhook 作為通知
    require_once __DIR__ . '/../includes/discord_api.php';
    $discord = new DiscordAPI(DISCORD_CLIENT_ID, DISCORD_CLIENT_SECRET, DISCORD_REDIRECT_URI, DISCORD_WEBHOOK_URL);
    $discord->sendWebhook("🛍️ 玩家 **{$_SESSION['username']}** 購買了 **{$item['name']}** (價格: {$item['price']} PTS)");

    $pdo->commit();
    echo json_encode(['success' => true, 'new_balance' => $user_points - $item['price']]);

} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => '系統錯誤']);
}
?>
