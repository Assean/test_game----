<footer class="py-20 border-t border-white/10 bg-[#020205] relative overflow-hidden">
    <!-- Footer Glow -->
    <div
        class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[1px] bg-gradient-to-r from-transparent via-secondary/50 to-transparent">
    </div>

    <div class="max-w-[1400px] mx-auto px-5">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 items-center text-center md:text-left mb-16">
            <!-- Brand -->
            <div>
                <div class="text-[2rem] font-black mb-4">
                    TEST <span class="text-secondary">GAME</span>
                </div>
                <p class="text-white/40 text-sm max-w-[300px] mx-auto md:mx-0">
                    致力於打造 Roblox 平台上最具震撼力的 3D 戰鬥體驗，結合極致視覺與流暢玩法。
                </p>
            </div>

            <!-- Dev & Tech -->
            <div class="text-center">
                <h4 class="text-xs font-black text-white/30 uppercase tracking-[0.3em] mb-6">開發與技術庫</h4>
                <div class="flex justify-center gap-6 mb-4">
                    <div class="group cursor-help relative">
                        <span class="text-2xl opacity-50 group-hover:opacity-100 transition-opacity">🐘</span>
                        <div
                            class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1 bg-white/10 backdrop-blur-md rounded text-[10px] opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-[1000]">
                            PHP 8.x</div>
                    </div>
                    <div class="group cursor-help relative">
                        <span class="text-2xl opacity-50 group-hover:opacity-100 transition-opacity">🌊</span>
                        <div
                            class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1 bg-white/10 backdrop-blur-md rounded text-[10px] opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-[1000]">
                            Tailwind CSS</div>
                    </div>
                    <div class="group cursor-help relative">
                        <span class="text-2xl opacity-50 group-hover:opacity-100 transition-opacity">🤖</span>
                        <div
                            class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1 bg-white/10 backdrop-blur-md rounded text-[10px] opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-[1000]">
                            Roblox Engine</div>
                    </div>
                </div>
                <div class="flex flex-col items-center gap-3">
                    <p class="text-xs text-white/50">Developed by <span class="text-primary font-bold">Ray</span> &
                        <span class="text-secondary font-bold">Sean</span></p>
                    <button onclick="document.getElementById('devLogModal').classList.remove('hidden')"
                        class="text-[10px] px-3 py-1 bg-primary/10 border border-primary/20 rounded-full text-primary hover:bg-primary/20 transition-all font-black uppercase tracking-widest">
                        開發日誌 <span class="ml-1">📜</span>
                    </button>
                </div>
            </div>

            <!-- Dev Log Modal -->
            <div id="devLogModal" class="fixed inset-0 z-[999] hidden flex items-center justify-center px-5">
                <div class="absolute inset-0 bg-black/80 backdrop-blur-sm"
                    onclick="this.parentElement.classList.add('hidden')"></div>
                <div
                    class="relative w-full max-w-[600px] bg-[#0a0a0f] border border-white/10 rounded-[40px] shadow-2xl overflow-hidden animate-fade-in-up">
                    <div class="p-8 border-b border-white/10 flex justify-between items-center">
                        <div>
                            <h3 class="text-2xl font-black uppercase tracking-tighter">開發 <span
                                    class="text-primary">日誌</span></h3>
                            <p class="text-white/30 text-[10px] uppercase tracking-widest">Latest Updates & Development
                                Progress</p>
                        </div>
                        <button onclick="document.getElementById('devLogModal').classList.add('hidden')"
                            class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-white/10 transition-colors">
                            <span class="text-xl">✕</span>
                        </button>
                    </div>
                    <div class="p-8 max-h-[60vh] overflow-y-auto space-y-8 custom-scrollbar">
                        <?php
                        require_once __DIR__ . '/../includes/dev_logs.php';
                        $logs = getDevLogs();
                        foreach ($logs as $log): ?>
                            <div class="relative pl-8 border-l border-white/10">
                                <div
                                    class="absolute left-[-5px] top-0 w-2.5 h-2.5 rounded-full <?php echo $log['status'] === 'new' ? 'bg-primary shadow-[0_0_8px_#8a2be2]' : 'bg-white/20'; ?>">
                                </div>
                                <div class="flex items-center gap-3 mb-2">
                                    <span
                                        class="text-[10px] font-black text-white/30 uppercase"><?php echo $log['date']; ?></span>
                                    <span
                                        class="px-2 py-0.5 bg-white/5 rounded text-[8px] text-white/40 uppercase font-black"><?php echo $log['tag']; ?></span>
                                </div>
                                <h4 class="text-lg font-bold mb-2"><?php echo $log['title']; ?></h4>
                                <p class="text-sm text-white/50 leading-relaxed"><?php echo $log['content']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="p-6 bg-white/[0.02] text-center">
                        <a href="<?php echo $prefix ?? ''; ?>features/devblog.php"
                            class="text-xs font-black text-primary hover:underline uppercase tracking-widest">查看完整日誌庫
                            →</a>
                    </div>
                </div>
            </div>

            <style>
                .custom-scrollbar::-webkit-scrollbar {
                    width: 4px;
                }

                .custom-scrollbar::-webkit-scrollbar-track {
                    background: rgba(255, 255, 255, 0.02);
                }

                .custom-scrollbar::-webkit-scrollbar-thumb {
                    background: rgba(255, 255, 255, 0.1);
                    border-radius: 10px;
                }

                .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                    background: rgba(255, 255, 255, 0.2);
                }
            </style>

            <!-- Links -->
            <div class="flex flex-col md:items-end gap-3 text-sm">
                <div class="flex flex-wrap justify-center md:justify-end gap-6 mb-2">
                    <a href="#" class="text-white/60 hover:text-white transition-colors">服務條款</a>
                    <a href="<?php echo $prefix ?? ''; ?>privacy_policy.php"
                        class="text-white/60 hover:text-white transition-colors">隱私權政策</a>
                    <a href="https://lin.ee/sIO0Bvo" target="_blank"
                        class="text-secondary font-bold hover:text-white transition-colors flex items-center gap-1">
                        <span class="text-xs">💬</span> 官方 LINE
                    </a>
                    <a href="<?php echo $prefix ?? ''; ?>feedback.php"
                        class="text-white/60 hover:text-white transition-colors">聯繫我們</a>
                </div>
                <a href="javascript:void(0)" onclick="triggerAdminTransition()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white/5 border border-white/10 rounded-full text-xs text-white/40 hover:text-white hover:bg-white/10 transition-all group">
                    <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
                    管理員系統入口
                </a>
            </div>
        </div>

        <div class="pt-8 border-t border-white/5 text-center">
            <p class="text-[10px] text-white/20 tracking-[0.2em] uppercase">
                &copy; 2026 TEST GAME STUDIO. ALL RIGHTS RESERVED. | <span class="text-white/10">Version
                    2.1.0-alpha</span>
            </p>
        </div>
    </div>
