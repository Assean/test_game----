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
            <h2 class="text-4xl font-black uppercase tracking-tighter mb-2">製作 <span class="text-secondary">手冊</span>
            </h2>
            <p class="text-white/40 text-xs tracking-[0.3em] uppercase">Alchemy & Blacksmithing Recipes</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 animate-fade-in-up">
            <div class="bg-white/5 border border-white/10 rounded-[40px] p-10">
                <h3 class="text-xl font-bold mb-8">配方搜尋 (Recipe Search)</h3>
                <input type="text"
                    class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 outline-none focus:border-secondary transition-all"
                    placeholder="輸入材料或名稱...">
                <div class="mt-8 space-y-4">
                    <div class="p-4 bg-white/5 rounded-2xl border border-white/5 flex items-center gap-4">
                        <span class="text-2xl">🧪</span>
                        <div>
                            <p class="font-bold">超級回血藥劑</p>
                            <p class="text-[10px] text-white/30 uppercase">材料: 紅草 x5, 藍草 x2</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col justify-center">
                <div class="text-6xl mb-8">⚒️</div>
                <h4 class="text-3xl font-black mb-4">掌握工藝，<br>鍛造未來。</h4>
                <p class="text-white/40 leading-relaxed">收集稀有素材以製作傳說級裝備，部分配方需解鎖特定成就方可查看。</p>
            </div>
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>