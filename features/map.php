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
            <h2 class="text-4xl font-black uppercase tracking-tighter mb-2">世界 <span class="text-secondary">全圖</span>
            </h2>
            <p class="text-white/40 text-xs tracking-[0.3em] uppercase">Interactive Realm Navigation</p>
        </div>

        <div
            class="aspect-video bg-white/5 border border-white/10 rounded-[50px] overflow-hidden relative group animate-fade-in-up">
            <div class="absolute inset-0 bg-cover bg-center opacity-30 grayscale group-hover:grayscale-0 transition-all duration-1000"
                style="background-image: url('https://placeholder.com/map');"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center">
                    <div class="text-6xl mb-6">🗺️</div>
                    <h3 class="text-2xl font-black mb-4">地圖數據加載中</h3>
                    <p class="text-white/40 max-w-md">探索 5 個主要行省與超過 100 個隱藏區域。</p>
                </div>
            </div>

            <!-- Map Markers -->
            <div class="absolute top-1/4 left-1/3 w-8 h-8 bg-red-500 rounded-full animate-ping"></div>
            <div class="absolute bottom-1/3 right-1/4 w-8 h-8 bg-secondary rounded-full animate-ping"></div>
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>