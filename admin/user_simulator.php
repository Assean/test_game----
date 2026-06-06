<?php
/**
 * user_simulator.php - 測試帳號批量模擬器
 * 僅限管理員使用，用於快速生成 01-n 的測試資料
 */
require_once __DIR__ . '/../includes/auth_logic.php';

if (!Auth::isAdmin()) {
    header("Location: ../login.php");
    exit;
}

$msg = "";
$error = "";

if (isset($_POST['simulate'])) {
    $count = (int) $_POST['count'];
    $default_password = password_hash($_POST['password'] ?: '12345678', PASSWORD_DEFAULT);

    if ($count <= 0 || $count > 100) {
        $error = "每次模擬上限為 100 個帳號。";
    } else {
        try {
            $pdo->beginTransaction();

            // 獲取目前最大的編號
            $stmt = $pdo->query("SELECT username FROM users WHERE username REGEXP '^[0-9]+$' ORDER BY CAST(username AS UNSIGNED) DESC LIMIT 1");
            $last_user = $stmt->fetchColumn();
            $start_index = $last_user ? (int) $last_user + 1 : 1;

            $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role_id, created_by_admin) VALUES (?, ?, ?, 1, 1)");

            for ($i = 0; $i < $count; $i++) {
                $num = str_pad($start_index + $i, 2, '0', STR_PAD_LEFT);
                $username = $num;
                $email = "test{$num}@example.com";
                $stmt->execute([$username, $default_password, $email]);
            }

            $pdo->commit();
            $msg = "成功模擬生成 {$count} 個帳號（從 {$start_index} 開始）。";
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = "模擬失敗：" . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title>測試數據模擬器 | 管理後台</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center p-6">
    <div
        class="max-w-xl w-full bg-gray-800 border-2 border-cyan-500/20 rounded-[2.5rem] p-12 shadow-2xl relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-cyan-500 to-transparent">
        </div>
        <div class="absolute top-0 right-0 p-8 opacity-5">
            <svg class="w-32 h-32 text-cyan-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L1 21h22L12 2zm0 3.99L19.53 19H4.47L12 5.99zM11 16h2v2h-2zm0-6h2v4h-2z" />
            </svg>
        </div>

        <div class="relative z-10">
            <h1 class="text-4xl font-black mb-2 tracking-tighter italic">USER SIMULATOR</h1>
            <p class="text-cyan-400 font-mono text-[10px] uppercase tracking-[0.3em] mb-10">Stress Test & Data
                Generation Unit</p>

            <?php if ($msg): ?>
                <div
                    class="bg-green-600/20 text-green-400 border border-green-600/30 p-4 rounded-2xl mb-8 text-sm flex items-center gap-3">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="bg-red-600/20 text-red-400 border border-red-600/30 p-4 rounded-2xl mb-8 text-sm">
                    ⚠️
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div>
                    <label
                        class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2 font-mono">Generation
                        Quantity (n)</label>
                    <input type="number" name="count" required min="1" max="100" value="10"
                        class="w-full bg-gray-900 border border-gray-700 rounded-2xl px-6 py-4 outline-none focus:border-cyan-400 transition text-xl font-black font-mono">
                </div>

                <div>
                    <label
                        class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2 font-mono">Global
                        Simulation Password</label>
                    <input type="text" name="password" placeholder="預設: 12345678"
                        class="w-full bg-gray-900 border border-gray-700 rounded-2xl px-6 py-4 outline-none focus:border-cyan-400 transition text-gray-400">
                </div>

                <div class="pt-6 flex gap-4">
                    <button type="submit" name="simulate"
                        class="flex-1 py-5 bg-cyan-600 hover:bg-cyan-500 rounded-2xl font-black tracking-widest uppercase transition shadow-lg shadow-cyan-600/20 flex items-center justify-center gap-2">
                        START SIMULATION
                    </button>
                    <a href="dashboard.php"
                        class="px-8 py-5 bg-gray-700 hover:bg-gray-600 rounded-2xl font-black transition text-center uppercase text-sm flex items-center">
                        EXIT
                    </a>
                </div>
            </form>

            <div class="mt-8 pt-8 border-t border-gray-700/50">
                <div class="flex items-center gap-4 opacity-30">
                    <div class="flex-1 h-[1px] bg-gray-600"></div>
                    <div class="text-[9px] font-mono">SYSTEM READY</div>
                    <div class="flex-1 h-[1px] bg-gray-600"></div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>