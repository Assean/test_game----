<?php
$token = $_GET['token'] ?? '';
require_once 'includes/auth_logic.php';
?>
<?php include 'templates/header.php'; ?>

<main class="pt-32 pb-24 px-[5%]">
    <div class="max-w-[480px] mx-auto bg-white/5 border border-white/10 backdrop-blur-2xl p-10 rounded-3xl">
        <h2 class="text-3xl font-black mb-2 uppercase text-secondary">重設 <span class="text-white">密碼</span></h2>
        <p class="text-white/40 text-sm mb-8">請輸入您的新密碼（至少 8 字元，含大小寫字母和數字）。</p>

        <?php if (empty($token)): ?>
            <div class="p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 text-sm">
                無效的重設連結，請重新申請。
                <a href="forgot_password.php" class="underline ml-2">返回申請</a>
            </div>
        <?php else: ?>
        <div id="reset-result" class="hidden mb-6 p-4 rounded-xl text-sm font-bold"></div>

        <form id="reset-form" class="space-y-5">
            <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <div>
                <label class="block text-xs font-black text-white/40 uppercase tracking-widest mb-2">新密碼</label>
                <input type="password" name="password" required
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 focus:border-secondary outline-none transition-all"
                    placeholder="至少 8 字元">
            </div>
            <div>
                <label class="block text-xs font-black text-white/40 uppercase tracking-widest mb-2">確認新密碼</label>
                <input type="password" name="confirm_password" required
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 focus:border-secondary outline-none transition-all"
                    placeholder="再輸入一次">
            </div>
            <button type="submit" id="reset-btn" class="w-full btn-neon py-5">確認重設密碼</button>
        </form>
        <?php endif; ?>
    </div>
</main>

<script>
const form = document.getElementById('reset-form');
if (form) {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('reset-btn');
        btn.disabled = true;
        btn.textContent = '處理中...';
        try {
            const res  = await fetch('auth/reset_handler.php', { method: 'POST', body: new FormData(this) });
            const data = await res.json();
            const box  = document.getElementById('reset-result');
            box.classList.remove('hidden','bg-green-500/10','text-green-400','bg-red-500/10','text-red-400');
            if (data.success) {
                box.classList.add('bg-green-500/10','text-green-400');
                box.innerHTML = data.message + ' <a href="login.php" class="underline">前往登入</a>';
                form.classList.add('hidden');
            } else {
                box.classList.add('bg-red-500/10','text-red-400');
                box.textContent = data.message;
            }
            box.classList.remove('hidden');
        } finally {
            btn.disabled = false;
            btn.textContent = '確認重設密碼';
        }
    });
}
</script>
<?php include 'templates/footer.php'; ?>
