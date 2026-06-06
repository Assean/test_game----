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
                <h2 class="text-4xl font-black uppercase tracking-tighter mb-2">每日 <span
                        class="text-primary">冒險任務</span></h2>
                <p class="text-white/40 text-xs tracking-[0.3em] uppercase">Daily Quests & Challenges</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-white/40 uppercase mb-1">任務刷新倒數</p>
                <span class="text-primary font-mono font-black text-xl">14:22:05</span>
            </div>
        </div>

        <div class="space-y-6 animate-fade-in-up">
            <?php
            $quests = [
                ['title' => '戰場初試', 'desc' => '在競技場中取得 3 場勝利。', 'reward' => '500 Pts', 'progress' => '2/3'],
                ['title' => '資源採集', 'desc' => '收集 50 個鐵礦石。', 'reward' => '300 Pts', 'progress' => '50/50', 'done' => true],
                ['title' => '社交達人', 'desc' => '在交流討論板發表 1 則貼文。', 'reward' => '200 Pts', 'progress' => '0/1'],
            ];
            foreach ($quests as $q):
                ?>
                <div
                    class="group flex items-center gap-8 p-8 bg-white/5 border border-white/10 rounded-[30px] hover:bg-white/10 transition-all">
                    <div class="w-16 h-16 bg-primary/20 rounded-2xl flex items-center justify-center text-3xl">
                        <?php echo isset($q['done']) ? '✅' : '⚔️'; ?>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-xl font-bold mb-1">
                            <?php echo $q['title']; ?>
                        </h4>
                        <p class="text-white/40 text-sm">
                            <?php echo $q['desc']; ?>
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-secondary font-black mb-1">
                            <?php echo $q['reward']; ?>
                        </p>
                        <p class="text-[10px] text-white/20 uppercase font-black">
                            <?php echo $q['progress']; ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>