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
            <h2 class="text-4xl font-black uppercase tracking-tighter mb-4">成就 <span class="text-indigo-400">殿堂</span>
            </h2>
            <p class="text-white/40 text-xs uppercase tracking-widest">Achievements & Milestones</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <?php
            $achievements = [
                ['name' => '初次見面', 'desc' => '完成帳號註冊與綁定', 'icon' => '👋', 'points' => 500, 'unlocked' => true],
                ['name' => '幸運之子', 'desc' => '在幸運大抽獎中獲得二等獎以上', 'icon' => '🍀', 'points' => 2000, 'unlocked' => false],
                ['name' => '資深開發', 'desc' => '提交超過 3 次有效的 Bug 回報', 'icon' => '🛠️', 'points' => 5000, 'unlocked' => false],
                ['name' => '理財專家', 'desc' => '累計獲得超過 10,000 積分', 'icon' => '📈', 'points' => 1000, 'unlocked' => false],
            ];
            foreach ($achievements as $a):
                ?>
                <div
                    class="group flex items-center gap-6 p-8 bg-white/5 border border-white/10 rounded-3xl <?php echo $a['unlocked'] ? 'border-indigo-400/50 hover:bg-white/[0.08]' : 'opacity-50 grayscale'; ?> transition-all animate-fade-in-up">
                    <div
                        class="w-20 h-20 bg-white/5 rounded-2xl flex items-center justify-center text-4xl group-hover:scale-110 transition-transform">
                        <?php echo $a['icon']; ?>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-xl font-bold mb-1">
                            <?php echo $a['name']; ?>
                        </h4>
                        <p class="text-white/40 text-xs mb-3 italic">
                            <?php echo $a['desc']; ?>
                        </p>
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-black uppercase text-indigo-400 tracking-widest">+
                                <?php echo $a['points']; ?> PTS
                            </span>
                            <?php if ($a['unlocked']): ?>
                                <span class="text-[10px] font-black uppercase text-green-400 tracking-widest">已達成 ✅</span>
                            <?php else: ?>
                                <span class="text-[10px] font-black uppercase text-white/20 tracking-widest">未達成</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>