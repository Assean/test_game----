<?php
require_once __DIR__ . '/includes/discord_api.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/templates/header.php';

$message_sent = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $content = $_POST['content'] ?? '';

    if (!empty($email) && !empty($content)) {
        // 1. 寫入資料庫
        $stmt = $pdo->prepare("INSERT INTO feedback (email, content, user_id) VALUES (?, ?, ?)");
        $user_id = Auth::isLoggedIn() ? $_SESSION['user_id'] : null;
        $stmt->execute([$email, $content, $user_id]);

        // 2. 觸發 Discord Webhook
        $discord = new DiscordAPI(DISCORD_CLIENT_ID, DISCORD_CLIENT_SECRET, DISCORD_REDIRECT_URI, DISCORD_WEBHOOK_URL);
        $webhook_msg = "📩 **新玩家反饋**\n**Email:** {$email}\n**內容:** {$content}";
        $discord->sendWebhook($webhook_msg);

        $message_sent = true;
    }
}
?>

<main class="pt-32 pb-24 px-[5%]">
    <div class="max-w-[700px] mx-auto bg-white/5 border border-white/10 backdrop-blur-xl p-10 rounded-3xl">
        <h2 class="text-3xl font-black mb-8 uppercase text-secondary">聯繫我們 <span class="text-white">&</span> 反饋</h2>

        <?php if ($message_sent): ?>
            <div class="bg-green-500/20 border border-green-500 text-green-400 p-5 rounded-xl mb-8">
                感謝您的反饋！我們已收到您的訊息，並會盡快處理。
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-bold text-white/50 mb-2 uppercase">電子郵件</label>
                <input type="email" name="email" required
                    class="w-full bg-white/10 border border-white/10 rounded-xl px-5 py-4 focus:border-secondary transition-all outline-none"
                    placeholder="your@email.com">
            </div>
            <div>
                <label class="block text-sm font-bold text-white/50 mb-2 uppercase">訊息內容</label>
                <textarea name="content" required rows="6"
                    class="w-full bg-white/10 border border-white/10 rounded-xl px-5 py-4 focus:border-secondary transition-all outline-none"
                    placeholder="請輸入您想對我們說的話..."></textarea>
            </div>
            <button type="submit" class="w-full btn-neon py-5">
                送出訊息
            </button>
        </form>
    </div>
</main>

<?php include 'templates/footer.php'; ?>