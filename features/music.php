<?php
require_once __DIR__ . '/../includes/auth_logic.php';
include '../templates/header.php';

if (!Auth::isLoggedIn()) {
    header("Location: ../login.php");
    exit;
}
?>

<main class="pt-32 pb-24 px-[5%]">
    <div class="max-w-[900px] mx-auto">
        <div class="flex items-center justify-between mb-16 animate-fade-in-down">
            <div>
                <h2 class="text-4xl font-black uppercase tracking-tighter mb-2">音樂 <span class="text-primary">空間</span>
                </h2>
                <p class="text-white/40 text-xs tracking-[0.3em] uppercase">Original $TEST Soundtrack</p>
            </div>
            <div
                class="w-16 h-16 bg-primary/20 rounded-full flex items-center justify-center text-3xl animate-spin-slow">
                🎵</div>
        </div>

        <div class="bg-white/5 border border-white/10 rounded-[50px] p-12 backdrop-blur-3xl animate-fade-in-up">
            <div class="flex flex-col md:flex-row gap-12 items-center">
                <div
                    class="w-48 h-48 bg-gradient-to-br from-primary to-secondary rounded-3xl shadow-2xl flex items-center justify-center text-7xl">
                    💿</div>
                <div class="flex-1 text-center md:text-left">
                    <h3 class="text-3xl font-black mb-2">Battle of Neon</h3>
                    <p class="text-white/40 mb-8 uppercase tracking-widest text-xs">Official Theme - Track 01</p>
                    <div class="flex items-center justify-center md:justify-start gap-8">
                        <button class="text-4xl hover:scale-110 transition-transform">⏮</button>
                        <button
                            class="w-20 h-20 bg-white text-bg-dark rounded-full text-3xl flex items-center justify-center hover:scale-105 transition-transform">▶</button>
                        <button class="text-4xl hover:scale-110 transition-transform">⏭</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>