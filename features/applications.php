<?php
require_once __DIR__ . '/../includes/auth_logic.php';
include '../templates/header.php';

if (!Auth::isLoggedIn()) {
    header("Location: ../login.php");
    exit;
}
?>

<main class="pt-32 pb-24 px-[5%]">
    <div
        class="max-w-[700px] mx-auto bg-white/5 border border-white/10 backdrop-blur-2xl p-10 rounded-[40px] relative overflow-hidden animate-fade-in-up">
        <div class="mb-12">
            <h2 class="text-3xl font-black uppercase tracking-tighter mb-2">計畫 <span class="text-primary">申請項目</span>
            </h2>
            <p class="text-white/40 text-[10px] tracking-[0.3em] uppercase">Staff Recruitment & Testing Opportunities
            </p>
        </div>

        <!-- App Type Selection -->
        <div id="type-selection" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-10">
            <button onclick="startApp('Admin')"
                class="p-6 border border-white/10 bg-white/5 rounded-3xl hover:border-primary transition-all text-left group">
                <div class="text-2xl mb-3 group-hover:scale-110 transition-transform">⚔️</div>
                <h4 class="text-lg font-black mb-1">遊戲管理員</h4>
                <p class="text-[10px] text-white/40">負責核心秩序與違規處理。</p>
            </button>
            <button onclick="startApp('Helper')"
                class="p-6 border border-white/10 bg-white/5 rounded-3xl hover:border-secondary transition-all text-left group">
                <div class="text-2xl mb-3 group-hover:scale-110 transition-transform">🛡️</div>
                <h4 class="text-lg font-black mb-1">社區小幫手</h4>
                <p class="text-[10px] text-white/40">協助玩家解答疑問與維護社群。</p>
            </button>
            <button onclick="startApp('Beta')"
                class="p-6 border border-white/10 bg-white/5 rounded-3xl hover:border-accent transition-all text-left group">
                <div class="text-2xl mb-3 group-hover:scale-110 transition-transform">🧪</div>
                <h4 class="text-lg font-black mb-1">測試先行者</h4>
                <p class="text-[10px] text-white/40">優先試玩並協助抓取 Bug。</p>
            </button>
        </div>

        <form id="app-form" class="hidden space-y-8">
            <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
            <input type="hidden" name="app_type" id="app_type">

            <div id="app-step-1" class="space-y-6 animate-fade-in-up">
                <h3 class="text-lg font-bold border-l-4 border-primary pl-4">基本資料確認</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-white/40 uppercase mb-2">聯絡 Discord</label>
                        <input type="text" value="<?php echo htmlspecialchars($_SESSION['username'] ?? '未登入'); ?>"
                            disabled
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white/40 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-white/40 uppercase mb-2">Roblox ID</label>
                        <input type="text" value="<?php echo htmlspecialchars($_SESSION['roblox_id'] ?? '未綁定'); ?>"
                            disabled
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-3 text-white/40 cursor-not-allowed">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-white/40 uppercase mb-2">為什麼想申請此職位？</label>
                    <textarea required name="reason" rows="4"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 focus:border-primary outline-none transition-all"
                        placeholder="請簡述您的動機..."></textarea>
                </div>
                <button type="submit" class="w-full btn-neon py-5">提交申請資料</button>
            </div>
        </form>

        <div id="app-success" class="hidden text-center py-10 animate-fade-in-up">
            <div class="text-6xl mb-6">✅</div>
            <h3 class="text-2xl font-black mb-4">申請已收悉！</h3>
            <p class="text-white/40 text-sm leading-relaxed mb-8">
                我們的審核團隊將會在 3-5 個工作天內，<br>透過 Discord 與您取得聯繫。
            </p>
            <a href="../index.php"
                class="text-secondary font-black text-xs uppercase tracking-widest hover:underline">返回首頁</a>
        </div>
    </div>
</main>

<script>
    function startApp(type) {
        document.getElementById('type-selection').classList.add('hidden');
        document.getElementById('app-form').classList.remove('hidden');
        document.getElementById('app_type').value = type;
    }

    document.getElementById('app-form').onsubmit = (e) => {
        e.preventDefault();
        document.getElementById('app-form').classList.add('hidden');
        document.getElementById('app-success').classList.remove('hidden');
    };
</script>

<?php include '../templates/footer.php'; ?>