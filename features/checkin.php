<?php
require_once __DIR__ . '/../includes/auth_logic.php';
require_once __DIR__ . '/../templates/header.php';

if (!Auth::isLoggedIn()) {
    header("Location: ../login.php");
    exit;
}

// 檢查今日是否已簽到
$today = date('Y-m-d');
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id, streak FROM checkins WHERE user_id = ? AND checkin_date = ?");
$stmt->execute([$user_id, $today]);
$checkinData = $stmt->fetch();
$hasCheckedIn = (bool)$checkinData;

// 取得最近一次簽到的連續天數 (不論是否為今天)
$stmt = $pdo->prepare("SELECT streak FROM checkins WHERE user_id = ? ORDER BY checkin_date DESC LIMIT 1");
$stmt->execute([$user_id]);
$lastCheckin = $stmt->fetch();
$currentStreak = $lastCheckin ? (int)$lastCheckin['streak'] : 0;

// 計算下一個里程碑
$milestones = [7, 14, 30];
$nextMilestone = 7;
foreach ($milestones as $m) {
    if ($currentStreak < $m) {
        $nextMilestone = $m;
        break;
    }
}
$progress = min(100, ($currentStreak / $nextMilestone) * 100);
?>

<main class="pt-32 pb-24 px-[5%]">
    <div
        class="max-w-[700px] mx-auto bg-white/5 border border-white/10 backdrop-blur-2xl p-12 rounded-[40px] text-center animate-fade-in-up">
        <div
            class="w-24 h-24 bg-primary/20 rounded-full flex items-center justify-center text-4xl mx-auto mb-8 animate-bounce">
            📅</div>
        <h2 class="text-4xl font-black mb-4 uppercase">每日 <span class="text-secondary">簽到</span></h2>
        
        <!-- Streak Info -->
        <div class="mb-10">
            <div class="inline-flex items-center gap-2 px-6 py-2 bg-secondary/10 border border-secondary/20 rounded-full text-secondary font-black text-xs uppercase tracking-widest mb-4">
                <span class="text-lg">🔥</span> 目前連續簽到：<?php echo $currentStreak; ?> 天
            </div>
            
            <!-- Progress Bar -->
            <div class="max-w-md mx-auto mt-4">
                <div class="flex justify-between text-[10px] font-black uppercase text-white/40 mb-2">
                    <span>簽到進度</span>
                    <span>下一獎勵：<?php echo $nextMilestone; ?> 天</span>
                </div>
                <div class="w-full h-3 bg-white/5 rounded-full overflow-hidden border border-white/10">
                    <div id="streak-progress" class="h-full bg-gradient-to-r from-primary to-secondary transition-all duration-1000" style="width: <?php echo $progress; ?>%"></div>
                </div>
                <?php if ($nextMilestone === 7): ?>
                    <p class="text-[9px] text-white/30 mt-2 italic">* 達成 7 天可獲取額外 500 積分</p>
                <?php elseif ($nextMilestone === 14): ?>
                    <p class="text-[9px] text-white/30 mt-2 italic">* 達成 14 天可獲取額外 1000 積分</p>
                <?php else: ?>
                    <p class="text-[9px] text-white/30 mt-2 italic">* 持續挑戰 30 天傳奇獎勵！</p>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($hasCheckedIn): ?>
            <div class="p-8 bg-green-500/10 border border-green-500/20 rounded-3xl">
                <p class="text-green-400 font-bold text-lg italic">✨ 您今天已經簽到過了！</p>
                <p class="text-white/30 text-xs mt-2 uppercase tracking-widest">目前累計簽到：<?php echo $currentStreak; ?> 天</p>
            </div>
        <?php else: ?>
            <form id="checkin-form">
                <input type="hidden" id="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
                <button type="submit" class="btn-neon py-6 px-16 text-lg tracking-widest">
                    立即簽到領取獎勵
                </button>
            </form>
        <?php endif; ?>

        <div id="checkin-result" class="mt-8 hidden animate-fade-in-up">
            <div id="reward-amount" class="text-secondary text-3xl font-black">+100 PTS!</div>
            <p id="reward-streak-info" class="text-white font-bold text-sm mt-2"></p>
            <p id="reward-milestone" class="text-accent text-xs font-bold mt-2 animate-pulse"></p>
            <p class="text-white/40 text-[10px] mt-4 uppercase">積分已存入您的帳戶</p>
        </div>
    </div>
</main>

<script>
    document.getElementById('checkin-form')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = e.target.querySelector('button');
        const csrf = document.getElementById('csrf_token').value;
        btn.disabled = true;
        btn.innerText = "處理中...";

        try {
            const formData = new FormData();
            formData.append('csrf_token', csrf);

            const response = await fetch('../auth/checkin_handler.php', {
                method: 'POST',
                body: formData
            });
            const res = await response.json();
            if (res.success) {
                e.target.classList.add('hidden');
                
                // 更新 UI 數據
                document.getElementById('reward-amount').textContent = `+${res.points} PTS!`;
                document.getElementById('reward-streak-info').textContent = `連續簽到第 ${res.streak} 天達成！`;
                if (res.message) {
                    document.getElementById('reward-milestone').textContent = res.message;
                }
                
                // 更新進度條 (簡單處理)
                const nextM = <?php echo $nextMilestone; ?>;
                const newProgress = Math.min(100, (res.streak / nextM) * 100);
                document.getElementById('streak-progress').style.width = newProgress + '%';

                document.getElementById('checkin-result').classList.remove('hidden');
                Utils.showToast('簽到成功！', 'success');
            } else {
                Utils.showToast(res.message, 'error');
                btn.disabled = false;
                btn.innerText = "立即簽到領取獎勵";
            }
        } catch (err) {
            Utils.showToast("網路錯誤", 'error');
            btn.disabled = false;
            btn.innerText = "立即簽到領取獎勵";
        }
    });
</script>

<?php include '../templates/footer.php'; ?>