<?php
/**
 * auth/community_handler.php — 社群交流板 API（Bug 11 修復）
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_logic.php';
require_once __DIR__ . '/../includes/validator.php';
require_once __DIR__ . '/../includes/error_handler.php';

if (!Auth::isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => '請先登入才能發文']);
    exit;
}

if (!Auth::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
    echo json_encode(['success' => false, 'message' => 'CSRF 驗證失敗']);
    exit;
}

$content = Validator::sanitize($_POST['content'] ?? '');
$user_id = $_SESSION['user_id'];

if (empty($content)) {
    echo json_encode(['success' => false, 'message' => '內容不能為空']);
    exit;
}
if (mb_strlen($content) > 500) {
    echo json_encode(['success' => false, 'message' => '內容不能超過 500 字元']);
    exit;
}

try {
    $pdo->prepare("INSERT INTO community_posts (user_id, content) VALUES (?, ?)")
        ->execute([$user_id, $content]);

    // 建立通知（系統確認）
    sendNotification($pdo, $user_id, '發文成功', "您的交流板貼文已成功發布！", 'social');

    echo json_encode(['success' => true, 'message' => '發文成功']);
} catch (\Throwable $e) {
    jsonError('發文失敗，請稍後再試', $e, 'community_handler');
}

/**
 * 通用通知發送函數
 */
function sendNotification(PDO $pdo, int $userId, string $title, string $content, string $type = 'system'): void
{
    try {
        $pdo->prepare("INSERT INTO notifications (user_id, title, content, type) VALUES (?, ?, ?, ?)")
            ->execute([$userId, $title, $content, $type]);
    } catch (\Throwable $e) {
        error_log('[NOTIFICATION ERROR] ' . $e->getMessage());
    }
}
?>
