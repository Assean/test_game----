<?php
require_once __DIR__ . '/../includes/auth_logic.php';
include '../templates/header.php';

if (!Auth::isLoggedIn()) {
    header("Location: ../login.php");
    exit;
}
?>

<main class="pt-32 pb-24 px-[5%]">
    <div class="max-w-[700px] mx-auto">
        <div class="mb-16 animate-fade-in-down">
            <h2 class="text-4xl font-black uppercase tracking-tighter mb-2">社群 <span class="text-secondary">投票</span>
            </h2>
            <p class="text-white/40 text-xs tracking-[0.3em] uppercase">Decide the Future of the Game</p>
        </div>

        <div class="bg-white/5 border border-white/10 rounded-[40px] p-10 animate-fade-in-up">
            <h3 class="text-xl font-bold mb-8">下個版本您最期待的內容？</h3>
            <div class="space-y-6">
                <div class="group cursor-pointer">
                    <div class="flex justify-between text-sm font-bold mb-2">
                        <span>新增冒險地圖</span>
                        <span class="text-secondary">45%</span>
                    </div>
                    <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden">
                        <div class="h-full bg-secondary w-[45%]"></div>
                    </div>
                </div>
                <div class="group cursor-pointer">
                    <div class="flex justify-between text-sm font-bold mb-2">
                        <span>武器平衡調整</span>
                        <span class="text-white/40">30%</span>
                    </div>
                    <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden">
                        <div class="h-full bg-white/20 w-[30%]"></div>
                    </div>
                </div>
                <button class="w-full mt-8 btn-neon py-4">提交我的投票</button>
            </div>
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>