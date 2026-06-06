<?php
/**
 * login_handler.php - 處理使用者登入
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/security.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../includes/auth_logic.php';

// 0. 速率限制 (1 分鐘內最多 5 次嘗試)
if (!$security->checkRateLimit('login', 5, 60)) {
    header("Location: ../login.php?error=too_many_attempts");
    exit;
}

// 1. CSRF 驗證
if (!isset($_POST['csrf_token']) || !Auth::verifyCsrfToken($_POST['csrf_token'])) {
    header("Location: ../login.php?error=csrf");
    exit;
}

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

try {
    // Bug 7 修復：先取 role_id 判斷是否為管理員，若是則走 2FA 流程，否則使用 Auth::login() 統一處理
    $stmt = $pdo->prepare("SELECT role_id FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $check = $stmt->fetch();

    if ($check && $check['role_id'] == ROLE_ADMIN) {
        // 管理員：需手動驗證密碼並進入 2FA 流程（不在此建立完整 Session）
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            if ($user['is_banned']) {
                header("Location: ../login.php?error=banned");
                exit;
            }
            $_SESSION['pending_admin_id']   = $user['id'];
            $_SESSION['pending_admin_user'] = $user['username'];
            header("Location: admin_2fa.php");
            exit;
        } else {
            header("Location: ../login.php?error=1");
            exit;
        }
    }

    // 一般使用者：交由 Auth::login() 處理，確保 session_token 機制正常運作
    $result = $auth->login($email, $password);
    if ($result === true) {
        header("Location: ../profile.php");
        exit;
    } elseif ($result === 'BANNED') {
        header("Location: ../login.php?error=banned");
        exit;
    } else {
        header("Location: ../login.php?error=1");
        exit;
    }

} catch (PDOException $e) {
    // Bug 2 修復：不將資料庫錯誤細節顯示給使用者
    error_log('[LOGIN ERROR] ' . $e->getMessage());
    header("Location: ../login.php?error=system");
    exit;
}
?>