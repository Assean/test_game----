<?php
require_once __DIR__ . '/../includes/auth_logic.php';
include '../templates/header.php';

if (!Auth::isLoggedIn()) {
    header("Location: ../login.php");
    exit;
}
?>

<main class="pt-32 pb-24 px-[5%]">
    <div class="max-w-[1000px] mx-auto">
        <div class="mb-16 animate-fade-in-down">
            <h2 class="text-4xl font-black uppercase tracking-tighter mb-2">玩家 <span class="text-secondary">支援中心</span>
            </h2>
            <p class="text-white/40 text-xs tracking-[0.3em] uppercase">24/7 Help & Ticket System</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 animate-fade-in-up">
            <div
                class="bg-white/5 border border-white/10 rounded-[40px] p-10 text-center group hover:border-secondary transition-all cursor-pointer">
                <div class="text-4xl mb-6 group-hover:scale-110 transition-transform">📄</div>
                <h3 class="text-lg font-bold mb-2">常見問題</h3>
                <p class="text-white/40 text-xs">解決 90% 的常見疑問。</p>
            </div>
            <div
                class="bg-white/5 border border-white/10 rounded-[40px] p-10 text-center group hover:border-secondary transition-all cursor-pointer">
                <div class="text-4xl mb-6 group-hover:scale-110 transition-transform">🎫</div>
                <h3 class="text-lg font-bold mb-2">提交工單</h3>
                <p class="text-white/40 text-xs">專人為您處理複雜問題。</p>
            </div>
            <div
                class="bg-indigo-500/20 border border-indigo-500/30 rounded-[40px] p-10 text-center group hover:scale-105 transition-all cursor-pointer">
                <div class="text-4xl mb-6">💬</div>
                <h3 class="text-lg font-bold mb-2">Discord 支援</h3>
                <p class="text-white/40 text-xs">加入社群即時互動。</p>
            </div>
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>