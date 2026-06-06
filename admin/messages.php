<?php
require_once __DIR__ . '/../includes/auth_logic.php';

if (!Auth::isAdmin()) {
    header("Location: ../login.php");
    exit;
}

// 獲取所有有對話的用戶
try {
    $stmt = $pdo->query("
        SELECT DISTINCT u.id, u.username, 
        (SELECT content FROM messages WHERE sender_id = u.id OR receiver_id = u.id ORDER BY created_at DESC LIMIT 1) as last_msg,
        (SELECT created_at FROM messages WHERE sender_id = u.id OR receiver_id = u.id ORDER BY created_at DESC LIMIT 1) as last_time
        FROM messages m
        JOIN users u ON (m.sender_id = u.id AND m.is_admin_reply = 0) OR (m.receiver_id = u.id AND m.is_admin_reply = 1)
        ORDER BY last_time DESC
    ");
    $chats = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // 如果表格不存在或查詢失敗，暫時顯示空列表
    $chats = [];
}

$active_chat = $_GET['user_id'] ?? ($chats[0]['id'] ?? null);
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title>客服中心 | 管理後台</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <div class="w-80 bg-gray-800 border-r border-gray-700 flex flex-col">
        <div class="p-6 border-b border-gray-700">
            <h2 class="text-xl font-bold text-cyan-400">SUPPORT INBOX</h2>
        </div>
        <div class="flex-1 overflow-y-auto">
            <?php foreach ($chats as $chat): ?>
                <a href="?user_id=<?php echo $chat['id']; ?>"
                    class="block p-4 border-b border-gray-700 hover:bg-gray-700/50 transition-all <?php echo $active_chat == $chat['id'] ? 'bg-gray-700' : ''; ?>">
                    <div class="flex justify-between items-start mb-1">
                        <span class="font-bold text-sm">
                            <?php echo htmlspecialchars($chat['username']); ?>
                        </span>
                        <span class="text-[10px] text-gray-500">
                            <?php echo date('H:i', strtotime($chat['last_time'])); ?>
                        </span>
                    </div>
                    <p class="text-xs text-gray-400 truncate">
                        <?php echo htmlspecialchars($chat['last_msg']); ?>
                    </p>
                </a>
            <?php endforeach; ?>
        </div>
        <a href="dashboard.php"
            class="p-4 bg-gray-900 text-center text-xs text-gray-500 hover:text-white uppercase tracking-widest font-bold">Back
            to Dashboard</a>
    </div>

    <!-- Chat Area -->
    <div class="flex-1 flex flex-col bg-gray-900">
        <?php if ($active_chat): ?>
            <div class="p-6 border-b border-gray-700 bg-gray-800/50 flex justify-between items-center">
                <h3 class="font-bold text-lg">對話中：
                    <?php
                    $u_stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
                    $u_stmt->execute([$active_chat]);
                    echo htmlspecialchars($u_stmt->fetchColumn());
                    ?>
                </h3>
            </div>

            <div id="chatBox" class="flex-1 p-8 overflow-y-auto space-y-4">
                <!-- Messages will be loaded here -->
            </div>

            <form id="replyForm" class="p-6 bg-gray-800 border-t border-gray-700 flex gap-4">
                <input type="text" id="replyInput" required
                    class="flex-1 bg-gray-900 border border-gray-700 rounded-lg px-6 py-3 focus:outline-none focus:border-cyan-400"
                    placeholder="輸入回覆內容...">
                <button type="submit"
                    class="px-8 py-3 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-lg transition-all">
                    回覆
                </button>
            </form>
        <?php else: ?>
            <div class="flex-1 flex items-center justify-center text-gray-600 uppercase tracking-[0.5em] font-black">
                Select a conversation
            </div>
        <?php endif; ?>
    </div>

    <script>
        const chatBox = document.getElementById('chatBox');
        const replyForm = document.getElementById('replyForm');
        const replyInput = document.getElementById('replyInput');
        const activeUserId = <?php echo json_encode($active_chat); ?>;

        async function fetchMessages() {
            if (!activeUserId) return;
            try {
                const response = await fetch('../auth/message_handler.php', {
                    method: 'POST',
                    body: new URLSearchParams({ action: 'fetch', other_id: activeUserId })
                });
                const res = await response.json();
                if (res.success) {
                    chatBox.innerHTML = res.data.map(m => `
                    <div class="flex ${m.is_admin_reply == 1 ? 'justify-end' : 'justify-start'}">
                        <div class="max-w-[70%] p-4 rounded-2xl text-sm ${m.is_admin_reply == 1 ? 'bg-cyan-900 text-cyan-50' : 'bg-gray-800 text-gray-200'}">
                            ${m.content}
                            <div class="text-[9px] mt-2 opacity-50 font-bold">${new Date(m.created_at).toLocaleTimeString()}</div>
                        </div>
                    </div>
                `).join('');
                    chatBox.scrollTop = chatBox.scrollHeight;
                }
            } catch (err) { console.error(err); }
        }

        replyForm.onsubmit = async (e) => {
            e.preventDefault();
            const content = replyInput.value;
            if (!content) return;

            try {
                const response = await fetch('../auth/message_handler.php', {
                    method: 'POST',
                    body: new URLSearchParams({
                        action: 'send',
                        content: content,
                        receiver_id: activeUserId
                    })
                });
                const res = await response.json();
                if (res.success) {
                    replyInput.value = '';
                    fetchMessages();
                }
            } catch (err) { console.error(err); }
        };

        fetchMessages();
        setInterval(fetchMessages, 3000);
    </script>
</body>

</html>