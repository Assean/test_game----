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
        <div class="mb-16 animate-fade-in-down">
            <h2 class="text-4xl font-black uppercase tracking-tighter mb-2">極致 <span class="text-accent">拍賣行</span></h2>
            <p class="text-white/40 text-xs tracking-[0.3em] uppercase">High-Stakes Live Auctions</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12 animate-fade-in-up">
            <!-- Auction Card 1 -->
            <div class="bg-white/5 border border-white/10 rounded-[30px] p-8 relative overflow-hidden group">
                <div
                    class="absolute top-4 right-4 bg-red-500 text-[10px] font-black px-3 py-1 rounded-full animate-pulse uppercase">
                    LIVE</div>
                <div class="text-6xl mb-6 group-hover:scale-110 transition-transform">⚔️</div>
                <h3 class="text-xl font-bold mb-2">Alpha Blade (001)</h3>
                <p class="text-white/40 text-sm mb-6">全服首把開發者限定武器</p>
                <div class="border-t border-white/10 pt-6">
                    <p class="text-[10px] text-white/40 uppercase mb-1">目前出價 (Current Bid)</p>
                    <div class="text-3xl font-black text-secondary">50,000 <span class="text-xs uppercase">Pts</span>
                    </div>
                </div>
                <button onclick="comingSoon('極致拍賣行 - 競標')"
                    class="w-full mt-8 py-4 bg-white text-bg-dark font-black uppercase text-xs rounded-2xl hover:bg-accent hover:text-white transition-all">參與競標</button>
            </div>

            <!-- Repeat cards as needed... -->
        </div>
    </div>
</main>

<script>
function comingSoon(featureName) {
    alert('【' + featureName + '】功能正在開發中，敬請期待！');
}
</script>

<?php include '../templates/footer.php'; ?>