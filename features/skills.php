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
        <div class="flex items-center justify-between mb-16 animate-fade-in-down">
            <div>
                <h2 class="text-4xl font-black uppercase tracking-tighter mb-2">技能 <span
                        class="text-secondary">模擬器</span></h2>
                <p class="text-white/40 text-xs tracking-[0.3em] uppercase">Build Strategy & Skill Tree</p>
            </div>
            <div class="bg-white/5 border border-white/10 px-6 py-3 rounded-full">
                <span class="text-secondary font-black text-sm">可用點數: 50</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 animate-fade-in-up">
            <?php
            $trees = [
                ['name' => '攻擊系', 'color' => 'red-500', 'desc' => '極大化輸出與暴擊能力。'],
                ['name' => '防禦系', 'color' => 'blue-500', 'desc' => '提升生存率與減傷屬性。'],
                ['name' => '輔助系', 'color' => 'green-500', 'desc' => '群體 Buff 與治療效果。'],
            ];
            foreach ($trees as $tree):
                ?>
                <div
                    class="bg-white/5 border border-white/10 rounded-[40px] p-8 hover:border-<?php echo $tree['color']; ?> transition-all text-center">
                    <h3 class="text-2xl font-bold mb-4">
                        <?php echo $tree['name']; ?>
                    </h3>
                    <p class="text-white/40 text-sm mb-8">
                        <?php echo $tree['desc']; ?>
                    </p>
                    <div class="flex justify-center gap-2 mb-8">
                        <div class="w-10 h-10 bg-white/10 rounded-lg"></div>
                        <div class="w-10 h-10 bg-white/10 rounded-lg"></div>
                        <div class="w-10 h-10 bg-white/10 rounded-lg"></div>
                    </div>
                    <button
                        class="w-full py-3 bg-white/10 rounded-2xl font-bold hover:bg-<?php echo $tree['color']; ?> transition-all">進入配點</button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>