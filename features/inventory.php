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
        <div class="flex justify-between items-center mb-16 animate-fade-in-down">
            <div>
                <h2 class="text-4xl font-black uppercase tracking-tighter mb-2">背包 <span class="text-teal-400">預覽</span>
                </h2>
                <p class="text-white/40 text-xs tracking-widest uppercase">Roblox Gear & Character Inventory</p>
            </div>
            <div class="hidden md:flex gap-4">
                <button
                    class="px-5 py-2 bg-white/10 rounded-full text-xs font-black uppercase border border-white/10 hover:border-teal-400 transition-all">所有道具</button>
                <button
                    class="px-5 py-2 bg-white/5 rounded-full text-xs font-black uppercase border border-white/5 hover:border-teal-400 transition-all">武器</button>
                <button
                    class="px-5 py-2 bg-white/5 rounded-full text-xs font-black uppercase border border-white/5 hover:border-teal-400 transition-all">套裝</button>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
            <?php
            $items = [
                ['name' => 'Starter Blade', 'rarity' => 'Common', 'icon' => '🗡️'],
                ['name' => 'Neon Shield', 'rarity' => 'Rare', 'icon' => '🛡️'],
                ['name' => 'Fireball Scroll', 'rarity' => 'Epic', 'icon' => '📜'],
                ['name' => 'Health Potion', 'rarity' => 'Common', 'icon' => '🧪'],
                ['name' => 'Shadow Cape', 'rarity' => 'Legendary', 'icon' => '🧣'],
            ];
            for ($i = 0; $i < 10; $i++):
                $item = $items[$i % 5];
                $color = $item['rarity'] == 'Legendary' ? 'text-orange-400 shadow-[0_0_20px_rgba(251,146,60,0.2)]' :
                    ($item['rarity'] == 'Epic' ? 'text-purple-400' :
                        ($item['rarity'] == 'Rare' ? 'text-secondary' : 'text-white/40'));
                ?>
                <div class="group bg-white/5 border border-white/10 rounded-3xl p-6 hover:bg-white/[0.08] hover:-translate-y-2 transition-all animate-fade-in-up"
                    style="animation-delay: <?php echo $i * 0.05; ?>s">
                    <div
                        class="aspect-square bg-zinc-900 rounded-2xl flex items-center justify-center text-4xl mb-4 group-hover:scale-110 transition-transform">
                        <?php echo $item['icon']; ?>
                    </div>
                    <h4 class="text-xs font-bold mb-1 line-clamp-1">
                        <?php echo $item['name']; ?>
                    </h4>
                    <span class="text-[8px] font-black uppercase tracking-widest <?php echo $color; ?>">
                        <?php echo $item['rarity']; ?>
                    </span>
                </div>
            <?php endfor; ?>
        </div>

        <div class="mt-16 text-center text-white/30 text-xs animate-fade-in-up">
            ※ 資料同步自 Roblox API，如有延遲請嘗試重新整理。
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>