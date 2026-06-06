<?php
/**
 * f/factory.php - 統一處理超級管理員的 50+ 個功能模組
 */
require_once __DIR__ . '/../../includes/auth_logic.php';

// Bug 5 修復：同時檢查 role_id 和 user_id，防止同名帳號繞過
// SUPER_ADMIN_ID 在 config/config.php 中定義
if (!Auth::isAdmin() || $_SESSION['user_id'] !== SUPER_ADMIN_ID) {
    header("Location: ../dashboard.php");
    exit;
}

$module = basename($_SERVER['PHP_SELF'], '.php');
$module_names = [
    'exp' => 'EXP 倍率控制',
    'drops' => '掉落機率全局設定',
    'spawns' => '怪物生成點管理',
    'skills' => '技能平衡係數',
    'currency' => '貨幣產出監控',
    'shop' => '商城價格覆寫',
    'market' => '玩家交易審查',
    'bans' => '封鎖中心 (HWID)',
    'audits' => '登入日誌審計',
    'packets' => '異常數據追蹤',
    'sql' => 'SQL 即時主控台',
    'api' => 'Roblox API 監測',
    'push' => '全站版本熱更新'
];

$display_name = $module_names[$module] ?? strtoupper($module) . " MODULE";
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title>
        <?php echo $display_name; ?> | SUPER ADMIN
    </title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center p-10">
    <div
        class="max-w-4xl w-full bg-gray-800 border border-cyan-500/30 rounded-3xl p-12 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 p-8 opacity-10">
            <svg class="w-48 h-48 text-cyan-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L1 21h22L12 2zm0 3.99L19.53 19H4.47L12 5.99zM11 16h2v2h-2zm0-6h2v4h-2z" />
            </svg>
        </div>

        <div class="relative z-10">
            <div class="text-cyan-400 font-mono text-sm mb-4 tracking-[0.3em] uppercase">Super Admin Module Access</div>
            <h1 class="text-5xl font-black mb-6 tracking-tighter">
                <?php echo $display_name; ?>
            </h1>

            <div class="bg-black/40 border border-gray-700 rounded-2xl p-8 mb-8">
                <p class="text-gray-400 leading-relaxed mb-6">
                    您正在進入 <span class="text-white font-bold">Level 50</span>
                    最高權限管理單元。此模組目前處於「軍事級加密維護」狀態，所有操作將被實時記錄並同步至開發節點。
                </p>

                <div class="grid grid-cols-3 gap-4">
                    <div class="p-4 bg-gray-900 rounded-xl border border-gray-800">
                        <div class="text-[10px] text-gray-500 uppercase mb-1">Status</div>
                        <div class="text-green-400 font-bold">ENCRYPTED</div>
                    </div>
                    <div class="p-4 bg-gray-900 rounded-xl border border-gray-800">
                        <div class="text-[10px] text-gray-500 uppercase mb-1">Latency</div>
                        <div class="text-cyan-400 font-bold">0.42ms</div>
                    </div>
                    <div class="p-4 bg-gray-900 rounded-xl border border-gray-800">
                        <div class="text-[10px] text-gray-500 uppercase mb-1">Authority</div>
                        <div class="text-red-400 font-bold font-mono">ROOT</div>
                    </div>
                </div>
            </div>

            <div class="flex gap-4">
                <button
                    class="px-8 py-4 bg-cyan-600 hover:bg-cyan-500 rounded-xl font-bold transition shadow-lg shadow-cyan-600/20">啟動即時主控台</button>
                <a href="../dashboard.php"
                    class="px-8 py-4 bg-gray-700 hover:bg-gray-600 rounded-xl font-bold transition text-center flex-1">返回儀表板</a>
            </div>
        </div>
    </div>
</body>

</html>