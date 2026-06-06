<?php
require_once __DIR__ . '/../includes/auth_logic.php';
include '../templates/header.php';

if (!Auth::isLoggedIn()) {
    header("Location: ../login.php");
    exit;
}
?>

<main class="pt-32 pb-24 px-[5%]">
    <div class="max-w-[900px] mx-auto">
        <div class="mb-16 animate-fade-in-down">
            <h2 class="text-4xl font-black uppercase tracking-tighter mb-2">遊戲 <span class="text-secondary">百科</span>
            </h2>
            <p class="text-white/40 text-xs tracking-[0.3em] uppercase">The Official $TEST Encyclopedia</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 animate-fade-in-up">
            <div class="space-y-8">
                <div
                    class="p-8 bg-white/5 border border-white/10 rounded-[40px] hover:bg-white/10 transition-all cursor-pointer">
                    <h3 class="text-xl font-bold mb-2">基礎教學 (Getting Started)</h3>
                    <p class="text-white/40 text-sm">新手必读：從零開始的生存之道。</p>
                </div>
                <div
                    class="p-8 bg-white/5 border border-white/10 rounded-[40px] hover:bg-white/10 transition-all cursor-pointer">
                    <h3 class="text-xl font-bold mb-2">遺產裝備 (Legendary Gear)</h3>
                    <p class="text-white/40 text-sm">數據分析：每件武器的隱藏屬性。</p>
                </div>
            </div>
            <div class="bg-indigo-500/10 border border-indigo-500/20 rounded-[40px] p-10 flex flex-col justify-center">
                <div class="text-4xl mb-6">📚</div>
                <h4 class="text-2xl font-black mb-4">貢獻知識</h4>
                <p class="text-white/40 text-sm leading-relaxed mb-8">百科由社區共同維護，提交您的攻略並獲得高額積分獎勵。</p>
                <button class="btn-neon">提交編輯請求</button>
            </div>
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>