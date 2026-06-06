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
        <div class="flex items-end justify-between mb-16 animate-fade-in-down">
            <div>
                <h2 class="text-4xl font-black uppercase tracking-tighter mb-2">PvP <span
                        class="text-accent">數據統計</span></h2>
                <p class="text-white/40 text-xs tracking-[0.3em] uppercase">Kill/Death Analytics & Ranking</p>
            </div>
            <div class="text-right">
                <span class="text-accent font-black text-2xl">K/D: 2.45</span>
                <p class="text-[10px] text-white/40 uppercase">全服排名: #420</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 animate-fade-in-up">
            <?php
            $stats = [
                ['label' => '擊殺數', 'value' => '1,250', 'icon' => '⚔️'],
                ['label' => '死亡數', 'value' => '510', 'icon' => '💀'],
                ['label' => '勝場率', 'value' => '68%', 'icon' => '🏆'],
                ['label' => '最高連殺', 'value' => '15', 'icon' => '🔥'],
            ];
            foreach ($stats as $stat):
                ?>
                <div class="bg-white/5 border border-white/10 rounded-3xl p-8 text-center">
                    <div class="text-2xl mb-4">
                        <?php echo $stat['icon']; ?>
                    </div>
                    <p class="text-[10px] text-white/40 uppercase font-black mb-1">
                        <?php echo $stat['label']; ?>
                    </p>
                    <h4 class="text-2xl font-black">
                        <?php echo $stat['value']; ?>
                    </h4>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>