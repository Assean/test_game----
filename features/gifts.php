<?php
require_once __DIR__ . '/../includes/auth_logic.php';
include '../templates/header.php';

if (!Auth::isLoggedIn()) {
    header("Location: ../login.php");
    exit;
}
?>

<main class="pt-32 pb-24 px-[5%]">
    <div class="max-w-[800px] mx-auto text-center">
        <div class="mb-16 animate-fade-in-down">
            <h2 class="text-4xl font-black uppercase tracking-tighter mb-2">禮物 <span class="text-accent">中心</span></h2>
            <p class="text-white/40 text-xs tracking-[0.3em] uppercase">Send Gems & Skins to Friends</p>
        </div>

        <div class="bg-white/5 border border-white/10 rounded-[50px] p-12 backdrop-blur-3xl animate-fade-in-up">
            <div class="max-w-[400px] mx-auto space-y-8">
                <div class="text-6xl mb-8">🎁</div>
                <div>
                    <label class="block text-[10px] font-black text-white/40 uppercase mb-3 text-left">好友 Roblox ID /
                        用戶名</label>
                    <input type="text"
                        class="w-full bg-white/10 border border-white/10 rounded-2xl px-6 py-4 outline-none focus:border-accent transition-all"
                        placeholder="輸入接收者資訊...">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-white/40 uppercase mb-3 text-left">送禮內容</label>
                    <select
                        class="w-full bg-white/10 border border-white/10 rounded-2xl px-6 py-4 outline-none focus:border-accent appearance-none cursor-pointer">
                        <option>100 積分禮包</option>
                        <option>專屬稱號: 慷慨者</option>
                        <option>隨機稀有皮膚箱</option>
                    </select>
                </div>
                <button
                    class="w-full btn-neon py-5 bg-gradient-to-r from-accent to-pink-600 border-none">確認送出禮物</button>
            </div>
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>