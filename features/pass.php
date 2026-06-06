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
            <h2 class="text-4xl font-black uppercase tracking-tighter mb-2">戰鬥 <span class="text-yellow-400">通行證</span>
            </h2>
            <p class="text-white/40 text-xs tracking-[0.3em] uppercase">Season 01: The Awakening</p>
        </div>

        <div class="grid grid-cols-1 gap-4 animate-fade-in-up">
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <div
                    class="group flex items-center gap-8 p-6 bg-white/5 border border-white/10 rounded-2xl hover:bg-white/10 transition-all cursor-pointer">
                    <div
                        class="w-16 h-16 flex items-center justify-center font-black text-2xl text-white/20 group-hover:text-yellow-400">
                        <?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold">限定獎勵: Level
                            <?php echo $i; ?>
                        </h4>
                        <p class="text-[10px] text-white/40 uppercase">獎項:
                            <?php echo $i % 2 == 0 ? 'Neon Skin' : '500 Pts'; ?>
                        </p>
                    </div>
                    <div class="<?php echo $i <= 2 ? 'text-green-400' : 'text-white/10'; ?>">
                        <?php echo $i <= 2 ? '已領取' : '未解鎖'; ?>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>