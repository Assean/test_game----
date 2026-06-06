<?php
require_once __DIR__ . '/../includes/auth_logic.php';
include '../templates/header.php';

if (!Auth::isLoggedIn()) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
?>

<main class="pt-32 pb-24 px-[5%]">
    <div class="max-w-[1000px] mx-auto">
        <div class="mb-12 flex justify-between items-end">
            <div>
                <h2 class="text-4xl font-black uppercase tracking-tighter mb-2">客服 <span
                        class="text-secondary">私訊</span></h2>
                <p class="text-white/40 text-xs tracking-[0.3em] uppercase">Private Support Channel</p>
            </div>
            <div
                class="px-4 py-2 bg-secondary/10 border border-secondary/20 rounded-full text-[10px] text-secondary font-black uppercase">
                Status: Secure & Encrypted
            </div>
        </div>

        <div
            class="bg-white/[0.02] border border-white/10 backdrop-blur-3xl rounded-[40px] overflow-hidden flex flex-col h-[600px]">
            <!-- Chat Header -->
            <div class="p-6 border-b border-white/10 bg-white/[0.02] flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-xl">🛡️</div>
                    <div>
                        <h4 class="font-bold">官方管理團隊</h4>
                        <span class="text-[10px] text-green-400 animate-pulse uppercase font-black">Online</span>
                    </div>
                </div>
            </div>

            <!-- Messages Area -->
            <div id="chatBox" class="flex-1 p-8 overflow-y-auto space-y-6 custom-scrollbar">
                <!-- Messages will be loaded here -->
            </div>

            <!-- Input Area -->
            <form id="msgForm" class="p-6 bg-white/[0.02] border-t border-white/10 flex gap-4">
                <input type="text" id="msgInput" required
                    class="flex-1 bg-white/5 border border-white/10 rounded-2xl px-6 py-4 focus:border-secondary outline-none transition-all"
                    placeholder="請輸入您的問題...">
                <button type="submit"
                    class="px-8 py-4 bg-secondary text-black font-black uppercase tracking-widest rounded-2xl hover:scale-105 active:scale-95 transition-all">
                    發送
                </button>
            </form>
        </div>
    </div>
</main>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(0, 242, 255, 0.2);
        border-radius: 10px;
    }

    .msg-bubble {
        max-width: 80%;
        padding: 1.25rem 1.5rem;
        border-radius: 25px;
        position: relative;
    }

    .msg-user {
        background: rgba(255, 255, 255, 0.05);
        border: 1px border white/10;
        align-self: flex-end;
        border-bottom-right-radius: 5px;
    }

    .msg-admin {
        background: rgba(0, 242, 255, 0.1);
        border: 1px border secondary/20;
        align-self: flex-start;
        border-bottom-left-radius: 5px;
    }
</style>

<script>
    const chatBox = document.getElementById('chatBox');
    const msgForm = document.getElementById('msgForm');
    const msgInput = document.getElementById('msgInput');

    async function fetchMessages() {
        try {
            const response = await fetch('../auth/message_handler.php', {
                method: 'POST',
                body: new URLSearchParams({ action: 'fetch' })
            });
            const res = await response.json();
            if (res.success) {
                chatBox.innerHTML = res.data.map(m => `
                <div class="flex flex-col ${m.is_admin_reply == 1 ? 'items-start' : 'items-end'}">
                    <div class="msg-bubble ${m.is_admin_reply == 1 ? 'msg-admin' : 'msg-user'} text-sm leading-relaxed">
                        ${m.content}
                    </div>
                    <span class="text-[8px] text-white/20 mt-2 uppercase tracking-widest px-2">
                        ${m.is_admin_reply == 1 ? 'Admin' : 'You'} • ${new Date(m.created_at).toLocaleTimeString()}
                    </span>
                </div>
            `).join('');
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        } catch (err) { console.error(err); }
    }

    msgForm.onsubmit = async (e) => {
        e.preventDefault();
        const content = msgInput.value;
        if (!content) return;

        try {
            const response = await fetch('../auth/message_handler.php', {
                method: 'POST',
                body: new URLSearchParams({ action: 'send', content: content })
            });
            const res = await response.json();
            if (res.success) {
                msgInput.value = '';
                fetchMessages();
            }
        } catch (err) { console.error(err); }
    };

    // Initial Fetch & Poll
    fetchMessages();
    setInterval(fetchMessages, 5000);
</script>

<?php include '../templates/footer.php'; ?>