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
        <div class="text-center mb-16 animate-fade-in-down">
            <h2 class="text-5xl font-black uppercase tracking-tighter mb-4">公會 <span class="text-primary">大廳</span></h2>
            <p class="text-white/40 uppercase tracking-[0.3em] text-sm">Alliance & Guild Management</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 animate-fade-in-up">
            <div
                class="bg-white/5 border border-white/10 rounded-[40px] p-10 flex flex-col items-center justify-center text-center">
                <div class="w-24 h-24 bg-primary/20 rounded-full flex items-center justify-center text-4xl mb-6">🛡️
                </div>
                <h3 class="text-2xl font-bold mb-4">加入公會</h3>
                <p class="text-white/40 text-sm mb-8">與志同道合的戰友同行，獲取公會專屬 Buff 與資源。</p>
                <button onclick="comingSoon('公會大廳 - 公會列表')"
                    class="w-full py-4 border border-white/10 rounded-2xl hover:bg-primary/20 transition-all font-bold">查看公會列表</button>
            </div>
            <div
                class="bg-white/5 border border-white/10 rounded-[40px] p-10 flex flex-col items-center justify-center text-center">
                <div class="w-24 h-24 bg-secondary/20 rounded-full flex items-center justify-center text-4xl mb-6">🚩
                </div>
                <h3 class="text-2xl font-bold mb-4">建立公會</h3>
                <p class="text-white/40 text-sm mb-8">需要等級 50+ 且消耗 10,000 積分來建立您的傳奇。</p>
                <button onclick="comingSoon('公會大廳 - 建立公會')"
                    class="w-full py-4 bg-gradient-to-r from-primary to-secondary text-white rounded-2xl hover:scale-105 transition-all font-bold">立即建立</button>
            </div>
        </div>
    </div>
</main>

<script>
function comingSoon(featureName) {
    alert('【' + featureName + '】功能正在開發中，敬請期待！');
}
</script>

<?php include '../templates/footer.php'; ?>