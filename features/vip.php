<?php
require_once __DIR__ . '/../includes/auth_logic.php';
include '../templates/header.php';

if (!Auth::isLoggedIn()) {
    header("Location: ../login.php");
    exit;
}
?>

<main class="pt-32 pb-24 px-[5%]">
    <div class="max-w-[1200px] mx-auto">
        <div class="text-center mb-20 animate-fade-in-down">
            <h2 class="text-5xl font-black uppercase tracking-tighter mb-4">尊爵 <span class="text-orange-400">VIP
                    系統</span></h2>
            <p class="text-white/40 text-xs uppercase tracking-widest">Membership Levels & Exclusive Perks</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Tier 1 -->
            <div
                class="group p-10 bg-white/5 border border-white/10 rounded-[40px] hover:border-white/30 transition-all animate-fade-in-up">
                <div class="flex justify-between items-start mb-8">
                    <div
                        class="w-16 h-16 bg-zinc-400/20 text-zinc-400 rounded-2xl flex items-center justify-center text-3xl">
                        🥉</div>
                    <span
                        class="text-[10px] font-black uppercase bg-zinc-400/20 px-3 py-1 rounded text-zinc-400">Bronze</span>
                </div>
                <h3 class="text-2xl font-black mb-6">青銅會員</h3>
                <ul class="space-y-4 mb-10 text-sm text-white/40">
                    <li class="flex items-center gap-2">✔ 每日簽到 100 積分</li>
                    <li class="flex items-center gap-2">✔ 基礎兌換倍率: 1.0x</li>
                    <li class="flex items-center gap-2 text-white/10">✘ 專屬 Discord 身分組</li>
                </ul>
                <div class="text-xs font-bold text-white/20 uppercase">目前等級</div>
            </div>

            <!-- Tier 2 -->
            <div class="group p-10 bg-white/5 border border-secondary/50 rounded-[40px] hover:scale-105 transition-all shadow-[0_0_30px_rgba(0,242,255,0.1)] relative overflow-hidden animate-fade-in-up"
                style="animation-delay: 0.2s">
                <div
                    class="absolute top-0 right-0 p-4 bg-secondary text-bg-dark text-[10px] font-black uppercase rounded-bl-3xl">
                    Popular</div>
                <div class="flex justify-between items-start mb-8">
                    <div
                        class="w-16 h-16 bg-secondary/20 text-secondary rounded-2xl flex items-center justify-center text-4xl">
                        🥈</div>
                    <span
                        class="text-[10px] font-black uppercase bg-secondary/20 px-3 py-1 rounded text-secondary">Silver</span>
                </div>
                <h3 class="text-2xl font-black mb-6">白銀會員</h3>
                <ul class="space-y-4 mb-10 text-sm text-white/60">
                    <li class="flex items-center gap-2">✔ 每日簽到 150 積分</li>
                    <li class="flex items-center gap-2">✔ 兌換倍率: 1.2x</li>
                    <li class="flex items-center gap-2">✔ 專屬 Discord 身分組</li>
                </ul>
                <button class="w-full btn-neon py-4 text-xs">啟動升級 (5000 PTS)</button>
            </div>

            <!-- Tier 3 -->
            <div class="group p-10 bg-white/5 border border-yellow-400/50 rounded-[40px] hover:border-yellow-400 transition-all shadow-[0_0_30px_rgba(250,204,21,0.1)] animate-fade-in-up"
                style="animation-delay: 0.4s">
                <div class="flex justify-between items-start mb-8">
                    <div
                        class="w-16 h-16 bg-yellow-400/20 text-yellow-400 rounded-2xl flex items-center justify-center text-4xl">
                        🥇</div>
                    <span
                        class="text-[10px] font-black uppercase bg-yellow-400/20 px-3 py-1 rounded text-yellow-400">Gold</span>
                </div>
                <h3 class="text-2xl font-black mb-6">黃金會員</h3>
                <ul class="space-y-4 mb-10 text-sm text-white/80 font-bold">
                    <li class="flex items-center gap-2">✔ 每日簽到 300 積分</li>
                    <li class="flex items-center gap-2">✔ 兌換倍率: 1.5x</li>
                    <li class="flex items-center gap-2 text-secondary">✔ 抽獎機率提升 2%</li>
                </ul>
                <button
                    class="w-full py-4 border border-yellow-400/30 text-yellow-400 rounded-full hover:bg-yellow-400/10 transition-all text-xs font-black">鎖定中</button>
            </div>
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>