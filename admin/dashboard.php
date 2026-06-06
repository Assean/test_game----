<?php
require_once __DIR__ . '/../includes/auth_logic.php';

// 權限檢查：只有管理員能進入
if (!Auth::isAdmin()) {
    header("Location: ../login.php");
    exit;
}

// 獲取統計資料
// 獲取數據統計
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$verified_users = $pdo->query("SELECT COUNT(*) FROM users WHERE email_verified = 1")->fetchColumn();
$roblox_binds = $pdo->query("SELECT COUNT(*) FROM users WHERE roblox_id IS NOT NULL")->fetchColumn();

// 1. 用戶增長 (最近 7 天)
$user_growth = $pdo->query("
    SELECT DATE(created_at) as date, COUNT(*) as count 
    FROM users 
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(created_at)
    ORDER BY date ASC
")->fetchAll();

// 2. 簽到活躍度 (最近 7 天)
$checkin_stats = $pdo->query("
    SELECT checkin_date as date, COUNT(*) as count 
    FROM checkins 
    WHERE checkin_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY checkin_date
    ORDER BY date ASC
")->fetchAll();

// 3. 積分分佈
$points_dist = $pdo->query("
    SELECT 
        SUM(CASE WHEN points < 1000 THEN 1 ELSE 0 END) as low,
        SUM(CASE WHEN points >= 1000 AND points < 5000 THEN 1 ELSE 0 END) as mid,
        SUM(CASE WHEN points >= 5000 THEN 1 ELSE 0 END) as high
    FROM users
")->fetch();

// 4. 最近 10 位用戶
$recent_users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 10")->fetchAll();
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title>管理後台 | TEST GAME</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(0,242,255,0.2); border-radius: 10px; }
    </style>
</head>

<body class="bg-[#05050b] text-white flex min-h-screen">
    <!-- Sidebar -->
    <div class="w-72 bg-gray-900/50 backdrop-blur-xl p-8 border-r border-white/5 flex flex-col">
        <h1 class="text-3xl font-black text-secondary mb-12 tracking-tighter italic">ADMIN<span class="text-white">CORE</span></h1>
        
        <nav class="flex-1 space-y-1">
            <div class="text-[10px] uppercase tracking-[0.3em] text-white/20 mb-4 font-black">Main Hub</div>
            <a href="dashboard.php" class="flex items-center gap-3 py-3 px-4 rounded-xl bg-secondary/10 text-secondary border border-secondary/20 font-bold">
                📊 儀表板 Dashboard
            </a>
            <a href="users.php" class="flex items-center gap-3 py-3 px-4 rounded-xl hover:bg-white/5 text-white/50 hover:text-white transition-all">
                👥 會員管理 Users
            </a>
            <a href="logs.php" class="flex items-center gap-3 py-3 px-4 rounded-xl hover:bg-white/5 text-white/50 hover:text-white transition-all">
                📜 操作日誌 Logs
            </a>
            <a href="messages.php" class="flex items-center gap-3 py-3 px-4 rounded-xl hover:bg-white/5 text-white/50 hover:text-white transition-all">
                💬 客服中心 Help
            </a>

            <div class="pt-8 text-[10px] uppercase tracking-[0.3em] text-white/20 mb-4 font-black">System Ops</div>
            <a href="f/sql.php" class="flex items-center gap-3 py-3 px-4 rounded-xl hover:bg-white/5 text-white/50 hover:text-white transition-all">
                🗄️ SQL 控制台
            </a>
            <a href="f/api.php" class="flex items-center gap-3 py-3 px-4 rounded-xl hover:bg-white/5 text-white/50 hover:text-white transition-all">
                🔌 API 健康度
            </a>
        </nav>

        <a href="../index.php" class="mt-auto flex items-center justify-center gap-2 py-4 rounded-2xl bg-white/5 hover:bg-red-500/20 text-white/40 hover:text-red-400 transition-all font-black text-xs uppercase tracking-widest border border-white/5">
            EXIT TO SITE
        </a>
    </div>

    <!-- Content -->
    <div class="flex-1 p-12 overflow-y-auto">
        <header class="flex justify-between items-center mb-12">
            <div>
                <h2 class="text-4xl font-black uppercase tracking-tighter">控制儀表板 <span class="text-secondary">DASHBOARD</span></h2>
                <p class="text-white/30 text-xs mt-1 uppercase tracking-widest font-bold">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            </div>
            <div class="flex gap-4">
                <div class="text-right">
                    <p class="text-[10px] uppercase font-black text-white/20">Server Status</p>
                    <p class="text-green-400 font-black text-xs flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span> ONLINE
                    </p>
                </div>
            </div>
        </header>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div class="bg-white/5 p-8 rounded-[32px] border border-white/5 relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 text-6xl opacity-5 group-hover:scale-110 transition-transform">👥</div>
                <p class="text-white/40 text-[10px] uppercase font-black tracking-widest mb-2">總註冊用戶</p>
                <h3 class="text-4xl font-black"><?php echo number_format($total_users); ?></h3>
                <p class="text-[9px] text-green-400 mt-2 font-bold">+<?php echo count($user_growth); ?> (Last 7d)</p>
            </div>
            <div class="bg-white/5 p-8 rounded-[32px] border border-white/5 relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 text-6xl opacity-5 group-hover:scale-110 transition-transform">📧</div>
                <p class="text-white/40 text-[10px] uppercase font-black tracking-widest mb-2">已驗證用戶</p>
                <h3 class="text-4xl font-black text-secondary"><?php echo number_format($verified_users); ?></h3>
                <p class="text-[9px] text-white/20 mt-2 font-bold">Verified Ratio: <?php echo $total_users ? round(($verified_users/$total_users)*100) : 0; ?>%</p>
            </div>
            <div class="bg-white/5 p-8 rounded-[32px] border border-white/5 relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 text-6xl opacity-5 group-hover:scale-110 transition-transform">🎮</div>
                <p class="text-white/40 text-[10px] uppercase font-black tracking-widest mb-2">Roblox 綁定</p>
                <h3 class="text-4xl font-black text-accent"><?php echo number_format($roblox_binds); ?></h3>
                <p class="text-[9px] text-white/20 mt-2 font-bold">Account Integrity High</p>
            </div>
            <div class="bg-white/5 p-8 rounded-[32px] border border-white/5 relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 text-6xl opacity-5 group-hover:scale-110 transition-transform">📈</div>
                <p class="text-white/40 text-[10px] uppercase font-black tracking-widest mb-2">今日簽到數</p>
                <h3 class="text-4xl font-black text-yellow-400"><?php echo count($checkin_stats) ? end($checkin_stats)['count'] : 0; ?></h3>
                <p class="text-[9px] text-white/20 mt-2 font-bold">Active Participation</p>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <div class="bg-white/5 p-10 rounded-[40px] border border-white/5">
                <div class="flex justify-between items-center mb-8">
                    <h4 class="font-black uppercase tracking-widest text-xs">用戶增長趨勢 (Growth)</h4>
                    <span class="text-[9px] text-white/20 font-mono">CHART_ID: 101</span>
                </div>
                <div class="h-[300px]">
                    <canvas id="growthChart"></canvas>
                </div>
            </div>
            <div class="bg-white/5 p-10 rounded-[40px] border border-white/5">
                <div class="flex justify-between items-center mb-8">
                    <h4 class="font-black uppercase tracking-widest text-xs">積分資產分佈 (Economy)</h4>
                    <span class="text-[9px] text-white/20 font-mono">CHART_ID: 102</span>
                </div>
                <div class="h-[300px] flex items-center justify-center">
                    <canvas id="pointsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Users Table -->
        <div class="bg-white/5 rounded-[40px] border border-white/5 overflow-hidden">
            <div class="p-10 border-b border-white/5 flex justify-between items-center">
                <h3 class="text-xl font-black uppercase italic">最新註冊用戶 <span class="text-white/20 not-italic">RECENT_USERS</span></h3>
                <a href="users.php" class="px-6 py-2 bg-white/5 hover:bg-white/10 rounded-full text-[10px] font-black uppercase transition-all tracking-widest border border-white/10">Manage All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] uppercase font-black text-white/30 tracking-widest border-b border-white/5">
                            <th class="p-8">ID</th>
                            <th class="p-8">用戶名 (Username)</th>
                            <th class="p-8">驗證狀態</th>
                            <th class="p-8">註冊時間</th>
                            <th class="p-8">操作</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <?php foreach ($recent_users as $user): ?>
                            <tr class="border-b border-white/[0.02] hover:bg-white/[0.02] transition-colors">
                                <td class="p-8 font-mono text-xs text-white/20">#<?php echo $user['id']; ?></td>
                                <td class="p-8">
                                    <div class="flex items-center gap-3">
                                        <?php if ($user['avatar_url']): ?>
                                            <img src="<?php echo htmlspecialchars($user['avatar_url']); ?>" class="w-8 h-8 rounded-full border border-white/10" alt="">
                                        <?php else: ?>
                                            <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-xs">👤</div>
                                        <?php endif; ?>
                                        <span class="font-bold"><?php echo htmlspecialchars($user['username']); ?></span>
                                    </div>
                                </td>
                                <td class="p-8">
                                    <?php if ($user['email_verified']): ?>
                                        <span class="px-3 py-1 bg-green-500/10 text-green-400 text-[9px] font-black rounded-full border border-green-500/20">VERIFIED</span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 bg-white/5 text-white/30 text-[9px] font-black rounded-full border border-white/10">PENDING</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-8 text-xs text-white/40 font-mono">
                                    <?php echo $user['created_at']; ?>
                                </td>
                                <td class="p-8">
                                    <a href="users.php?edit=<?php echo $user['id']; ?>"
                                        class="text-secondary hover:text-white transition-colors text-xs font-black uppercase tracking-widest">Manage</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Chart Config
        const growthCtx = document.getElementById('growthChart').getContext('2d');
        const pointsCtx = document.getElementById('pointsChart').getContext('2d');

        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($user_growth, 'date')); ?>,
                datasets: [{
                    label: 'New Users',
                    data: <?php echo json_encode(array_column($user_growth, 'count')); ?>,
                    borderColor: '#00f2ff',
                    backgroundColor: 'rgba(0, 242, 255, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointBackgroundColor: '#00f2ff',
                    borderWidth: 4
                }, {
                    label: 'Check-ins',
                    data: <?php echo json_encode(array_column($checkin_stats, 'count')); ?>,
                    borderColor: '#8a2be2',
                    backgroundColor: 'transparent',
                    borderDash: [5, 5],
                    tension: 0.4,
                    pointRadius: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: 'rgba(255,255,255,0.3)', font: { size: 10 } } },
                    y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: 'rgba(255,255,255,0.3)', font: { size: 10 } } }
                }
            }
        });

        new Chart(pointsCtx, {
            type: 'doughnut',
            data: {
                labels: ['< 1k', '1k - 5k', '5k+'],
                datasets: [{
                    data: [<?php echo $points_dist['low']; ?>, <?php echo $points_dist['mid']; ?>, <?php echo $points_dist['high']; ?>],
                    backgroundColor: ['#8a2be2', '#00f2ff', '#ff007f'],
                    borderWidth: 0,
                    hoverOffset: 20
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '80%',
                plugins: { legend: { position: 'bottom', labels: { color: 'rgba(255,255,255,0.4)', font: { size: 10, weight: 'bold' }, padding: 20 } } }
            }
        });
    </script>
</body>

</html>

</html>