<?php
require_once __DIR__ . '/../includes/auth_logic.php';
include '../templates/header.php';

if (!Auth::isLoggedIn()) {
    header("Location: ../login.php");
    exit;
}
?>

<main class="pt-32 pb-24 px-[5%]">
    <div class="max-w-[800px] mx-auto">
        <div class="mb-16 animate-fade-in-down">
            <h2 class="text-4xl font-black uppercase tracking-tighter mb-2">開發 <span class="text-primary">日誌</span></h2>
            <p class="text-white/40 text-xs tracking-[0.3em] uppercase">Behind the Scenes & Updates</p>
        </div>

        <div class="space-y-12 animate-fade-in-up">
            <?php
            require_once __DIR__ . '/../includes/dev_logs.php';
            $logs = getDevLogs();
            foreach ($logs as $log): ?>
                <article class="relative pl-12 border-l-2 border-white/10 pb-4">
                    <div
                        class="absolute left-[-9px] top-0 w-4 h-4 rounded-full <?php echo $log['status'] === 'new' ? 'bg-primary shadow-[0_0_10px_#8a2be2]' : 'bg-white/20'; ?>">
                    </div>
                    <span
                        class="text-[10px] font-black text-white/30 uppercase tracking-widest block mb-2"><?php echo $log['date']; ?></span>
                    <h3 class="text-2xl font-bold mb-4"><?php echo $log['title']; ?> <span
                            class="ml-2 text-[10px] px-2 py-0.5 bg-white/5 rounded-full text-white/30"><?php echo $log['tag']; ?></span>
                    </h3>
                    <p class="text-white/60 leading-relaxed mb-6"><?php echo $log['content']; ?></p>
                    <a href="#" class="text-primary font-bold text-xs uppercase hover:underline">閱讀全文 →</a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>