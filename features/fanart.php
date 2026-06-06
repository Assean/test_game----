<?php
require_once __DIR__ . '/../includes/auth_logic.php';
include '../templates/header.php';

if (!Auth::isLoggedIn()) {
    header("Location: ../login.php");
    exit;
}
?>

<main class="pt-32 pb-24 px-[5%]">
    <div class="max-w-[1000px] mx-auto text-center">
        <div class="mb-16 animate-fade-in-down">
            <h2 class="text-4xl font-black uppercase tracking-tighter mb-2">同人 <span class="text-pink-400">投稿專區</span>
            </h2>
            <p class="text-white/40 text-xs tracking-[0.3em] uppercase">Fan Art & Community Creations</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-16 animate-fade-in-up">
            <div
                class="bg-white/5 border border-white/10 rounded-[40px] p-12 flex flex-col items-center justify-center border-dashed group hover:border-pink-400 transition-all cursor-pointer">
                <div class="text-6xl mb-6 group-hover:bounce transition-transform">🎨</div>
                <h4 class="text-xl font-bold mb-2">上傳您的創作</h4>
                <p class="text-white/40 text-sm">接受 PNG, JPG, GIF (Max 10MB)</p>
            </div>
            <div class="bg-pink-400/10 border border-pink-400/20 rounded-[40px] p-12 text-left">
                <h4 class="text-xl font-bold mb-4">本月主題: <span class="text-pink-400">極光之下</span></h4>
                <p class="text-white/40 text-sm leading-relaxed mb-6">獲勝者將獲得「大藝術家」Discord 稱號與 5,000 點積分獎勵。</p>
                <button
                    class="w-full py-4 bg-pink-500 text-white rounded-2xl font-black uppercase tracking-widest text-xs">立即參賽</button>
            </div>
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>