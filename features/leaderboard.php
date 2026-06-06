<?php
require_once __DIR__ . '/../includes/auth_logic.php';
require_once __DIR__ . '/../templates/header.php';

// Fetch top 10 users by points
$stmt = $pdo->query("SELECT username, points, roblox_id FROM users ORDER BY points DESC LIMIT 10");
$top_users = $stmt->fetchAll();
?>

<main class="pt-32 pb-24 px-[5%]">
    <div class="max-w-[800px] mx-auto">
        <div class="text-center mb-16 animate-fade-in-down">
            <h2 class="text-4xl font-black uppercase tracking-tighter mb-4">榮譽 <span class="text-secondary">排行榜</span></h2>
            <p class="text-white/40 text-xs uppercase tracking-widest">Global Points Leaderboard</p>
        </div>

        <div class="bg-white/5 border border-white/10 rounded-[40px] overflow-hidden backdrop-blur-2xl animate-fade-in-up">
            <table class="w-full text-left">
                <thead class="bg-white/5">
                    <tr>
                        <th class="px-8 py-6 text-[10px] font-black uppercase text-white/40">排名</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase text-white/40">玩家</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase text-white/40 text-right">總積分</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php if ($top_users): foreach ($top_users as $i => $u): ?>
                    <tr class="hover:bg-white/[0.02] transition-colors group">
                        <td class="px-8 py-6">
                            <?php if ($i < 3): ?>
                                <span class="w-8 h-8 rounded-full flex items-center justify-center font-black text-sm
                                <?php echo $i == 0 ? 'bg-yellow-400 text-black shadow-[0_0_15px_rgba(250,204,21,0.5)]' : ($i == 1 ? 'bg-zinc-300 text-black' : 'bg-orange-400 text-black'); ?>">
                                    <?php echo $i + 1; ?>
                                </span>
                            <?php else: ?>
                                <span class="text-white/40 font-bold ml-2">#<?php echo $i + 1; ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-white/5 rounded-full flex items-center justify-center text-lg">👤</div>
                                <div>
                                    <p class="font-bold group-hover:text-secondary transition-colors"><?php echo htmlspecialchars($u['username']); ?></p>
                                    <p class="text-[10px] text-white/20 uppercase font-mono tracking-tighter">Roblox: <?php echo htmlspecialchars($u['roblox_id']); ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <span class="text-xl font-black <?php echo $i == 0 ? 'text-secondary' : 'text-white'; ?>">
                                <?php echo number_format($u['points']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="3" class="px-8 py-10 text-center text-white/20">暫位有任何玩家數據</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>
