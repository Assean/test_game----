<?php
/**
 * admin_2fa_handler.php - 處理管理員二次驗證
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_logic.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login.php");
    exit;
}

// 1. 基本安全檢查
if (!isset($_SESSION['pending_admin_id'])) {
    header("Location: ../login.php");
    exit;
}

// 2. CSRF 驗證
if (!isset($_POST['csrf_token']) || !Auth::verifyCsrfToken($_POST['csrf_token'])) {
    die('CSRF 驗證失敗');
}

$admin_email_input = $_POST['admin_email'] ?? '';
$user_id = $_SESSION['pending_admin_id'];

try {
    // 3. 比對資料庫中的 admin_email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role_id = ? LIMIT 1");
    $stmt->execute([$user_id, ROLE_ADMIN]);
    $user = $stmt->fetch();

    if ($user && $user['admin_email'] === $admin_email_input) {
        // 4. 驗證成功，完成登入流程
        session_regenerate_id(true); // 重新生成 Session ID

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['discord_id'] = $user['discord_id'];
        $_SESSION['roblox_id'] = $user['roblox_id'];

        // 清除暫存
        unset($_SESSION['pending_admin_id']);
        unset($_SESSION['pending_admin_user']);

        header("Location: ../profile.php");
        exit;
    } else {
        // 5. 驗證失敗
        header("Location: admin_2fa.php?error=1");
        exit;
    }

} catch (PDOException $e) {
    die('資料庫錯誤：' . $e->getMessage());
}
?>