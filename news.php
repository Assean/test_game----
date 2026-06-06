<?php include 'templates/header.php'; ?>

<main class="pt-32 pb-24 px-[5%]">
    <div class="max-w-[1400px] mx-auto">
        <div class="text-center mb-16 animate-fade-in-down">
            <h2 class="text-5xl font-black uppercase tracking-tighter mb-4">
                最新 <span class="text-secondary drop-shadow-[0_0_10px_rgba(0,242,255,0.7)]">資訊</span>
            </h2>
            <p class="text-white/40 uppercase tracking-[0.3em] text-sm">Latest Updates & Reward Announcements</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

            <!-- Column 1: Game & System Updates -->
            <section class="animate-fade-in-up">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-10 h-10 bg-primary/20 rounded-xl flex items-center justify-center text-xl">🎮</div>
                    <h3 class="text-2xl font-black uppercase tracking-widest text-primary">遊戲與系統更新</h3>
                    <div class="h-px flex-1 bg-gradient-to-r from-primary/30 to-transparent"></div>
                </div>

                <div class="space-y-6">
                    <?php
                    // Fetch Game/System news from DB
                    try {
                        $stmt = $pdo->query("SELECT * FROM news WHERE category IN ('System', 'Game') ORDER BY created_at DESC LIMIT 5");
                        $systemNews = $stmt->fetchAll();
                    } catch (Exception $e) {
                        // Fallback dummy data
                        $systemNews = [
                            ['title' => 'V1.5 大型更新：霓虹之影 (Neon Shadows)', 'category' => 'System', 'created_at' => '2026-03-09', 'desc' => '全新地圖「失落之城」正式開放，支援即時戰技系統。'],
                            ['title' => '伺服器維護與效能優化公告', 'category' => 'System', 'created_at' => '2026-03-08', 'desc' => '我們將於 3/10 進行例行維護，預計提升 30% 連線穩定度。'],
                            ['title' => '新增武器：雷鳴長劍 (Thunder Blade)', 'category' => 'Game', 'created_at' => '2026-03-05', 'desc' => '平衡性調整，雷鳴系列武器數值全面強化。']
                        ];
                    }

                    foreach ($systemNews as $item): ?>
                        <div
                            class="group bg-white/5 border border-white/10 rounded-3xl p-6 hover:border-primary transition-all cursor-pointer">
                            <div class="flex justify-between items-start mb-3">
                                <span
                                    class="text-[10px] font-black uppercase px-2 py-1 rounded bg-primary/20 text-primary tracking-widest">
                                    <?php echo htmlspecialchars($item['category']); ?>
                                </span>
                                <span class="text-[10px] text-white/30 font-bold">
                                    <?php echo date('Y/m/d', strtotime($item['created_at'])); ?>
                                </span>
                            </div>
                            <h4 class="text-lg font-bold mb-2 group-hover:text-primary transition-colors">
                                <?php echo htmlspecialchars($item['title']); ?>
                            </h4>
                            <p class="text-xs text-white/40 leading-relaxed mb-4">
                                <?php echo isset($item['desc']) ? htmlspecialchars($item['desc']) : 'Stay ahead of the game with our latest patch notes...'; ?>
                            </p>
                            <a href="#"
                                class="inline-flex items-center gap-2 text-primary font-black text-[10px] uppercase tracking-widest hover:gap-3 transition-all">
                                閱讀全文 <span>→</span>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Column 2: Reward Announcements -->
            <section class="animate-fade-in-up" style="animation-delay: 0.2s">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-10 h-10 bg-accent/20 rounded-xl flex items-center justify-center text-xl">🎁</div>
                    <h3 class="text-2xl font-black uppercase tracking-widest text-accent">獎勵公告</h3>
                    <div class="h-px flex-1 bg-gradient-to-r from-accent/30 to-transparent"></div>
                </div>

                <div class="space-y-6">
                    <?php
                    // Fetch Reward news from DB
                    try {
                        $stmt = $pdo->query("SELECT * FROM news WHERE category = 'Reward' ORDER BY created_at DESC LIMIT 5");
                        $rewardNews = $stmt->fetchAll();
                    } catch (Exception $e) {
                        // Fallback dummy data
                        $rewardNews = [
                            ['title' => '官方活動：限時萬人積分大抽獎！', 'category' => 'Reward', 'created_at' => '2026-03-07', 'desc' => '最高可獲得 10,000 點積分，獎項包含絕版皮膚。'],
                            ['title' => '連續簽到 7 天獎勵發放完畢', 'category' => 'Reward', 'created_at' => '2026-03-04', 'desc' => '請檢查您的信箱領取您的「勇敢者」專屬徽章。'],
                            ['title' => '新手禮包：序號兌換中心正式啟動', 'category' => 'Reward', 'created_at' => '2026-03-01', 'desc' => '輸入序號「WELCOME2026」即可領取 500 點初始積分。']
                        ];
                    }

                    foreach ($rewardNews as $item): ?>
                        <div
                            class="group bg-white/5 border border-white/10 rounded-3xl p-6 hover:border-accent transition-all cursor-pointer">
                            <div class="flex justify-between items-start mb-3">
                                <span
                                    class="text-[10px] font-black uppercase px-2 py-1 rounded bg-accent/20 text-accent tracking-widest">
                                    <?php echo htmlspecialchars($item['category']); ?>
                                </span>
                                <span class="text-[10px] text-white/30 font-bold">
                                    <?php echo date('Y/m/d', strtotime($item['created_at'])); ?>
                                </span>
                            </div>
                            <h4 class="text-lg font-bold mb-2 group-hover:text-accent transition-colors">
                                <?php echo htmlspecialchars($item['title']); ?>
                            </h4>
                            <p class="text-xs text-white/40 leading-relaxed mb-4">
                                <?php echo isset($item['desc']) ? htmlspecialchars($item['desc']) : 'Exclusive rewards and event results are waiting...'; ?>
                            </p>
                            <a href="#"
                                class="inline-flex items-center gap-2 text-accent font-black text-[10px] uppercase tracking-widest hover:gap-3 transition-all">
                                領取獎勵 <span>→</span>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

        </div>

        <!-- Featured / Community Section -->
        <section class="mt-20 p-10 bg-white/[0.02] border border-white/5 rounded-[40px] animate-fade-in-up"
            style="animation-delay: 0.4s">
            <div class="flex flex-col md:flex-row items-center gap-10">
                <div
                    class="w-full md:w-1/3 aspect-video bg-zinc-900 rounded-2xl flex items-center justify-center text-5xl">
                    📸</div>
                <div class="flex-1">
                    <h3 class="text-2xl font-black mb-4">社群集錦：本月最佳創作</h3>
                    <p class="text-white/60 mb-6 leading-relaxed">
                        我們非常驚訝於玩家們在本月投稿的同人画作！快來看看哪些作品獲得了最高人氣，並為您喜歡的內容投下一票。
                    </p>
                    <a href="features/fanart.php" class="btn-neon">前往同人專區</a>
                </div>
            </div>
        </section>
    </div>
</main>

<?php include 'templates/footer.php'; ?>