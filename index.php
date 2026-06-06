<?php include 'templates/header.php'; ?>

<!-- Dynamic Battle Background -->
<div class="fixed inset-0 z-[-1] bg-[radial-gradient(circle_at_center,#1a0b3b_0%,#05050b_100%)]">
    <div
        class="absolute inset-[-50%] w-[200%] h-[200%] bg-[linear-gradient(rgba(0,242,255,0.1)_1px,transparent_1px),linear-gradient(90deg,rgba(0,242,255,0.1)_1px,transparent_1px)] bg-[size:50px_50px] [transform:perspective(1000px)_rotateX(60deg)] animate-[gridMove_20s_linear_infinite]">
    </div>
</div>

<main>
    <!-- Hero Section -->
    <section id="hero" class="h-screen flex items-center px-[5%] relative overflow-hidden">
        <div class="z-10 max-w-[800px]">
            <span class="text-secondary text-lg mb-5 block animate-fade-in-down">準備好進入戰場了嗎？</span>
            <h1 class="text-[clamp(3rem,8vw,6rem)] leading-[1.1] font-black mb-8 animate-fade-in-left">
                未來的傳說，<br>由你 <span class="text-accent drop-shadow-[0_0_10px_rgba(255,0,127,0.7)]">決戰</span>。
            </h1>
            <p class="text-xl text-white/70 mb-10 animate-fade-in-up">沉浸式 3D 戰鬥體驗，極致視覺饗宴，與全球玩家同台競技。</p>
            <div class="flex gap-5 animate-fade-in-up">
                <a href="#" class="btn-neon">立即下載</a>
                <a href="#about"
                    class="px-10 py-3 border-2 border-primary text-primary rounded-full hover:bg-primary hover:text-white transition-all">探索故事</a>
            </div>
        </div>
    </section>

    <!-- Game Lore Section / About Us -->
    <section id="about" class="py-24 px-[5%] bg-white/5 backdrop-blur-sm border-y border-white/5">
        <div class="max-w-[1400px] mx-auto grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
            <div class="order-2 md:order-1">
                <h2 class="text-4xl font-black mb-8 uppercase leading-tight">
                    起源：<span class="text-secondary">霓虹廢墟</span> 的戰火
                </h2>
                <div class="space-y-6 text-white/70 text-lg leading-relaxed">
                    <p>
                        在 2142 年的世界，曾經繁華的都市已化為廢墟。為了爭奪稀有的「星際核心」能源，各大派系展開了永無止盡的衝突。
                    </p>
                    <p>
                        身為精英戰士，你將踏上這片充滿危險與機會的土地。每一場決鬥都是對意志的考驗，每一次勝利都將提升你的排名與地位。
                    </p>
                    <div class="pt-6 border-t border-white/10 italic">
                        "在黑暗中，只有最強者才能看見黎明。"
                    </div>
                </div>
            </div>
            <div class="order-1 md:order-2">
                <div class="relative group">
                    <div
                        class="absolute -inset-4 bg-gradient-to-r from-primary to-accent opacity-20 blur-2xl group-hover:opacity-40 transition-opacity">
                    </div>
                    <img src="assets/img/lore.png" alt="Game Lore Art"
                        class="relative rounded-3xl border border-white/10 shadow-2xl hover:scale-[1.02] transition-transform duration-500">
                </div>
            </div>
        </div>
    </section>

    <!-- Detailed Features -->
    <section id="features" class="py-24 px-[5%]">
        <div class="max-w-[1400px] mx-auto">
            <h2 class="text-center text-[clamp(2rem,5vw,3rem)] font-black mb-20 uppercase">
                極致 <span class="text-accent">遊戲特色</span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div
                    class="p-8 border border-white/5 bg-white/[0.02] rounded-3xl hover:bg-white/[0.05] transition-all group">
                    <div class="text-secondary text-4xl mb-6 group-hover:scale-110 transition-transform">⚡</div>
                    <h4 class="text-2xl font-bold mb-4">流暢戰鬥系統</h4>
                    <p class="text-white/60">針對 Roblox 引擎優化的物理反饋，讓每一次揮擊與技能釋放都感到充滿震撼力。</p>
                </div>
                <div
                    class="p-8 border border-white/5 bg-white/[0.02] rounded-3xl hover:bg-white/[0.05] transition-all group">
                    <div class="text-secondary text-4xl mb-6 group-hover:scale-110 transition-transform">🌍</div>
                    <h4 class="text-2xl font-bold mb-4">全球即時競技</h4>
                    <p class="text-white/60">低延遲伺服器架構，支援數百名玩家在同一個開放世界中進行大規模混戰。</p>
                </div>
                <div
                    class="p-8 border border-white/5 bg-white/[0.02] rounded-3xl hover:bg-white/[0.05] transition-all group">
                    <div class="text-secondary text-4xl mb-6 group-hover:scale-110 transition-transform">💎</div>
                    <h4 class="text-2xl font-bold mb-4">深度角色自定義</h4>
                    <p class="text-white/60">上千種裝備與技能組合，打造屬於你獨一無二的戰鬥風格與外觀。</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Development Team Section -->
    <section id="team"
        class="py-32 px-[5%] bg-[radial-gradient(circle_at_top,rgba(138,43,226,0.05)_0%,transparent_70%)] relative overflow-hidden">
        <!-- Decorative Elements -->
        <div class="absolute top-1/2 left-0 w-64 h-64 bg-primary/10 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-secondary/5 blur-[150px] rounded-full"></div>

        <div class="max-w-[1400px] mx-auto relative z-10">
            <div class="text-center mb-20 animate-fade-in-up">
                <h2 class="text-[clamp(2.5rem,6vw,4rem)] font-black uppercase leading-none mb-4">
                    核心 <span
                        class="bg-gradient-to-r from-primary via-secondary to-accent bg-clip-text text-transparent">開發團隊</span>
                </h2>
                <div class="w-24 h-1.5 bg-gradient-to-r from-primary to-secondary mx-auto rounded-full"></div>
                <p class="text-white/50 mt-8 max-w-2xl mx-auto text-lg">
                    由頂尖的 Roblox 開發者與網頁工程師組成，致力於打造最極致的戰鬥遊戲體驗。
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 max-w-5xl mx-auto">
                <!-- Ray - Lead Dev -->
                <div
                    class="group relative p-1 bg-gradient-to-br from-white/10 to-transparent rounded-[40px] transition-all duration-500 hover:scale-[1.02]">
                    <div
                        class="bg-bg-dark/80 backdrop-blur-2xl rounded-[39px] p-10 h-full flex flex-col items-center border border-white/5 group-hover:border-primary/30 transition-colors">
                        <div class="relative mb-8">
                            <div
                                class="absolute -inset-2 bg-gradient-to-r from-primary to-accent rounded-full opacity-20 blur-xl group-hover:opacity-60 transition-opacity">
                            </div>
                            <div
                                class="w-40 h-40 rounded-full border-2 border-primary/50 overflow-hidden relative z-10 shadow-[0_0_30px_rgba(138,43,226,0.4)]">
                                <img src="assets/img/ray.png" alt="Ray"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            </div>
                            <div
                                class="absolute -bottom-2 -right-2 bg-primary text-white text-xs font-black px-4 py-1.5 rounded-full shadow-lg z-20 uppercase tracking-tighter">
                                Founder</div>
                        </div>

                        <h3 class="text-3xl font-black mb-2 tracking-tight">Ray</h3>
                        <p class="text-primary font-bold text-sm uppercase tracking-[0.2em] mb-6">Lead Game Developer
                        </p>

                        <div class="flex gap-3 mb-8">
                            <span
                                class="px-3 py-1 bg-white/5 border border-white/10 rounded-md text-[10px] text-white/40 uppercase font-black">Luau</span>
                            <span
                                class="px-3 py-1 bg-white/5 border border-white/10 rounded-md text-[10px] text-white/40 uppercase font-black">Game
                                Design</span>
                            <span
                                class="px-3 py-1 bg-white/5 border border-white/10 rounded-md text-[10px] text-white/40 uppercase font-black">Project
                                Mgmt</span>
                        </div>

                        <div class="flex gap-4">
                            <a href="#"
                                class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-primary transition-colors border border-white/10">
                                <span class="text-lg">💬</span>
                            </a>
                            <a href="#"
                                class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-primary transition-colors border border-white/10">
                                <span class="text-lg">🐦</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Sean - Web Dev -->
                <div
                    class="group relative p-1 bg-gradient-to-br from-white/10 to-transparent rounded-[40px] transition-all duration-500 hover:scale-[1.02]">
                    <div
                        class="bg-bg-dark/80 backdrop-blur-2xl rounded-[39px] p-10 h-full flex flex-col items-center border border-white/5 group-hover:border-secondary/30 transition-colors">
                        <div class="relative mb-8">
                            <div
                                class="absolute -inset-2 bg-gradient-to-r from-secondary to-primary rounded-full opacity-20 blur-xl group-hover:opacity-60 transition-opacity">
                            </div>
                            <div
                                class="w-40 h-40 rounded-full border-2 border-secondary/50 overflow-hidden relative z-10 shadow-[0_0_30px_rgba(0,242,255,0.4)]">
                                <img src="assets/img/sean.png" alt="Sean"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            </div>
                            <div
                                class="absolute -bottom-2 -right-2 bg-secondary text-black text-xs font-black px-4 py-1.5 rounded-full shadow-lg z-20 uppercase tracking-tighter">
                                Engineer</div>
                        </div>

                        <h3 class="text-3xl font-black mb-2 tracking-tight">Sean</h3>
                        <p class="text-secondary font-bold text-sm uppercase tracking-[0.2em] mb-6">Full-Stack Web
                            Developer</p>

                        <div class="flex gap-3 mb-8">
                            <span
                                class="px-3 py-1 bg-white/5 border border-white/10 rounded-md text-[10px] text-white/40 uppercase font-black">PHP
                                / SQL</span>
                            <span
                                class="px-3 py-1 bg-white/5 border border-white/10 rounded-md text-[10px] text-white/40 uppercase font-black">Node.js</span>
                            <span
                                class="px-3 py-1 bg-white/5 border border-white/10 rounded-md text-[10px] text-white/40 uppercase font-black">UI/UX</span>
                        </div>

                        <div class="flex gap-4">
                            <a href="#"
                                class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-secondary hover:text-black transition-all border border-white/10">
                                <span class="text-lg">🌐</span>
                            </a>
                            <a href="#"
                                class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-secondary hover:text-black transition-all border border-white/10">
                                <span class="text-lg">🐙</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Characters Section -->
    <section id="characters" class="py-24 px-[5%] bg-[rgba(138,43,226,0.02)]">
        <div class="max-w-[1400px] mx-auto">
            <h2 class="text-center text-[clamp(2rem,5vw,3.5rem)] font-black mb-20 uppercase">
                選取你的 <span class="text-secondary">精英</span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <?php
                $chars = [
                    ['name' => '暗影祭司', 'role' => '魔法 / 爆發', 'icon' => '🔮', 'stat1' => '9/10', 'stat2' => '4/10'],
                    ['name' => '星際勇者', 'role' => '戰士 / 均衡', 'icon' => '⚔️', 'stat1' => '7/10', 'stat2' => '8/10'],
                    ['name' => '鋼鐵之盾', 'role' => '坦克 / 防禦', 'icon' => '🛡️', 'stat1' => '4/10', 'stat2' => '10/10']
                ];
                foreach ($chars as $c): ?>
                    <div
                        class="bg-white/5 border border-white/10 backdrop-blur-md rounded-3xl p-12 text-center transition-all hover:-translate-y-5 hover:border-secondary hover:shadow-[0_0_25px_rgba(0,242,255,0.4)]">
                        <div
                            class="w-[150px] h-[150px] mx-auto mb-8 bg-zinc-900 rounded-full border-2 border-secondary flex items-center justify-center text-6xl shadow-[0_0_15px_rgba(0,242,255,0.5)]">
                            <?php echo $c['icon']; ?>
                        </div>
                        <h3 class="text-3xl font-black mb-3"><?php echo $c['name']; ?></h3>
                        <span class="text-accent font-extrabold mb-5 block"><?php echo $c['role']; ?></span>
                        <div class="flex justify-center gap-5 text-sm text-white/60">
                            <span>攻擊: <?php echo $c['stat1']; ?></span>
                            <span>生存: <?php echo $c['stat2']; ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-24 px-[5%]">
        <div class="max-w-[900px] mx-auto">
            <h2 class="text-4xl font-black mb-16 text-center uppercase">玩家 <span class="text-secondary">常見問題</span></h2>
            <div class="space-y-4">
                <details class="group bg-white/5 rounded-2xl border border-white/10 overflow-hidden" open>
                    <summary
                        class="p-6 cursor-pointer font-bold list-none flex justify-between items-center group-open:bg-white/10 transition-all">
                        遊戲是免費的嗎？
                        <span class="text-secondary transition-transform group-open:rotate-180">▼</span>
                    </summary>
                    <div class="p-6 text-white/60 border-t border-white/10">
                        是的，TEST GAME 在 Roblox 平台上完全免費。我們提供一些外觀加成與特殊道具的內購選項。
                    </div>
                </details>
                <details class="group bg-white/5 rounded-2xl border border-white/10 overflow-hidden">
                    <summary
                        class="p-6 cursor-pointer font-bold list-none flex justify-between items-center group-open:bg-white/10 transition-all">
                        如何獲得專屬代碼？
                        <span class="text-secondary transition-transform group-open:rotate-180">▼</span>
                    </summary>
                    <div class="p-6 text-white/60 border-t border-white/10">
                        您可以關注我們的 Discord 頻道或是關注官方網站、頻道 以獲取最新發放的限時虛擬貨幣代碼。
                    </div>
                </details>
                <details class="group bg-white/5 rounded-2xl border border-white/10 overflow-hidden">
                    <summary
                        class="p-6 cursor-pointer font-bold list-none flex justify-between items-center group-open:bg-white/10 transition-all">
                        官網綁定 Roblox 帳號有什麼好處？
                        <span class="text-secondary transition-transform group-open:rotate-180">▼</span>
                    </summary>
                    <div class="p-6 text-white/60 border-t border-white/10">
                        綁定後您可以直接在官網查看您的遊戲數據、參與官網限定活動，並能在未來獲得專屬的官網回饋禮包。
                    </div>
                </details>
            </div>
        </div>
    </section>

    <!-- Contact Section (New Feature) -->
    <section id="contact" class="py-32 px-[5%] relative overflow-hidden">
        <!-- Section Background Decor -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-[radial-gradient(circle_at_center,rgba(0,242,255,0.05)_0%,transparent_70%)] pointer-events-none"></div>
        
        <div class="max-w-[1400px] mx-auto relative z-10">
            <div class="text-center mb-20">
                <h2 class="text-5xl font-black uppercase mb-4 tracking-tighter italic">Connect With <span class="text-secondary">Us</span></h2>
                <p class="text-white/30 text-xs font-black uppercase tracking-[0.5em]">24/7 Official Support Channels</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- LINE 卡片 -->
                <a href="https://lin.ee/sIO0Bvo" target="_blank" class="group relative p-8 bg-white/5 border border-white/10 rounded-[32px] overflow-hidden hover:border-[#06C755]/50 transition-all duration-500">
                    <div class="absolute bottom-0 left-0 h-1 w-full bg-[#06C755] scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-500"></div>
                    <div class="text-4xl mb-6 grayscale group-hover:grayscale-0 transition-all">💬</div>
                    <h4 class="text-xl font-black mb-2 uppercase tracking-wide">官方 LINE</h4>
                    <p class="text-white/40 text-xs font-bold leading-relaxed mb-4">最直接的即時客服，處理帳務與申訴。</p>
                    <span class="text-[#06C755] text-[10px] font-black uppercase tracking-widest">Add Friend →</span>
                </a>

                <!-- Discord 卡片 -->
                <a href="https://discord.gg/pGUkdvNv" target="_blank" class="group relative p-8 bg-white/5 border border-white/10 rounded-[32px] overflow-hidden hover:border-[#5865F2]/50 transition-all duration-500">
                    <div class="absolute bottom-0 left-0 h-1 w-full bg-[#5865F2] scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-500"></div>
                    <div class="text-4xl mb-6 grayscale group-hover:grayscale-0 transition-all">📡</div>
                    <h4 class="text-xl font-black mb-2 uppercase tracking-wide">Discord 社群</h4>
                    <p class="text-white/40 text-xs font-bold leading-relaxed mb-4">開發進度追蹤與玩家交流中心。</p>
                    <span class="text-[#5865F2] text-[10px] font-black uppercase tracking-widest">Join Server →</span>
                </a>

                <!-- Support Email 卡片 -->
                <a href="mailto:seanpage008166@gmail.com" class="group relative p-8 bg-white/5 border border-white/10 rounded-[32px] overflow-hidden hover:border-primary/50 transition-all duration-500">
                    <div class="absolute bottom-0 left-0 h-1 w-full bg-primary scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-500"></div>
                    <div class="text-4xl mb-6 grayscale group-hover:grayscale-0 transition-all">🛠️</div>
                    <h4 class="text-xl font-black mb-2 uppercase tracking-wide">技術支援</h4>
                    <p class="text-white/40 text-xs font-bold leading-relaxed mb-4">回報 Bug 或連線異常等技術問題。</p>
                    <span class="text-primary text-[10px] font-black uppercase tracking-widest">seanpage008166@gmail.com</span>
                </a>

                <!-- Biz 卡片 -->
                <a href="mailto:seanpage008166@gmail.com" class="group relative p-8 bg-white/5 border border-white/10 rounded-[32px] overflow-hidden hover:border-accent/50 transition-all duration-500">
                    <div class="absolute bottom-0 left-0 h-1 w-full bg-accent scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-500"></div>
                    <div class="text-4xl mb-6 grayscale group-hover:grayscale-0 transition-all">👜</div>
                    <h4 class="text-xl font-black mb-2 uppercase tracking-wide">商務合作</h4>
                    <p class="text-white/40 text-xs font-bold leading-relaxed mb-4">媒體洽詢、廣告合作與異業聯盟。</p>
                    <span class="text-accent text-[10px] font-black uppercase tracking-widest">seanpage008166@gmail.com</span>
                </a>
            </div>
        </div>
    </section>
</main>

<style>
    @keyframes gridMove {
        0% {
            transform: perspective(1000px) rotateX(60deg) translateY(0);
        }

        100% {
            transform: perspective(1000px) rotateX(60deg) translateY(50px);
        }
    }
</style>

<?php include 'templates/footer.php'; ?>