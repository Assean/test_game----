<?php
require_once __DIR__ . '/../includes/auth_logic.php';

if (!Auth::isAdmin()) {
    header("Location: ../login.php");
    exit;
}

$msg = "";
$error = "";

// 處理新增會員
if (isset($_POST['add_user'])) {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = trim($_POST['email']);
    $role_id = (int) $_POST['role_id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role_id, created_by_admin) VALUES (?, ?, ?, ?, 1)");
        $stmt->execute([$username, $password, $email, $role_id]);
        $msg = "成功建立會員！";
    } catch (PDOException $e) {
        $error = "建立失敗：" . $e->getMessage();
    }
}

// 處理編輯會員
if (isset($_POST['edit_user'])) {
    $id = (int) $_POST['id'];
    $role_id = (int) $_POST['role_id'];
    $email = trim($_POST['email']);

    try {
        $stmt = $pdo->prepare("UPDATE users SET role_id = ?, email = ? WHERE id = ?");
        $stmt->execute([$role_id, $email, $id]);
        $msg = "會員資料已更新！";
    } catch (PDOException $e) {
        error_log('[USER EDIT ERROR] ' . $e->getMessage());
        $error = "更新失敗，請稍後再試。";
    }
}

// Bug 4 修復：刪除改為 POST 請求並驗證 CSRF Token
if (isset($_POST['delete_user_id']) && Auth::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
    $id = (int) $_POST['delete_user_id'];
    if ($id !== $_SESSION['user_id']) {
        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            header("Location: users.php?msg=deleted");
            exit;
        } catch (PDOException $e) {
            error_log('[USER DELETE ERROR] ' . $e->getMessage());
            $error = "刪除失敗，請稍後再試。";
        }
    }
}

// 搜尋過濾邏輯
$search_id = $_GET['search_id'] ?? '';
$search_role = $_GET['search_role'] ?? '';
$search_username = $_GET['search_username'] ?? '';
$search_email = $_GET['search_email'] ?? '';

$where_clauses = ["1=1"];
$params = [];

if ($search_id !== '') {
    $where_clauses[] = "u.id = ?";
    $params[] = (int) $search_id;
}
if ($search_role !== '') {
    $where_clauses[] = "u.role_id = ?";
    $params[] = (int) $search_role;
}
if ($search_username !== '') {
    $where_clauses[] = "u.username LIKE ?";
    $params[] = "%$search_username%";
}
if ($search_email !== '') {
    $where_clauses[] = "u.email LIKE ?";
    $params[] = "%$search_email%";
}

$where_sql = implode(" AND ", $where_clauses);

// 獲取角色列表
$roles = $pdo->query("SELECT * FROM roles ORDER BY id ASC")->fetchAll();

// 獲取會員列表 (含過濾)
$stmt = $pdo->prepare("SELECT u.*, r.role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE $where_sql ORDER BY u.id DESC");
$stmt->execute($params);
$users = $stmt->fetchAll();

if (isset($_GET['msg']) && $_GET['msg'] === 'deleted')
    $msg = "已成功刪除該會員。";
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title>會員大數據管理 | 管理後台</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #06b6d4;
            border-radius: 10px;
        }
    </style>
</head>

