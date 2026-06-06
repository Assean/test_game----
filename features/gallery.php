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
        <div class="flex items-center justify-between mb-16 animate-fade-in-down">
            <div>
                <h2 class="text-4xl font-black uppercase tracking-tighter mb-2">影音 <span
                        class="text-secondary">藝廊</span></h2>
                <p class="text-white/40 text-xs tracking-[0.3em] uppercase">Official Media & Concept Art</p>
            </div>
            <div class="flex gap-4">
                <button class="text-xs font-black uppercase text-secondary">所有</button>
                <button class="text-xs font-black uppercase text-white/40">截圖</button>
                <button class="text-xs font-black uppercase text-white/40">影片</button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 animate-fade-in-up">
            <?php for ($i = 1; $i <= 6; $i++): ?>
                <div
                    class="group relative aspect-video bg-white/5 border border-white/10 rounded-3xl overflow-hidden cursor-pointer hover:border-secondary transition-all">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-bg-dark to-transparent opacity-0 group-hover:opacity-60 transition-opacity">
                    </div>
                    <div
                        class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="text-4xl">🔍</span>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>