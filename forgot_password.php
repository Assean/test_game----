<?php require_once 'includes/auth_logic.php'; ?>
<?php include 'templates/header.php'; ?>

<main class="pt-32 pb-24 px-[5%]">
    <div class="max-w-[480px] mx-auto bg-white/5 border border-white/10 backdrop-blur-2xl p-10 rounded-3xl">
        <h2 class="text-3xl font-black mb-2 uppercase text-secondary">忘記 <span class="text-white">密碼</span></h2>
        <p class="text-white/40 text-sm mb-8">輸入您的 Email，系統將產生密碼重設連結。</p>

        <div id="forgot-result" class="hidden mb-6 p-4 rounded-xl text-sm font-bold"></div>
        <div id="reset-link-box" class="hidden mb-6 p-4 bg-secondary/10 border border-secondary/20 rounded-xl">
            <p class="text-secondary text-xs font-black uppercase mb-2">重設連結（開發模式直接顯示）：</p>
            <a id="reset-link-url" href="#" class="text-white text-xs break-all hover:underline"></a>
        </div>

        <form id="forgot-form" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
            <div>
                <label class="block text-xs font-black text-white/40 uppercase tracking-widest mb-2">Email 地址</label>
                <input type="email" name="email" required
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 focus:border-secondary outline-none transition-all"
                    placeholder="your@email.com">
            </div>
            <button type="submit" id="forgot-btn" class="w-full btn-neon py-5">送出重設請求</button>
        </form>

        <div class="mt-8 text-center text-white/40 text-sm">
            記起來了？<a href="login.php" class="text-secondary hover:underline">返回登入</a>
        </div>
    </div>
</main>

<script>
document.getElementById('forgot-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('forgot-btn');
    btn.disabled = true;
    btn.textContent = '處理中...';
    const formData = new FormData(this);
    try {
        const res  = await fetch('auth/forgot_handler.php', { method: 'POST', body: formData });
        const data = await res.json();
        const box  = document.getElementById('forgot-result');
        box.classList.remove('hidden', 'bg-green-500/10', 'text-green-400', 'bg-red-500/10', 'text-red-400');
        if (data.success) {
            box.classList.add('bg-green-500/10', 'text-green-400');
            box.textContent = data.message;
            if (data.reset_link) {
                const linkBox = document.getElementById('reset-link-box');
                document.getElementById('reset-link-url').textContent = data.reset_link;
                document.getElementById('reset-link-url').href = data.reset_link;
                linkBox.classList.remove('hidden');
            }
        } else {
            box.classList.add('bg-red-500/10', 'text-red-400');
            box.textContent = data.message;
        }
        box.classList.remove('hidden');
    } finally {
        btn.disabled = false;
        btn.textContent = '送出重設請求';
    }
});
</script>

<?php include 'templates/footer.php'; ?>
