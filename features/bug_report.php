<?php
require_once __DIR__ . '/../includes/auth_logic.php';
include '../templates/header.php';

if (!Auth::isLoggedIn()) {
    header("Location: ../login.php");
    exit;
}
?>

<main class="pt-32 pb-24 px-[5%]">
    <div
        class="max-w-[800px] mx-auto bg-white/5 border border-white/10 backdrop-blur-2xl p-12 rounded-[40px] animate-fade-in-up">
        <div class="flex items-center gap-6 mb-12">
            <div class="w-16 h-16 bg-red-400/20 rounded-2xl flex items-center justify-center text-3xl">🐛</div>
            <div>
                <h2 class="text-3xl font-black uppercase tracking-tighter mb-1">問題 <span
                        class="text-red-400">回報系統</span></h2>
                <p class="text-white/40 text-[10px] tracking-[0.3em] uppercase">Bug Tracker & Community Feedback</p>
            </div>
        </div>

        <form id="bug-form" class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-[10px] font-black text-white/40 uppercase mb-3">問題分類</label>
                    <select name="category"
                        class="w-full bg-white/10 border border-white/10 rounded-2xl px-5 py-4 focus:border-red-400 outline-none transition-all appearance-none cursor-pointer">
                        <option value="Game Bug">遊戲漏洞 (Game Bug)</option>
                        <option value="Visual Error">視覺錯誤 (Visual Error)</option>
                        <option value="Account Issue">帳號問題 (Account Issue)</option>
                        <option value="Suggestion">功能建議 (Suggestion)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-white/40 uppercase mb-3">發生頻率</label>
                    <select name="frequency"
                        class="w-full bg-white/10 border border-white/10 rounded-2xl px-5 py-4 focus:border-red-400 outline-none transition-all appearance-none cursor-pointer">
                        <option value="Always">每次都會 (Always)</option>
                        <option value="Random">隨機發生 (Random)</option>
                        <option value="Once">僅此一次 (Once)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-white/40 uppercase mb-3">詳細描述</label>
                <textarea required name="description" rows="5"
                    class="w-full bg-white/10 border border-white/10 rounded-2xl px-5 py-4 focus:border-red-400 outline-none transition-all"
                    placeholder="請盡可能詳細描述問題發生的步驟..."></textarea>
            </div>

            <div class="p-6 bg-red-400/10 border border-red-400/20 rounded-2xl">
                <p class="text-xs text-red-300 leading-relaxed font-bold">
                    💡 提示：有效的 Bug 回報經官方確認後，最高可獲得 <span class="underline">5,000 積分</span> 作為獎勵。
                </p>
            </div>

            <button type="submit"
                class="w-full btn-neon py-5 bg-gradient-to-r from-red-500 to-pink-600 text-white font-black">
                提交報告
            </button>
        </form>

        <div id="bug-success" class="hidden text-center py-10 animate-fade-in-up">
            <div class="text-5xl mb-6">📤</div>
            <h3 class="text-2xl font-black mb-4">回報已送出！</h3>
            <p class="text-white/40 text-sm mb-8">感謝您的協助，我們會盡快處理您的回報。</p>
            <button onclick="location.reload()"
                class="text-secondary font-black text-xs uppercase tracking-widest">返回表格</button>
        </div>
    </div>
</main>

<script>
    document.getElementById('bug-form').onsubmit = (e) => {
        e.preventDefault();
        document.getElementById('bug-form').classList.add('hidden');
        document.getElementById('bug-success').classList.remove('hidden');
    };
</script>

<?php include '../templates/footer.php'; ?>