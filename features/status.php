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
        <div class="flex items-center gap-6 mb-16 animate-fade-in-down">
            <div class="w-16 h-16 bg-green-500/20 rounded-2xl flex items-center justify-center text-3xl">⚡</div>
            <div>
                <h2 class="text-4xl font-black uppercase tracking-tighter mb-1">伺服器 <span
                        class="text-green-400">狀態</span></h2>
                <p class="text-white/40 text-xs tracking-widest uppercase">Global Server Health Monitor</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 animate-fade-in-up">
            <?php
            $regions = [
                ['name' => '北美東部 (Virginia)', 'status' => 'Online', 'ping' => '24ms'],
                ['name' => '亞洲東部 (Tokyo)', 'status' => 'Online', 'ping' => '45ms'],
                ['name' => '歐洲西部 (Frankfurt)', 'status' => 'Maintenance', 'ping' => '---'],
                ['name' => '東南亞 (Singapore)', 'status' => 'Online', 'ping' => '32ms'],
            ];
            foreach ($regions as $r):
                $isOnline = $r['status'] === 'Online';
                ?>
                <div
                    class="flex items-center justify-between p-8 bg-white/5 border border-white/10 rounded-3xl hover:bg-white/[0.08] transition-all">
                    <div class="flex items-center gap-6">
                        <div
                            class="w-3 h-3 rounded-full <?php echo $isOnline ? 'bg-green-400 shadow-[0_0_10px_rgba(74,222,128,0.5)]' : 'bg-red-500'; ?> animate-pulse">
                        </div>
                        <div>
                            <h4 class="font-bold text-lg">
                                <?php echo $r['name']; ?>
                            </h4>
                            <p class="text-[10px] text-white/30 uppercase font-black uppercase tracking-tighter">Region ID:
                                <?php echo strtolower(explode('(', $r['name'])[0]); ?>
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span
                            class="block font-black uppercase tracking-widest text-sm <?php echo $isOnline ? 'text-green-400' : 'text-red-500'; ?>">
                            <?php echo $r['status']; ?>
                        </span>
                        <span class="text-xs text-white/40">Latency:
                            <?php echo $r['ping']; ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-12 p-8 bg-white/5 border border-white/10 border-dashed rounded-3xl animate-fade-in-up"
            style="animation-delay: 0.3s">
            <h5 class="font-black text-xs uppercase mb-4 text-white/60">ℹ️ 最近維護公告</h5>
            <p class="text-sm text-white/40 leading-relaxed">
                歐洲伺服器目前正進行 V1.52 緊急補丁更新，預計將於 2026-03-09 22:00 (UTC+8) 恢復運行。其餘地區目前運作正常，祝您遊戲愉快。
            </p>
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>