</footer>

<!-- Admin Portal Transition Overlay -->
<div id="adminTransitionOverlay"
    class="fixed inset-0 z-[9999] hidden pointer-events-none flex items-center justify-center">
    <div class="absolute inset-0 bg-black scale-y-0 origin-top transition-transform duration-700 ease-in-out"
        id="transitionBg"></div>
    <div class="relative opacity-0 transition-opacity duration-500 delay-500 scale-150 transition-transform duration-1000"
        id="transitionLogo">
        <div class="text-4xl font-black tracking-[0.5em] text-secondary drop-shadow-[0_0_20px_rgba(0,242,255,1)]">
            RESTRICTED ACCESS
        </div>
        <div class="mt-4 h-1 bg-secondary shadow-[0_0_15px_#00f2ff] origin-left scale-x-0 transition-transform duration-1000 delay-700"
            id="transitionBar"></div>
    </div>

    <!-- Scanning Lines -->
    <div class="absolute inset-0 bg-[linear-gradient(rgba(0,242,255,0.1)_1px,transparent_1px)] bg-[size:100%_4px] opacity-0 transition-opacity duration-300 pointer-events-none"
        id="scanLines"></div>
</div>

<script>
    function triggerAdminTransition() {
        const overlay = document.getElementById('adminTransitionOverlay');
        const bg = document.getElementById('transitionBg');
        const logo = document.getElementById('transitionLogo');
        const bar = document.getElementById('transitionBar');
        const scan = document.getElementById('scanLines');

        overlay.classList.remove('hidden');
        overlay.classList.remove('pointer-events-none');

        // Step 1: Slide down background
        setTimeout(() => {
            bg.classList.remove('scale-y-0');
            bg.classList.add('scale-y-100');
        }, 10);

        // Step 2: Show scanning lines
        setTimeout(() => {
            scan.classList.remove('opacity-0');
            scan.classList.add('opacity-40');
        }, 400);

        // Step 3: Show Logo & Bar
        setTimeout(() => {
            logo.classList.remove('opacity-0');
            logo.classList.add('opacity-100');
            logo.classList.remove('scale-150');
            logo.classList.add('scale-100');
            bar.classList.remove('scale-x-0');
            bar.classList.add('scale-x-100');
        }, 600);

        // Step 4: Redict
        setTimeout(() => {
            window.location.href = '<?php echo $prefix ?? ''; ?>admin_login.php';
        }, 2800);
    }
</script>

<!-- Floating LINE Button (New Feature) -->
<a href="https://lin.ee/sIO0Bvo" target="_blank"
   class="fixed bottom-8 right-8 z-[900] group flex items-center justify-center">
    <!-- Pulse Effect -->
    <div class="absolute inset-0 bg-[#06C755] rounded-full scale-110 animate-ping opacity-20"></div>
    <div class="relative w-14 h-14 bg-[#06C755] rounded-full shadow-[0_0_20px_rgba(6,199,85,0.4)] flex items-center justify-center transition-transform group-hover:scale-110">
        <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/LINE_logo.svg" class="w-7 h-7 filter brightness-0 invert" alt="LINE">
    </div>
    <!-- Tooltip -->
    <div class="absolute right-full mr-4 px-4 py-2 bg-white text-black text-[10px] font-black uppercase tracking-widest rounded-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap shadow-xl">
        CONTACT US ON LINE
    </div>
</a>

<script src="script.js"></script>
</body>

</html>