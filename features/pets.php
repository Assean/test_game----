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
        <div class="flex items-end justify-between mb-16 animate-fade-in-down">
            <div>
                <h2 class="text-4xl font-black uppercase tracking-tighter mb-2">寵物 <span
                        class="text-secondary">藝廊</span></h2>
                <p class="text-white/40 text-xs tracking-[0.3em] uppercase">Companion & Pet Encyclopedia</p>
            </div>
            <div class="flex gap-4">
                <button onclick="comingSoon('寵物系統 - 篩選：神聖')"
                    class="px-6 py-2 bg-white/5 border border-white/10 rounded-full text-[10px] font-black uppercase">神聖等級</button>
                <button onclick="comingSoon('寵物系統 - 篩選：史詩')"
                    class="px-6 py-2 bg-white/5 border border-white/10 rounded-full text-[10px] font-black uppercase">史詩等級</button>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6 animate-fade-in-up">
            <?php
            $pets = [
                ['name' => 'Solar Dragon', 'rarity' => 'Divine', 'icon' => '🐉'],
                ['name' => 'Shadow Wolf', 'rarity' => 'Epic', 'icon' => '🐺'],
                ['name' => 'Cyber Cat', 'rarity' => 'Rare', 'icon' => '🐱'],
                ['name' => 'Frost Phoenix', 'rarity' => 'Divine', 'icon' => '🦅'],
                ['name' => 'Earth Golem', 'rarity' => 'Common', 'icon' => '🗿'],
                ['name' => 'Void Bat', 'rarity' => 'Epic', 'icon' => '🦇'],
            ];
            foreach ($pets as $pet):
                ?>
                <div
                    class="bg-white/5 border border-white/10 rounded-3xl p-6 text-center group hover:border-secondary transition-all">
                    <div class="text-5xl mb-4 group-hover:scale-125 transition-transform duration-500">
                        <?php echo $pet['icon']; ?>
                    </div>
                    <h4 class="text-sm font-bold truncate">
                        <?php echo $pet['name']; ?>
                    </h4>
                    <p
                        class="text-[8px] font-black uppercase mt-1 <?php echo $pet['rarity'] === 'Divine' ? 'text-yellow-400' : 'text-white/20'; ?>">
                        <?php echo $pet['rarity']; ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<script>
function comingSoon(featureName) {
    alert('【' + featureName + '】功能正在開發中，敬請期待！');
}
</script>

<?php include '../templates/footer.php'; ?>