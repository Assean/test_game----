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
        <div class="flex items-end justify-between mb-16 animate-fade-in-down">
            <div>
                <h2 class="text-4xl font-black uppercase tracking-tighter mb-2">家園 <span
                        class="text-orange-400">預覽</span></h2>
                <p class="text-white/40 text-xs tracking-[0.3em] uppercase">Housing & Interior Showcase</p>
            </div>
            <div class="bg-orange-400/10 border border-orange-400/20 px-6 py-2 rounded-full">
                <span class="text-orange-400 font-black text-[10px] uppercase">當前裝扮值: 1,500</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 animate-fade-in-up">
            <div class="bg-white/5 border border-white/10 rounded-[50px] overflow-hidden group relative aspect-[4/3]">
                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-1000 group-hover:scale-110"
                    style="background-image: url('https://placeholder.com/house');"></div>
                <div class="absolute bottom-0 left-0 right-0 p-8 bg-gradient-to-t from-bg-dark to-transparent">
                    <h3 class="text-2xl font-black mb-2">霓虹公寓 (Neon Suite)</h3>
                    <p class="text-white/40 text-sm">解鎖條件: 等級 100 / 尊爵 VIP</p>
                </div>
            </div>
            <div class="flex flex-col justify-center space-y-8">
                <div
                    class="p-8 bg-white/5 border border-white/10 rounded-[30px] hover:border-orange-400 transition-all cursor-pointer">
                    <h4 class="font-bold mb-2">家具商城 (Furniture Shop)</h4>
                    <p class="text-white/40 text-xs">購買超過 500 種獨具特色的家具。</p>
                </div>
                <div
                    class="p-8 bg-white/5 border border-white/10 rounded-[30px] hover:border-orange-400 transition-all cursor-pointer">
                    <h4 class="font-bold mb-2">造訪好友 (Visit Friends)</h4>
                    <p class="text-white/40 text-xs">前往好友家園參觀並留下點讚。</p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>