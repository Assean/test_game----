<?php
/**
 * features/notifications.php — 通知中心（新功能 3）
 */
require_once __DIR__ . '/../includes/auth_logic.php';
include '../templates/header.php';

if (!Auth::isLoggedIn()) {
    header("Location: ../login.php");
    exit;
}
?>

<main class="pt-32 pb-24 px-[5%]">
    <div class="max-w-[700px] mx-auto">

        <div class="flex justify-between items-center mb-12 animate-fade-in-down">
            <div>
                <h2 class="text-4xl font-black uppercase tracking-tighter">通知 <span class="text-secondary">中心</span></h2>
                <p class="text-white/40 text-xs tracking-widest uppercase mt-1">Notification Center</p>
            </div>
            <button id="mark-all-read"
                class="px-6 py-3 border border-white/10 rounded-full text-xs font-black uppercase hover:border-secondary transition-all">
                全部標為已讀
            </button>
        </div>

        <div id="notifications-list" class="space-y-4 animate-fade-in-up">
            <div class="text-center text-white/30 py-12">
                <div class="text-4xl mb-4">🔔</div>
                <p class="text-sm">載入中...</p>
            </div>
        </div>
    </div>
</main>

<script>
const csrfToken = '<?php echo Auth::generateCsrfToken(); ?>';

async function loadNotifications() {
    const res  = await fetch('../auth/notification_handler.php?action=list');
    const data = await res.json();
    const list = document.getElementById('notifications-list');

    if (!data.success || !data.data.length) {
        list.innerHTML = `<div class="text-center text-white/30 py-12">
            <div class="text-4xl mb-4">🔔</div>
            <p class="text-sm">目前沒有通知</p>
        </div>`;
        return;
    }

    const typeColors = { system: 'blue', reward: 'yellow', social: 'pink', admin: 'red' };
    const typeIcons  = { system: '⚙️', reward: '🎁', social: '💬', admin: '🛡️' };

    list.innerHTML = data.data.map(n => {
        const color = typeColors[n.type] || 'blue';
        const icon  = typeIcons[n.type] || '🔔';
        const unread = !n.is_read;
        return `
        <div class="flex gap-4 p-6 rounded-2xl border transition-all cursor-pointer
            ${unread ? 'bg-white/8 border-white/20' : 'bg-white/3 border-white/5 opacity-60'}"
            onclick="markRead(${n.id}, this)">
            <div class="w-10 h-10 rounded-full bg-${color}-500/20 flex items-center justify-center text-xl flex-shrink-0">${icon}</div>
            <div class="flex-1">
                <div class="flex justify-between items-start">
                    <h4 class="font-bold text-sm ${unread ? 'text-white' : 'text-white/60'}">${n.title}</h4>
                    ${unread ? '<span class="w-2 h-2 bg-secondary rounded-full flex-shrink-0 mt-1"></span>' : ''}
                </div>
                <p class="text-white/50 text-xs mt-1">${n.content}</p>
                <p class="text-white/20 text-[10px] mt-2">${n.created_at}</p>
            </div>
        </div>`;
    }).join('');
}

async function markRead(id, el) {
    const fd = new FormData();
    fd.append('csrf_token', csrfToken);
    fd.append('action', 'mark_read');
    fd.append('id', id);
    await fetch('../auth/notification_handler.php', { method: 'POST', body: fd });
    el.classList.replace('bg-white/8', 'bg-white/3');
    el.classList.replace('border-white/20', 'border-white/5');
    el.classList.add('opacity-60');
    el.querySelector('.bg-secondary')?.remove();
}

document.getElementById('mark-all-read').addEventListener('click', async () => {
    const fd = new FormData();
    fd.append('csrf_token', csrfToken);
    fd.append('action', 'mark_read');
    await fetch('../auth/notification_handler.php', { method: 'POST', body: fd });
    loadNotifications();
});

loadNotifications();
</script>

<?php include '../templates/footer.php'; ?>
