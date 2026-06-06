<?php
require_once __DIR__ . '/../includes/auth_logic.php';
include '../templates/header.php';

if (!Auth::isLoggedIn()) {
    header("Location: ../login.php");
    exit;
}
?>

<main class="pt-32 pb-24 px-[5%]">
    <div class="max-w-[900px] mx-auto">
        <div class="flex justify-between items-center mb-16 animate-fade-in-down">
            <h2 class="text-4xl font-black uppercase tracking-tighter">玩家 <span class="text-pink-400">交流板</span></h2>
            <button onclick="document.getElementById('post-form').classList.toggle('hidden')"
                class="btn-neon py-3 px-8 text-xs bg-pink-500">
                新文章 / 留言
            </button>
        </div>

        <!-- Post Form -->
        <div id="post-form" class="hidden mb-16 p-8 bg-white/5 border border-white/10 rounded-3xl animate-fade-in-up">
            <textarea id="post-content" rows="4"
                class="w-full bg-white/10 border border-white/10 rounded-2xl px-6 py-5 focus:border-pink-400 outline-none transition-all text-sm mb-6"
                placeholder="分享您的戰鬥心得..."></textarea>
            <div class="flex justify-end gap-4">
                <button onclick="document.getElementById('post-form').classList.add('hidden')"
                    class="px-6 py-3 text-xs font-bold text-white/40 uppercase">取消</button>
                <button onclick="submitPost()"
                    class="px-8 py-3 bg-pink-500 text-white text-xs font-black uppercase rounded-full hover:scale-105 transition-all">發佈內容</button>
            </div>
        </div>

        <!-- Posts List -->
        <div class="space-y-8 animate-fade-in-up">
            <?php
            $posts = [
                ['user' => 'Ray', 'content' => '歡迎大家加入新開啟的交流板！期待看到各位的攻略。', 'date' => '2026-03-09 18:20'],
                ['user' => 'Sean', 'content' => '網站終於完成了 10 個核心功能，大家快去商城看看。', 'date' => '2026-03-09 18:15'],
                ['user' => 'ProGamer_X', 'content' => '剛剛在抽獎中抽到了 Neon Legend Skin！太爽了！', 'date' => '2026-03-09 17:50'],
            ];
            foreach ($posts as $p):
                ?>
                <div class="bg-white/5 border border-white/10 rounded-[30px] p-8 hover:bg-white/[0.08] transition-all">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center text-xl">👤</div>
                        <div>
                            <h4 class="font-bold text-sm">
                                <?php echo htmlspecialchars($p['user']); ?>
                            </h4>
                            <p class="text-[10px] text-white/30 font-bold">
                                <?php echo $p['date']; ?>
                            </p>
                        </div>
                    </div>
                    <p class="text-white/70 text-sm leading-relaxed mb-6">
                        <?php echo htmlspecialchars($p['content']); ?>
                    </p>
                    <div class="flex gap-6 mt-4 opacity-50 text-[10px] font-black uppercase tracking-widest">
                        <button class="hover:text-pink-400">👍 讚 (12)</button>
                        <button class="hover:text-secondary">💬 回覆 (3)</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<script>
    function submitPost() {
        const content = document.getElementById('post-content').value;
        if (!content) return;
        alert("內容已發佈！(模擬功能)");
        location.reload();
    }
</script>

<?php include '../templates/footer.php'; ?>