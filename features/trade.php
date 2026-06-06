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
        <div class="flex items-center justify-between mb-16 animate-fade-in-down">
            <div>
                <h2 class="text-4xl font-black uppercase tracking-tighter mb-2">貿易 <span
                        class="text-secondary">中心</span></h2>
                <p class="text-white/40 text-xs tracking-[0.3em] uppercase">Player-to-Player Trading Hub</p>
            </div>
            <div class="bg-secondary/10 border border-secondary/20 px-6 py-3 rounded-2xl">
                <span class="text-secondary font-black text-sm uppercase">在線交易中: 124</span>
            </div>
        </div>

        <div class="bg-white/5 border border-white/10 rounded-[40px] p-12 backdrop-blur-3xl animate-fade-in-up">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="space-y-6">
                    <h3 class="text-xl font-bold border-l-4 border-secondary pl-4">我的報價 (My Offers)</h3>
                    <div class="p-6 bg-white/5 border border-white/5 rounded-2xl text-center text-white/20 italic">
                        目前沒有進行中的交易
                    </div>
                    <button class="w-full btn-neon py-4">發起新貿易報價</button>
                </div>
                <div class="space-y-6">
                    <h3 class="text-xl font-bold border-l-4 border-primary pl-4">熱門需求 (Trending)</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl border border-white/5">
                            <span class="font-bold">Neon Katana</span>
                            <span class="text-secondary font-black">尋求中</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl border border-white/5">
                            <span class="font-bold">Phoenix Pet</span>
                            <span class="text-secondary font-black">熱賣中</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>