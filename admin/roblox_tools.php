<?php
require_once __DIR__ . '/../includes/auth_logic.php';
require_once __DIR__ . '/../includes/roblox_api.php';

if (!Auth::isAdmin()) {
    header("Location: ../login.php");
    exit;
}

$roblox = new RobloxAPI(ROBLOX_API_KEY, ROBLOX_UNIVERSE_ID);
$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'broadcast') {
        $msg = $_POST['message'] ?? '';
        $result = $roblox->publishMessage('GlobalAnnouncement', ['text' => $msg]);
    } elseif ($action === 'give_currency') {
        $target_id = $_POST['target_id'] ?? '';
        $amount = (int) $_POST['amount'] ?? 0;
        // 假設遊戲端 DataStore 名為 'PlayerData'，Key 為 'UserId_Currency'
        $result = $roblox->publishMessage('GiveCurrency', ['userId' => $target_id, 'amount' => $amount]);
    }
}
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title>Roblox 管理工具 | TEST GAME</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white flex">
    <div class="w-64 min-h-screen bg-gray-800 p-6 flex flex-col">
        <h1 class="text-2xl font-bold text-cyan-400 mb-10">ADMIN PANEL</h1>
        <nav class="flex-1">
            <a href="dashboard.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">儀表板</a>
            <a href="users.php"
                class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 mt-2">會員管理</a>
            <a href="roblox_tools.php" class="block py-2.5 px-4 rounded transition duration-200 bg-gray-700 mt-2">Roblox
                管理</a>
        </nav>
    </div>

    <div class="flex-1 p-10">
        <h2 class="text-3xl font-bold mb-10">Roblox 開發者工具 (DevOps)</h2>

        <?php if ($result): ?>
            <div
                class="p-5 rounded-xl mb-10 <?php echo $result['success'] ? 'bg-green-600/20 text-green-400 border border-green-600' : 'bg-red-600/20 text-red-400 border border-red-600'; ?>">
                <strong>API 結果:</strong>
                <?php echo $result['success'] ? '執行成功' : '失敗: ' . ($result['error'] ?? '未知錯誤'); ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <!-- Broadcast -->
            <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700">
                <h3 class="text-xl font-bold mb-6">全服動態廣播</h3>
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="action" value="broadcast">
                    <textarea name="message" required
                        class="w-full bg-gray-700 border border-gray-600 p-4 rounded-xl h-32"
                        placeholder="輸入廣播內容..."></textarea>
                    <button type="submit"
                        class="w-full bg-cyan-600 py-3 rounded-xl font-bold hover:bg-cyan-500">立即廣播</button>
                </form>
            </div>

            <!-- Currency -->
            <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700">
                <h3 class="text-xl font-bold mb-6">發放虛擬貨幣</h3>
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="action" value="give_currency">
                    <input type="text" name="target_id" required
                        class="w-full bg-gray-700 border border-gray-600 p-4 rounded-xl" placeholder="Roblox Player ID">
                    <input type="number" name="amount" required
                        class="w-full bg-gray-700 border border-gray-600 p-4 rounded-xl" placeholder="發放金額">
                    <button type="submit"
                        class="w-full bg-cyan-600 py-3 rounded-xl font-bold hover:bg-cyan-500">發放補償</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>