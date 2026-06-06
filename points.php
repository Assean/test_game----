<?php
require_once __DIR__ . '/includes/auth_logic.php';
require_once __DIR__ . '/templates/header.php';

if (!Auth::isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT points FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user_points = $stmt->fetchColumn();
?>

<main class="pt-32 pb-24 px-[5%]">
    <div class="max-w-[1000px] mx-auto">
        <div
            class="bg-gradient-to-br from-primary/20 via-transparent to-secondary/10 border border-white/10 backdrop-blur-2xl rounded-[40px] p-12 relative overflow-hidden mb-12 animate-fade-in-down">
            <!-- Decorative Background Animation -->
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-secondary/20 blur-[100px] rounded-full animate-pulse">
            </div>

            <div class="relative z-10">
                <div class="flex justify-between items-start mb-12">
                    <div>
                        <h2 class="text-4xl font-black uppercase tracking-tighter mb-2">我的 <span
                                class="text-secondary">積分</span></h2>
                        <p class="text-white/40 text-sm tracking-widest uppercase">My Points Dashboard</p>
                    </div>
                    <div class="bg-white/10 px-4 py-2 rounded-2xl border border-white/10 text-xs font-bold">
                        ID:
                        <?php echo $_SESSION['user_id']; ?>
                    </div>
                </div>

                <div class="flex items-end gap-4 mb-10">
                    <span class="text-8xl font-black text-white leading-none tracking-tighter">
                        <?php echo number_format($user_points); ?>
                    </span>
                    <span class="text-2xl font-black text-secondary uppercase mb-2 tracking-widest">PTS</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white/5 p-6 rounded-3xl border border-white/5 hover:bg-white/10 transition-colors">
                        <p class="text-[10px] text-white/30 uppercase font-black mb-1">今日獲得</p>
                        <h4 class="text-2xl font-bold">+150</h4>
                    </div>
                    <div class="bg-white/5 p-6 rounded-3xl border border-white/5 hover:bg-white/10 transition-colors">
                        <p class="text-[10px] text-white/30 uppercase font-black mb-1">累計消耗</p>
                        <h4 class="text-2xl font-bold text-accent">0</h4>
                    </div>
                    <div class="bg-white/5 p-6 rounded-3xl border border-white/5 hover:bg-white/10 transition-colors">
                        <p class="text-[10px] text-white/30 uppercase font-black mb-1">積分等級</p>
                        <h4 class="text-2xl font-bold text-secondary">Bronze</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Point Rules & History -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2 space-y-8 animate-fade-in-up" style="animation-delay: 0.2s">
                <div class="bg-white/5 border border-white/10 rounded-3xl p-10">
                    <h3 class="text-xl font-bold mb-8 flex items-center gap-3">
                        <span class="text-secondary">✦</span> 積分獲取記錄
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-4 bg-white/5 rounded-2xl border border-white/5">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 bg-green-500/10 text-green-400 rounded-full flex items-center justify-center text-lg">
                                    ➕</div>
                                <div>
                                    <p class="font-bold text-sm">每日簽到獎勵</p>
                                    <p class="text-[10px] text-white/30">2026-03-09 10:00</p>
                                </div>
                            </div>
                            <span class="text-green-400 font-black">+100</span>
                        </div>
                        <div class="flex justify-between items-center p-4 bg-white/5 rounded-2xl border border-white/5">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 bg-blue-500/10 text-blue-400 rounded-full flex items-center justify-center text-lg">
                                    🔗</div>
                                <div>
                                    <p class="font-bold text-sm">Roblox 帳號綁定</p>
                                    <p class="text-[10px] text-white/30">2026-03-09 09:30</p>
                                </div>
                            </div>
                            <span class="text-blue-400 font-black">+500</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-8 animate-fade-in-up" style="animation-delay: 0.4s">
                <div class="bg-gradient-to-br from-accent/20 to-transparent border border-white/10 rounded-3xl p-8">
                    <h3 class="text-xl font-bold mb-6">積分用途</h3>
                    <ul class="space-y-4 text-sm text-white/60">
                        <li class="flex items-center gap-2">
                            <span class="text-accent">✔</span> 兌換遊戲虛擬幣
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-accent">✔</span> 參加幸運大抽獎
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-accent">✔</span> 獲取 Discord 特殊稱號
                        </li>
                    </ul>
                    <button
                        class="w-full mt-8 btn-neon py-4 bg-accent shadow-[0_0_20px_rgba(255,0,127,0.3)] text-xs">前往兌換商城</button>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'templates/footer.php'; ?>