<?php
/**
 * admin/logs.php — 管理員操作日誌（新功能 8）
 */
require_once __DIR__ . '/../includes/auth_logic.php';
include '../templates/header.php';

if (!Auth::isAdmin()) {
    header("Location: ../login.php");
    exit;
}

// 分頁處理
$limit = 20;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// 取得日誌總數
$totalLogs = $pdo->query("SELECT COUNT(*) FROM admin_logs")->fetchColumn();
$totalPages = ceil($totalLogs / $limit);

// 取得日誌與管理員姓名
$stmt = $pdo->prepare("
    SELECT l.*, u.username as admin_name 
    FROM admin_logs l 
    JOIN users u ON l.admin_id = u.id 
    ORDER BY l.created_at DESC 
    LIMIT ? OFFSET ?
");
$stmt->bindValue(1, $limit, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$logs = $stmt->fetchAll();
?>

<main class="pt-32 pb-24 px-[5%]">
    <div class="max-w-[1200px] mx-auto">
        
        <div class="flex justify-between items-center mb-12">
            <div>
                <h2 class="text-4xl font-black uppercase tracking-tighter">操作 <span class="text-accent">日誌</span></h2>
                <p class="text-white/40 text-xs tracking-widest uppercase mt-1">Admin Audit Logs</p>
            </div>
            <a href="dashboard.php" class="px-6 py-3 border border-white/10 rounded-full text-xs font-black uppercase hover:border-secondary transition-all">
                返回儀表板
            </a>
        </div>

        <div class="bg-white/5 border border-white/10 backdrop-blur-2xl rounded-3xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-white/5 border-b border-white/10 text-white/50 text-[10px] uppercase font-black tracking-widest">
                            <th class="p-6">時間</th>
                            <th class="p-6">管理員</th>
                            <th class="p-6">動作 (Action)</th>
                            <th class="p-6">對象 (Target)</th>
                            <th class="p-6">詳情 (Details)</th>
                            <th class="p-6">IP 地址</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-white/5">
                        <?php foreach ($logs as $log): ?>
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="p-6 text-white/40 whitespace-nowrap"><?php echo $log['created_at']; ?></td>
                            <td class="p-6 font-bold text-secondary"><?php echo htmlspecialchars($log['admin_name']); ?></td>
                            <td class="p-6">
                                <span class="px-2 py-1 bg-white/10 rounded text-[10px] font-black"><?php echo $log['action']; ?></span>
                            </td>
                            <td class="p-6 text-white/60">
                                <?php if ($log['target_id']): ?>
                                    <span class="text-[10px] bg-secondary/10 text-secondary px-2 py-1 rounded">
                                        ID: <?php echo $log['target_id']; ?>
                                    </span>
                                <?php else: ?>
                                    ---
                                <?php endif; ?>
                            </td>
                            <td class="p-6 text-white/80 max-w-[300px] truncate" title="<?php echo htmlspecialchars($log['details']); ?>">
                                <?php echo htmlspecialchars($log['details']); ?>
                            </td>
                            <td class="p-6 text-white/30 font-mono text-xs"><?php echo $log['ip_address']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex justify-center gap-2">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" 
                   class="w-10 h-10 flex items-center justify-center rounded-xl border <?php echo $page == $i ? 'bg-accent border-accent text-white' : 'border-white/10 text-white/40 hover:border-white/40'; ?> font-bold transition-all text-xs">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>
