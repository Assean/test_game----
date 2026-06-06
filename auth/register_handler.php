<?php
/**
 * register_handler.php - 處理三段式註冊邏輯
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/security.php';

require_once __DIR__ . '/../includes/auth_logic.php';

// 0. 速率限制
if (!$security->checkRateLimit('register', 3, 300)) { // 5 分鐘內最多 3 次
    echo json_encode(['success' => false, 'message' => '嘗試次數過多，請稍後再試']);
    exit;
}

// 1. CSRF 驗證
if (!isset($_POST['csrf_token']) || !Auth::verifyCsrfToken($_POST['csrf_token'])) {
    echo json_encode(['success' => false, 'message' => 'CSRF 驗證失敗']);
    exit;
}

// 2. 收集資料
$email = trim($_POST['email'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';
$discord_id = trim($_POST['discord_id'] ?? '');
$roblox_id = trim($_POST['roblox_id'] ?? '');

// 3. 基礎驗證
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Email 格式不正確']);
    exit;
}

if (strlen($username) < 3) {
    echo json_encode(['success' => false, 'message' => '使用者名稱至少需要 3 個字元']);
    exit;
}

if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => '密碼至少需要 6 個字元']);
    exit;
}

if ($password !== $confirm) {
    echo json_encode(['success' => false, 'message' => '密碼與確認密碼不一致']);
    exit;
}

if (empty($roblox_id)) {
    echo json_encode(['success' => false, 'message' => 'Roblox ID 為必填項目']);
    exit;
}

try {
    // 4. 檢查 Email 或 Username 是否已存在
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ? LIMIT 1");
    $stmt->execute([$email, $username]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Email 或使用者名稱已被註冊']);
        exit;
    }

    // 5. 加密密碼與寫入資料庫
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password, discord_id, roblox_id, role_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $username,
        $email,
        $hashedPassword,
        $discord_id,
        $roblox_id,
        ROLE_USER // 預設權限
    ]);

    // 6. 自動登入（使用 Auth 類別統一處理 Session）
    $userId = $pdo->lastInsertId();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    
    $auth->setSession($user);

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    // Bug 3 修復：不回傳資料庫錯誤細節給使用者
    error_log('[REGISTER ERROR] ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => '系統忙碌中，請稍後再試']);
}
?>