<body class="bg-gray-900 text-white flex h-screen overflow-hidden">
    <!-- Sidebar (Simplified for users.php) -->
    <div class="w-64 bg-gray-800 border-r border-gray-700 flex flex-col p-6 overflow-y-auto custom-scrollbar">
        <h2 class="text-xl font-black text-cyan-400 mb-10 tracking-tighter">DATA CENTER</h2>
        <nav class="space-y-2">
            <a href="dashboard.php" class="block py-2 px-4 rounded hover:bg-gray-700 transition">儀表板</a>
            <a href="users.php"
                class="block py-2 px-4 rounded bg-cyan-600/20 text-cyan-400 border border-cyan-400/20">會員大數據</a>
            <a href="user_simulator.php" class="block py-2 px-4 rounded hover:bg-gray-700 transition">模擬器 Simulator</a>
            <a href="messages.php" class="block py-2 px-4 rounded hover:bg-gray-700 transition">管理收件夾</a>
        </nav>
        <div class="mt-auto pt-6 border-t border-gray-700">
            <button onclick="document.getElementById('addModal').classList.toggle('hidden')"
                class="w-full py-3 bg-cyan-600 hover:bg-cyan-500 rounded-lg font-bold transition">＋ 手動新增會員</button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
        <header class="p-8 border-b border-gray-700 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black">會員清單管理 <span class="text-gray-500 ml-2 text-sm">TOTAL:
                        <?php echo count($users); ?></span></h1>
            </div>
            <div class="flex gap-4">
                <?php if ($msg): ?><span
                        class="bg-green-600/20 text-green-400 border border-green-600/30 px-4 py-2 rounded text-xs"><?php echo $msg; ?></span><?php endif; ?>
                <?php if ($error): ?><span
                        class="bg-red-600/20 text-red-400 border border-red-600/30 px-4 py-2 rounded text-xs"><?php echo $error; ?></span><?php endif; ?>
            </div>
        </header>

        <!-- Search Bar -->
        <div class="px-8 pt-6 pb-2">
            <form method="GET"
                class="bg-gray-800/40 border border-gray-700/50 p-4 rounded-2xl flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[100px]">
                    <label
                        class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1 font-mono">Filter
                        by ID</label>
                    <input type="number" name="search_id" value="<?php echo htmlspecialchars($search_id); ?>"
                        placeholder="ID #"
                        class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-3 py-2 text-xs outline-none focus:border-cyan-400 transition">
                </div>
                <div class="flex-[2] min-w-[150px]">
                    <label
                        class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1 font-mono">Username</label>
                    <input type="text" name="search_username" value="<?php echo htmlspecialchars($search_username); ?>"
                        placeholder="Search Username..."
                        class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-3 py-2 text-xs outline-none focus:border-cyan-400 transition">
                </div>
                <div class="flex-[2] min-w-[150px]">
                    <label
                        class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1 font-mono">Email
                        Address</label>
                    <input type="text" name="search_email" value="<?php echo htmlspecialchars($search_email); ?>"
                        placeholder="Search Email..."
                        class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-3 py-2 text-xs outline-none focus:border-cyan-400 transition">
                </div>
                <div class="flex-1 min-w-[120px]">
                    <label
                        class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1 font-mono">Authority</label>
                    <select name="search_role"
                        class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-3 py-2 text-xs outline-none focus:border-cyan-400 transition">
                        <option value="">ALL ROLES</option>
                        <?php foreach ($roles as $r): ?>
                            <option value="<?php echo $r['id']; ?>" <?php echo $search_role == $r['id'] ? 'selected' : ''; ?>>
                                <?php echo $r['role_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="px-5 py-2 bg-cyan-600 hover:bg-cyan-500 rounded-lg text-xs font-bold transition">SEARCH</button>
                    <a href="users.php"
                        class="px-5 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-xs font-bold transition">CLEAR</a>
                </div>
            </form>
        </div>

        <div class="flex-1 p-8 overflow-y-auto custom-scrollbar">
            <div class="bg-gray-800/50 border border-gray-700 rounded-2xl overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-700/50 text-xs uppercase tracking-widest text-gray-400">
                        <tr>
                            <th class="p-4">ID</th>
                            <th class="p-4">權限身份</th>
                            <th class="p-4">基本資料</th>
                            <th class="p-4">建立來源</th>
                            <th class="p-4">註冊時間</th>
                            <th class="p-4">操作行動</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <?php foreach ($users as $user): ?>
                            <tr class="border-b border-gray-700 hover:bg-white/5 transition">
                                <td class="p-4 text-gray-500 font-mono">#<?php echo $user['id']; ?></td>
                                <td class="p-4">
                                    <?php
                                    $label_color = 'bg-gray-700';
                                    if ($user['role_id'] == 3)
                                        $label_color = 'bg-red-900/40 text-red-400 border border-red-400/20';
                                    elseif ($user['role_id'] == 4)
                                        $label_color = 'bg-blue-900/40 text-blue-400 border border-blue-400/20';
                                    elseif ($user['role_id'] == 5)
                                        $label_color = 'bg-orange-900/40 text-orange-400 border border-orange-400/20';
                                    ?>
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-black uppercase <?php echo $label_color; ?>">
                                        <?php echo $user['role_name']; ?>
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="font-bold"><?php echo htmlspecialchars($user['username']); ?></div>
                                    <div class="text-[10px] text-gray-500"><?php echo htmlspecialchars($user['email']); ?>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <?php if ($user['created_by_admin'] ?? 0): ?>
                                        <span class="text-cyan-400 flex items-center gap-1"><span
                                                class="w-1 h-1 bg-cyan-400 rounded-full"></span> 管理員手動建立</span>
                                    <?php else: ?>
                                        <span class="text-gray-400">使用者自行註冊</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4 font-mono text-gray-500 text-xs"><?php echo $user['created_at']; ?></td>
                                <td class="p-4">
                                    <div class="flex gap-2">
                                        <button onclick="openEdit(<?php echo htmlspecialchars(json_encode($user)); ?>)"
                                            class="px-3 py-1.5 bg-gray-700 hover:bg-gray-600 rounded text-[10px] font-bold transition">EDIT</button>
                                        <button onclick="openMore(<?php echo htmlspecialchars(json_encode($user)); ?>)"
                                            class="px-3 py-1.5 bg-cyan-900/40 text-cyan-400 border border-cyan-400/20 hover:bg-cyan-600 hover:text-white rounded text-[10px] font-bold transition">MORE</button>
                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                            <form method="POST" style="display:inline;" onsubmit="return confirm('確定要抗除此使用者數據？此操作不可復原。')">
                                                <input type="hidden" name="csrf_token" value="<?= Auth::generateCsrfToken() ?>">
                                                <input type="hidden" name="delete_user_id" value="<?= $user['id'] ?>">
                                                <button type="submit" class="px-3 py-1.5 bg-red-900/20 text-red-400 hover:bg-red-600 hover:text-white rounded text-[10px] font-bold transition">DELETE</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div id="addModal"
        class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-6">
        <div class="bg-gray-800 border border-gray-700 rounded-3xl w-full max-w-md p-8 shadow-2xl">
            <h3 class="text-xl font-black mb-6">新增系統會員</h3>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="add_user" value="1">
                <div>
                    <label
                        class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2 font-mono">Operator
                        Username</label>
                    <input type="text" name="username" required
                        class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400 transition">
                </div>
                <div>
                    <label
                        class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2 font-mono">Default
                        Password</label>
                    <input type="password" name="password" required
                        class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400 transition">
                </div>
                <div>
                    <label
                        class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2 font-mono">Contact
                        Email</label>
                    <input type="email" name="email" required
                        class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400 transition">
                </div>
                <div>
                    <label
                        class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2 font-mono">Authority
                        Level</label>
                    <select name="role_id"
                        class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400 transition">
                        <?php foreach ($roles as $r): ?>
                            <option value="<?php echo $r['id']; ?>" <?php echo $r['id'] == 1 ? 'selected' : ''; ?>>
                                <?php echo $r['role_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')"
                        class="flex-1 py-4 bg-gray-700 hover:bg-gray-600 rounded-xl font-bold transition">CANCEL</button>
                    <button type="submit"
                        class="flex-1 py-4 bg-cyan-600 hover:bg-cyan-500 rounded-xl font-bold transition">EXECUTE</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal (JS Controlled) -->
    <div id="editModal"
        class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-6">
        <div class="bg-gray-800 border border-gray-700 rounded-3xl w-full max-w-md p-8 shadow-2xl">
            <h3 class="text-xl font-black mb-6">修改會員權限</h3>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="edit_user" value="1">
                <input type="hidden" name="id" id="edit_id">
                <div>
                    <label
                        class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2 font-mono">Operator
                        ID</label>
                    <div id="edit_name"
                        class="w-full bg-gray-900/50 border border-dashed border-gray-700 rounded-xl px-4 py-3 text-gray-400">
                        ---</div>
                </div>
                <div>
                    <label
                        class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2 font-mono">Contact
                        Email</label>
                    <input type="email" name="email" id="edit_email" required
                        class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400 transition">
                </div>
                <div>
                    <label
                        class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2 font-mono">Authority
                        Level</label>
                    <select name="role_id" id="edit_role"
                        class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400 transition">
                        <?php foreach ($roles as $r): ?>
                            <option value="<?php echo $r['id']; ?>"><?php echo $r['role_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')"
                        class="flex-1 py-4 bg-gray-700 hover:bg-gray-600 rounded-xl font-bold transition">CANCEL</button>
                    <button type="submit"
                        class="flex-1 py-4 bg-cyan-600 hover:bg-cyan-500 rounded-xl font-bold transition">SAVE
                        CHANGES</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MORE Modal -->
    <div id="moreModal"
        class="hidden fixed inset-0 bg-black/90 backdrop-blur-xl z-[60] flex items-center justify-center p-6">
        <div
            class="bg-gray-800 border-2 border-cyan-500/30 rounded-[2rem] w-full max-w-2xl p-10 shadow-[0_0_50px_rgba(6,182,212,0.2)] overflow-hidden relative">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-cyan-500 to-transparent">
            </div>

            <div class="flex justify-between items-start mb-10">
                <div>
                    <h3 class="text-3xl font-black tracking-tighter text-white">USER CONTROL UNIT</h3>
                    <p class="text-cyan-400 text-xs font-mono uppercase mt-1">Target: <span id="more_username"
                            class="bg-cyan-500/10 px-2 py-0.5 rounded">---</span></p>
                </div>
                <button onclick="document.getElementById('moreModal').classList.add('hidden')"
                    class="text-gray-500 hover:text-white transition text-2xl">&times;</button>
            </div>

            <div id="more_content_area"
                class="h-[440px] overflow-y-auto custom-scrollbar pr-2 transition-all duration-500">
                <div id="action_grid" class="grid grid-cols-2 gap-4">
                    <!-- 01 DATA -->
                    <button onclick="showDetails()"
                        class="group p-5 bg-gray-900/50 border border-gray-700/50 rounded-2xl hover:border-cyan-500 transition-all text-left">
                        <div class="text-cyan-400 text-[10px] font-black mb-1 font-mono uppercase tracking-widest">01
                            DATA</div>
                        <div class="text-lg font-bold group-hover:translate-x-1 transition-transform">詳細資訊 Profile</div>
                    </button>

                    <!-- 02 ALERT -->
                    <button onclick="userAction('remind_bind')"
                        class="group p-5 bg-gray-900/50 border border-gray-700/50 rounded-2xl hover:border-yellow-500 transition-all text-left">
                        <div class="text-yellow-400 text-[10px] font-black mb-1 font-mono uppercase tracking-widest">02
                            ALERT</div>
                        <div class="text-lg font-bold group-hover:translate-x-1 transition-transform">提醒使用者綁定</div>
                    </button>

                    <!-- 03 PROTOCOL -->
                    <button onclick="triggerBan()"
                        class="group p-5 bg-red-900/20 border border-red-900/40 rounded-2xl hover:bg-red-600 hover:border-red-400 transition-all text-left">
                        <div
                            class="text-red-400 text-[10px] font-black mb-1 font-mono uppercase tracking-widest group-hover:text-white">
                            03 PROTOCOL</div>
                        <div
                            class="text-lg font-bold group-hover:translate-x-1 transition-transform group-hover:text-white">
                            帳號停權 (Ban)</div>
                    </button>

                    <!-- 04 ECONOMY -->
                    <button onclick="promptPoints()"
                        class="group p-5 bg-gray-900/50 border border-gray-700/50 rounded-2xl hover:border-green-500 transition-all text-left">
                        <div class="text-green-400 text-[10px] font-black mb-1 font-mono uppercase tracking-widest">04
                            ECONOMY</div>
                        <div class="text-lg font-bold group-hover:translate-x-1 transition-transform">積分/金幣調整</div>
                    </button>

                    <!-- 05-10 MODULES (Placeholder styled) -->
                    <?php $extra = ["強制登出 Session", "監看對話 Logs", "修改安全密碼", "數據同步校正", "權限清單審計", "社交關係重置"];
                    foreach ($extra as $i => $name): ?>
                        <button
                            class="group p-5 bg-gray-900/20 border border-gray-800 rounded-2xl opacity-40 cursor-not-allowed text-left">
                            <div class="text-gray-600 text-[10px] font-black mb-1 font-mono uppercase tracking-widest">
                                <?php echo sprintf("%02d", $i + 5); ?> MODULE</div>
                            <div class="text-lg font-bold text-gray-700"><?php echo $name; ?></div>
                        </button>
                    <?php endforeach; ?>
                </div>

                <!-- 01 Detail Panel (Hidden by default) -->
                <div id="detail_panel" class="hidden animate-in fade-in slide-in-from-bottom-4 duration-500">
                    <button onclick="hideDetails()"
                        class="mb-4 text-cyan-400 text-xs flex items-center gap-1 hover:underline">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg> BACK TO MENU
                    </button>
                    <div class="bg-gray-900/80 border border-gray-700 rounded-3xl p-8 space-y-6">
                        <div class="grid grid-cols-2 gap-8">
                            <div>
                                <p class="text-[9px] text-gray-500 font-black uppercase mb-1">Account ID</p>
                                <p id="det_id" class="text-xl font-mono">#---</p>
                            </div>
                            <div>
                                <p class="text-[9px] text-gray-500 font-black uppercase mb-1">Status</p>
                                <p id="det_status" class="text-xl">---</p>
                            </div>
                            <div>
                                <p class="text-[9px] text-gray-500 font-black uppercase mb-1">Username</p>
                                <p id="det_user" class="text-xl">---</p>
                            </div>
                            <div>
                                <p class="text-[9px] text-gray-500 font-black uppercase mb-1">Email</p>
                                <p id="det_email" class="text-xl">---</p>
                            </div>
                            <div>
                                <p class="text-[9px] text-gray-500 font-black uppercase mb-1">Authority</p>
                                <p id="det_role" class="text-xl">---</p>
                            </div>
                            <div>
                                <p class="text-[9px] text-gray-500 font-black uppercase mb-1">Economy</p>
                                <p id="det_points" class="text-xl text-green-400 font-mono">--- pts</p>
                            </div>
                        </div>
                        <div class="pt-6 border-t border-gray-700">
                            <p class="text-[9px] text-gray-500 font-black uppercase mb-2">Registration Matrix</p>
                            <p id="det_date" class="font-mono text-gray-400">TIMESTAMP: ---</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentUser = null;

        function openEdit(user) {
            document.getElementById('edit_id').value = user.id;
            document.getElementById('edit_name').innerText = user.username;
            document.getElementById('edit_email').value = user.email;
            document.getElementById('edit_role').value = user.role_id;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function openMore(user) {
            currentUser = user;
            document.getElementById('more_username').innerText = user.id; // 匹配圖中 "TARGET: 112"
            hideDetails(); // 重置面板
            document.getElementById('moreModal').classList.remove('hidden');
        }

        async function showDetails() {
            const response = await fetch('../auth/user_action_handler.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'get_details', user_id: currentUser.id })
            });
            const result = await response.json();
            if (result.success) {
                const u = result.details;
                document.getElementById('det_id').innerText = '#' + u.id;
                document.getElementById('det_status').innerHTML = u.is_banned ? '<span class="text-red-500">CLOSED (BANNED)</span>' : '<span class="text-green-500">ACTIVE</span>';
                document.getElementById('det_user').innerText = u.username;
                document.getElementById('det_email').innerText = u.email || 'N/A';
                document.getElementById('det_role').innerText = u.role_name;
                document.getElementById('det_points').innerText = u.points + ' PTS';
                document.getElementById('det_date').innerText = 'SECURE LOG: ' + u.created_at;

                document.getElementById('action_grid').classList.add('hidden');
                document.getElementById('detail_panel').classList.remove('hidden');
            }
        }

        function hideDetails() {
            document.getElementById('action_grid').classList.remove('hidden');
            document.getElementById('detail_panel').classList.add('hidden');
        }

        async function userAction(action, extraData = {}) {
            const response = await fetch('../auth/user_action_handler.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: action,
                    user_id: currentUser.id,
                    ...extraData
                })
            });
            const result = await response.json();

            if (result.success) {
                // 優雅的提示
                const toast = document.createElement('div');
                toast.className = "fixed bottom-10 right-10 bg-cyan-600 text-white px-8 py-4 rounded-2xl shadow-2xl z-[100] animate-bounce";
                toast.innerText = "√ SYSTEM: " + result.message;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            } else {
                alert('FAILED: ' + result.message);
            }
        }

        function triggerBan() {
            const pass = prompt('CRITICAL: 進行停權操作需要輸入「總管理密碼」：');
            if (pass) {
                userAction('ban', { admin_password: pass });
            }
        }

        function triggerResetPass() {
            const newPass = prompt('請輸入新的使用者密碼：');
            if (newPass) {
                userAction('change_security_pass', { new_password: newPass });
            }
        }

        function promptPoints() {
            const amount = prompt('請輸入要調整的點數金額 (正數增加，負數減少)：', '100');
            if (amount) {
                userAction('add_points', { amount: parseInt(amount) });
            }
        }
    </script>
</body>

</html>