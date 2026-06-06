<?php include 'templates/header.php'; ?>

<main class="pt-32 pb-24 px-[5%]">
    <div class="max-w-[1200px] mx-auto">
        <div class="text-center mb-24 animate-fade-in-down">
            <h2 class="text-5xl font-black uppercase tracking-tighter mb-4">
                全方位 <span class="text-secondary drop-shadow-[0_0_15px_rgba(0,242,255,0.7)]">功能中心</span>
            </h2>
            <p class="text-white/40 uppercase tracking-[0.3em] text-sm">32 Advanced Features categorized for your
                convenience</p>
        </div>

        <!-- Section 1: Rewards & Economy -->
        <section class="mb-20">
            <div class="flex items-center gap-4 mb-10">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-secondary/20"></div>
                <h3 class="text-2xl font-black uppercase tracking-widest text-secondary flex items-center gap-3">
                    <span class="text-3xl">💎</span> 獎勵與貿易 <span class="text-xs font-normal opacity-50 ml-2">Rewards &
                        Economy</span>
                </h3>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-secondary/20"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Points Shop -->
                <a href="features/shop.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-secondary transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        🛒</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-secondary/20 rounded-xl flex items-center justify-center text-xl">🛒
                        </div>
                        <div>
                            <h4 class="font-bold">積分兌換商城</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Points Exchange</p>
                        </div>
                    </div>
                </a>
                <!-- Lucky Draw -->
                <a href="features/luckydraw.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-accent transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        🎰</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-accent/20 rounded-xl flex items-center justify-center text-xl">🎰</div>
                        <div>
                            <h4 class="font-bold">萬人抽獎活動</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Spin & Win</p>
                        </div>
                    </div>
                </a>
                <!-- Trade Center -->
                <a href="features/trade.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-secondary transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        🤝</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-secondary/20 rounded-xl flex items-center justify-center text-xl">🤝
                        </div>
                        <div>
                            <h4 class="font-bold">貿易中心</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">P2P Trading</p>
                        </div>
                    </div>
                </a>
                <!-- Auction House -->
                <a href="features/auction.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-accent transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        ⚖️</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-accent/20 rounded-xl flex items-center justify-center text-xl">⚖️</div>
                        <div>
                            <h4 class="font-bold">極致拍賣行</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Global Auction</p>
                        </div>
                    </div>
                </a>
                <!-- VIP System -->
                <a href="features/vip.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-orange-400 transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        👑</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-orange-400/20 rounded-xl flex items-center justify-center text-xl">👑
                        </div>
                        <div>
                            <h4 class="font-bold">尊爵 VIP 系統</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">VIP Privilege</p>
                        </div>
                    </div>
                </a>
                <!-- Gift Hub -->
                <a href="features/gifts.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-accent transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        🎁</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-accent/20 rounded-xl flex items-center justify-center text-xl">🎁</div>
                        <div>
                            <h4 class="font-bold">禮物中心</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Social Gifting</p>
                        </div>
                    </div>
                </a>
                <!-- Redeem Code -->
                <a href="features/redeem.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-yellow-400 transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        🎫</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-yellow-400/20 rounded-xl flex items-center justify-center text-xl">🎫
                        </div>
                        <div>
                            <h4 class="font-bold">序號兌換中心</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Code Redemption</p>
                        </div>
                    </div>
                </a>
            </div>
        </section>

        <!-- Section 2: Adventure & Character -->
        <section class="mb-20 animate-fade-in-up" style="animation-delay: 0.2s">
            <div class="flex items-center gap-4 mb-10">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-primary/20"></div>
                <h3 class="text-2xl font-black uppercase tracking-widest text-primary flex items-center gap-3">
                    <span class="text-3xl">⚔️</span> 冒險與角色 <span class="text-xs font-normal opacity-50 ml-2">Adventure &
                        Growth</span>
                </h3>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-primary/20"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Daily Quests -->
                <a href="features/quests.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-primary transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        ⚔️</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-primary/20 rounded-xl flex items-center justify-center text-xl">⚔️
                        </div>
                        <div>
                            <h4 class="font-bold">每日冒險任務</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Daily Quests</p>
                        </div>
                    </div>
                </a>
                <!-- Battle Pass -->
                <a href="features/pass.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-yellow-400 transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        🎗️</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-yellow-400/20 rounded-xl flex items-center justify-center text-xl">🎗️
                        </div>
                        <div>
                            <h4 class="font-bold">戰鬥通行證</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Season Pass</p>
                        </div>
                    </div>
                </a>
                <!-- World Map -->
                <a href="features/map.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-green-400 transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        🗺️</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-400/20 rounded-xl flex items-center justify-center text-xl">🗺️
                        </div>
                        <div>
                            <h4 class="font-bold">世界全圖</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Interactive Map</p>
                        </div>
                    </div>
                </a>
                <!-- Boss Timer -->
                <a href="features/boss.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-red-500 transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        ⏱️</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-red-500/20 rounded-xl flex items-center justify-center text-xl">⏱️
                        </div>
                        <div>
                            <h4 class="font-bold">首領計時器</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Boss Timers</p>
                        </div>
                    </div>
                </a>
                <!-- Guilds -->
                <a href="features/guilds.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-primary transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        🛡️</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-primary/20 rounded-xl flex items-center justify-center text-xl">🛡️
                        </div>
                        <div>
                            <h4 class="font-bold">公會大廳</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Guild System</p>
                        </div>
                    </div>
                </a>
                <!-- Pets -->
                <a href="features/pets.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-secondary transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        🐉</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-secondary/20 rounded-xl flex items-center justify-center text-xl">🐉
                        </div>
                        <div>
                            <h4 class="font-bold">寵物藝廊</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Pet Gallery</p>
                        </div>
                    </div>
                </a>
                <!-- Skills -->
                <a href="features/skills.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-indigo-400 transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        🧪</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-indigo-400/20 rounded-xl flex items-center justify-center text-xl">🧪
                        </div>
                        <div>
                            <h4 class="font-bold">技能模擬器</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Skill Simulator</p>
                        </div>
                    </div>
                </a>
                <!-- Inventory -->
                <a href="features/inventory.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-teal-400 transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        🎒</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-teal-400/20 rounded-xl flex items-center justify-center text-xl">🎒
                        </div>
                        <div>
                            <h4 class="font-bold">背包模擬器</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Inventory Check</p>
                        </div>
                    </div>
                </a>
                <!-- Crafting -->
                <a href="features/crafting.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-yellow-600 transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        ⚒️</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-yellow-600/20 rounded-xl flex items-center justify-center text-xl">⚒️
                        </div>
                        <div>
                            <h4 class="font-bold">製作手冊</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Crafting Guide</p>
                        </div>
                    </div>
                </a>
                <!-- Check-in -->
                <a href="features/checkin.php"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-primary transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        📅</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-primary/20 rounded-xl flex items-center justify-center text-xl">📅
                        </div>
                        <div>
                            <h4 class="font-bold">每日簽到系統</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Daily Reward</p>
                        </div>
                    </div>
                </a>
                <!-- Achievements -->
                <a href="features/achievements.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-indigo-400 transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        🏆</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-indigo-400/20 rounded-xl flex items-center justify-center text-xl">🏆
                        </div>
                        <div>
                            <h4 class="font-bold">成就與獎章</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Mastery Path</p>
                        </div>
                    </div>
                </a>
                <!-- Housing -->
                <a href="features/housing.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-orange-400 transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        🏠</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-orange-400/20 rounded-xl flex items-center justify-center text-xl">🏠
                        </div>
                        <div>
                            <h4 class="font-bold">家園預覽</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Private Space</p>
                        </div>
                    </div>
                </a>
            </div>
        </section>

        <!-- Section 3: Community & Social -->
        <section class="mb-20 animate-fade-in-up" style="animation-delay: 0.4s">
            <div class="flex items-center gap-4 mb-10">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-pink-400/20"></div>
                <h3 class="text-2xl font-black uppercase tracking-widest text-pink-400 flex items-center gap-3">
                    <span class="text-3xl">✨</span> 玩家社群 <span class="text-xs font-normal opacity-50 ml-2">Community &
                        Social</span>
                </h3>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-pink-400/20"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Fan Art -->
                <a href="features/fanart.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-pink-400 transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        🎨</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-pink-400/20 rounded-xl flex items-center justify-center text-xl">🎨
                        </div>
                        <div>
                            <h4 class="font-bold">同人投稿專區</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Creation Hub</p>
                        </div>
                    </div>
                </a>
                <!-- Polls -->
                <a href="features/polls.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-secondary transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        🗳️</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-secondary/20 rounded-xl flex items-center justify-center text-xl">🗳️
                        </div>
                        <div>
                            <h4 class="font-bold">社群投票</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Voting System</p>
                        </div>
                    </div>
                </a>
                <!-- Community Board -->
                <a href="features/community.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-pink-400 transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        💬</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-pink-400/20 rounded-xl flex items-center justify-center text-xl">💬
                        </div>
                        <div>
                            <h4 class="font-bold">交流討論板</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Forum</p>
                        </div>
                    </div>
                </a>
                <!-- Music Room -->
                <a href="features/music.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-primary transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        🎵</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-primary/20 rounded-xl flex items-center justify-center text-xl">🎵
                        </div>
                        <div>
                            <h4 class="font-bold">音樂空間</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">OST Library</p>
                        </div>
                    </div>
                </a>
                <!-- Leaderboard -->
                <a href="features/leaderboard.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-secondary transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        📊</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-secondary/20 rounded-xl flex items-center justify-center text-xl">📊
                        </div>
                        <div>
                            <h4 class="font-bold">競爭排行榜</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Rankings</p>
                        </div>
                    </div>
                </a>
                <!-- PvP Stats -->
                <a href="features/pk_stats.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-red-400 transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        ⚔️</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-red-400/20 rounded-xl flex items-center justify-center text-xl">⚔️
                        </div>
                        <div>
                            <h4 class="font-bold">PvP 數據統計</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Global PK Records</p>
                        </div>
                    </div>
                </a>
            </div>
        </section>

        <!-- Section 4: Info & Support -->
        <section class="mb-20 animate-fade-in-up" style="animation-delay: 0.6s">
            <div class="flex items-center gap-4 mb-10">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-white/10"></div>
                <h3 class="text-2xl font-black uppercase tracking-widest text-white/80 flex items-center gap-3">
                    <span class="text-3xl">📚</span> 資訊與支援 <span class="text-xs font-normal opacity-50 ml-2">Info &
                        Support</span>
                </h3>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-white/10"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Wiki -->
                <a href="features/wiki.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-indigo-400 transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        📚</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-indigo-400/20 rounded-xl flex items-center justify-center text-xl">📚
                        </div>
                        <div>
                            <h4 class="font-bold">遊戲百科</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Game Wiki</p>
                        </div>
                    </div>
                </a>
                <!-- Dev Blog -->
                <a href="features/devblog.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-primary transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        📝</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-primary/20 rounded-xl flex items-center justify-center text-xl">📝
                        </div>
                        <div>
                            <h4 class="font-bold">開發日誌</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Latest Updates</p>
                        </div>
                    </div>
                </a>
                <!-- Gallery -->
                <a href="features/gallery.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-secondary transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        🖼️</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-secondary/20 rounded-xl flex items-center justify-center text-xl">🖼️
                        </div>
                        <div>
                            <h4 class="font-bold">影音藝廊</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Wallpapers & Videos</p>
                        </div>
                    </div>
                </a>
                <!-- Server Status -->
                <a href="features/status.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-green-400 transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        ⚡</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-400/20 rounded-xl flex items-center justify-center text-xl">⚡
                        </div>
                        <div>
                            <h4 class="font-bold">伺服器狀態</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Online Monitor</p>
                        </div>
                    </div>
                </a>
                <!-- Support Center -->
                <a href="features/support.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-secondary transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        🆘</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-secondary/20 rounded-xl flex items-center justify-center text-xl">🆘
                        </div>
                        <div>
                            <h4 class="font-bold">支援中心</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Customer Support</p>
                        </div>
                    </div>
                </a>
                <!-- Bug Tracker -->
                <a href="features/bug_report.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-red-400 transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        🐛</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-red-400/20 rounded-xl flex items-center justify-center text-xl">🐛
                        </div>
                        <div>
                            <h4 class="font-bold">臭蟲與建議回報</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Feedback System</p>
                        </div>
                    </div>
                </a>
                <!-- Applications -->
                <a href="features/applications.php" onclick="Utils.comingSoon(event)"
                    class="group p-6 bg-white/5 border border-white/10 rounded-[30px] hover:border-primary transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-6 -bottom-6 opacity-10 text-6xl group-hover:scale-125 transition-transform">
                        🛡️</div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 bg-primary/20 rounded-xl flex items-center justify-center text-xl">🛡️
                        </div>
                        <div>
                            <h4 class="font-bold">申請項目</h4>
                            <p class="text-[10px] text-white/40 uppercase mt-1">Join Our Team</p>
                        </div>
                    </div>
                </a>
            </div>
        </section>

    </div>
</main>

<style>
    @keyframes fade-in-down {
        0% {
            opacity: 0;
            transform: translateY(-20px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fade-in-up {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-down {
        animation: fade-in-down 0.8s ease-out forwards;
    }

    .animate-fade-in-up {
        animation: fade-in-up 0.8s ease-out forwards;
    }
</style>

<?php include 'templates/footer.php'; ?>