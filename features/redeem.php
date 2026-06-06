<?php
require_once __DIR__ . '/../includes/auth_logic.php';
include '../templates/header.php';
?>

<main class="pt-32 pb-24 px-[5%]">
    <div
        class="max-w-[600px] mx-auto bg-white/5 border border-white/10 backdrop-blur-2xl p-12 rounded-[40px] text-center animate-fade-in-up">
        <div class="w-20 h-20 bg-yellow-400/20 rounded-full flex items-center justify-center text-3xl mx-auto mb-8">🎁
        </div>
        <h2 class="text-3xl font-black mb-4 uppercase">序號 <span class="text-yellow-400">兌換中心</span></h2>
        <p class="text-white/40 mb-10 text-sm">輸入您的 12 位活動序號以領取限時獎勵</p>

        <form id="redeem-form" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
            <input type="text" name="code" required
                class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-5 text-center text-2xl font-mono tracking-[0.3em] focus:border-yellow-400 outline-none transition-all uppercase"
                placeholder="TEST-XXXX-XXXX">
            <button type="submit"
                class="w-full btn-neon py-5 bg-gradient-to-r from-yellow-500 to-orange-500 text-black font-black">
                立即兌換獎勵
            </button>
        </form>

        <div id="redeem-result" class="mt-8 hidden animate-fade-in-up">
            <div class="p-6 bg-white/5 border border-white/5 rounded-2xl">
                <p id="redeem-msg" class="text-lg font-bold"></p>
            </div>
        </div>
    </div>
</main>

<script>
    document.getElementById('redeem-form').onsubmit = async (e) => {
        // ... (rest of the script)
    };
</script>

<?php include '../templates/footer.php'; ?>