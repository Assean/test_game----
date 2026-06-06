<?php
require_once __DIR__ . '/../includes/auth_logic.php';
include '../templates/header.php';

if (!Auth::isLoggedIn()) {
    header("Location: ../login.php");
    exit;
}
?>

<main class="pt-32 pb-24 px-[5%]">
    <div class="max-w-[800px] mx-auto text-center">
        <div class="mb-16 animate-fade-in-down">
            <h2 class="text-4xl font-black uppercase tracking-tighter mb-2">首領 <span class="text-accent">計時器</span></h2>
            <p class="text-white/40 text-xs tracking-[0.3em] uppercase">World Boss Respawn Schedule</p>
        </div>

        <div class="space-y-6 animate-fade-in-up">
            <?php
            $bosses = [
                ['name' => 'Ancient Dragon', 'time' => '02:45:12', 'status' => 'Waiting'],
                ['name' => 'Void Reaper', 'time' => 'SPAWNED', 'status' => 'Alive'],
                ['name' => 'Frozen Giant', 'time' => '05:12:30', 'status' => 'Waiting'],
            ];
            foreach ($bosses as $boss):
                ?>
                <div
                    class="flex items-center justify-between p-8 bg-white/5 border border-white/10 rounded-[30px] group hover:bg-white/10 transition-all">
                    <div class="text-left">
                        <h4 class="text-xl font-black uppercase">
                            <?php echo $boss['name']; ?>
                        </h4>
                        <p
                            class="text-[10px] uppercase font-black <?php echo $boss['status'] === 'Alive' ? 'text-green-400' : 'text-white/20'; ?>">
                            ●
                            <?php echo $boss['status']; ?>
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-white/40 uppercase mb-1">重置倒數</p>
                        <div
                            class="text-2xl font-mono font-black <?php echo $boss['status'] === 'Alive' ? 'text-red-500 animate-pulse' : 'text-secondary'; ?>">
                            <?php echo $boss['time']; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>