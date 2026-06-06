<?php
/**
 * verify_email.php — 處理 Email 驗證連結（新功能 5）
 */

require_once 'includes/auth_logic.php';
include 'templates/header.php';

$token = $_GET['token'] ?? '';
$success = false;
$message = '';

if (empty($token)) {
    $message = '無效的驗證連結';
} else {
    try {
        $stmt = $pdo->prepare("SELECT id, username FROM users WHERE email_verify_token = ? LIMIT 1");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if ($user) {
            $stmt = $pdo->prepare("UPDATE users SET email_verified = 1, email_verify_token = NULL WHERE id = ?");
            $stmt->execute([$user['id']]);
            $success = true;
            $message = 'Email 驗證成功！歡迎加入 TEST GAME。';
            
            // 系統通知
            $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, content, type) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user['id'], 'Email 驗證成功', '您的帳號已完成驗證，現在可以使用所有功能了！', 'system']);
            
            // 如果已登入，更新 Session
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user['id']) {
                $_SESSION['email_verified'] = true;
            }
        } else {
            $message = '驗證連結無效或已過期';
        }
    } catch (PDOException $e) {
        error_log('[VERIFY ERROR] ' . $e->getMessage());
        $message = '系統錯誤，請稍後再試';
    }
}
?>

<main class="pt-32 pb-24 px-[5%]">
    <div class="max-w-[500px] mx-auto bg-white/5 border border-white/10 backdrop-blur-2xl p-12 rounded-[40px] text-center">
        <div class="text-6xl mb-8"><?php echo $success ? '🎉' : '❌'; ?></div>
        <h2 class="text-3xl font-black mb-4 uppercase">Email <span class="<?php echo $success ? 'text-secondary' : 'text-red-500'; ?>">驗證結果</span></h2>
        <p class="text-white/60 mb-10"><?php echo htmlspecialchars($message); ?></p>
        
        <a href="<?php echo Auth::isLoggedIn() ? 'profile.php' : 'login.php'; ?>" class="btn-neon py-4 px-10">
            <?php echo Auth::isLoggedIn() ? '前往個人檔案' : '前往登入頁面'; ?>
        </a>
    </div>
</main>

<?php include 'templates/footer.php'; ?>
