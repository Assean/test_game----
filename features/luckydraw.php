<?php
require_once __DIR__ . '/../includes/auth_logic.php';
include '../templates/header.php';
?>

<main class="pt-32 pb-24 px-[5%] overflow-hidden">
    <input type="hidden" id="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
    <div class="max-w-[1200px] mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

        <!-- Left: Info -->
        <div class="animate-fade-in-up">
            <h2 class="text-6xl font-black uppercase tracking-tighter mb-8 leading-none">
                萬人 <span class="bg-gradient-to-r from-accent to-primary bg-clip-text text-transparent">抽獎活動</span>
            </h2>
            <p class="text-white/60 text-lg leading-relaxed mb-10">
                消耗 <span class="text-secondary font-black">100 積分</span> 即可進行一次抽獎！<br>
                有機會獲得限量皮膚、巨量虛擬幣以及 Discord 限定稱號。
            </p>

            <div class="space-y-4 mb-12">
                <div class="flex items-center gap-4 bg-white/5 p-4 rounded-2xl border border-white/10">
                    <span class="text-2xl">💎</span>
                    <div>
                        <p class="font-bold text-sm">一等獎：Neon Legend Skin</p>
                        <p class="text-[10px] text-white/30">機率：0.5%</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 bg-white/5 p-4 rounded-2xl border border-white/10">
                    <span class="text-2xl">💰</span>
                    <div>
                        <p class="font-bold text-sm">二等獎：10,000 $TEST Coin</p>
                        <p class="text-[10px] text-white/30">機率：5%</p>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-secondary/10 border border-secondary/20 rounded-3xl">
                <p class="text-xs text-secondary font-black uppercase mb-1">您的現有積分</p>
                <h4 class="text-4xl font-black text-white">1,500 <span class="text-xs text-white/40">PTS</span></h4>
            </div>
        </div>

        <!-- Right: The Wheel -->
        <div class="relative flex flex-col items-center animate-fade-in-up" style="animation-delay: 0.3s">
            <div class="relative w-[400px] h-[400px] md:w-[500px] md:h-[500px]">
                <!-- Pointer -->
                <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-4 z-20 text-4xl">▼</div>

                <!-- Wheel Container -->
                <div id="wheel"
                    class="w-full h-full rounded-full border-8 border-white/10 relative transition-transform duration-[5s] ease-out shadow-[0_0_50px_rgba(0,242,255,0.2)]">
                    <?php
                    $prizes = ['Better luck!', 'Small Prize', 'JACKPOT!', 'Try Again', 'Bonus Points', 'Mystery Box'];
                    $colors = ['#1a1a1a', '#333', '#8a2be2', '#1a1a1a', '#00f2ff', '#ff007f'];
                    foreach ($prizes as $i => $prize):
                        $rotate = $i * (360 / count($prizes));
                        ?>
                        <div class="absolute top-0 left-1/2 w-1 h-1/2 origin-bottom flex flex-col items-center pt-8"
                            style="transform: translateX(-50%) rotate(<?php echo $rotate; ?>deg);">
                            <div class="absolute inset-0 w-[200px] h-[300px] -translate-x-1/2 origin-bottom clip-path-sector"
                                style="background: <?php echo $colors[$i]; ?>; opacity: 0.5;"></div>
                            <span class="relative z-10 text-[10px] font-black uppercase text-white/80 rotate-180"
                                style="writing-mode: vertical-lr;">
                                <?php echo $prize; ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Center Button -->
                <button onclick="comingSoon('幸運大抽獎 - 抽獎')"
                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-24 h-24 rounded-full bg-white text-bg-dark font-black uppercase text-xs z-30 shadow-xl hover:scale-110 active:scale-95 transition-all">
                    SPIN
                </button>
            </div>

            <div id="result"
                class="mt-8 text-2xl font-black uppercase tracking-widest text-secondary hidden animate-bounce">
                🎉 Congratulations! 🎉
            </div>
        </div>
    </div>
</main>

<style>
    .clip-path-sector {
        clip-path: polygon(50% 100%, 0 0, 100% 0);
    }

    #wheel {
        background: conic-gradient(#1a1a1a 0deg 60deg,
                #333 60deg 120deg,
                #8a2be2 120deg 180deg,
                #1a1a1a 180deg 240deg,
                #00f2ff 240deg 300deg,
                #ff007f 300deg 360deg);
    }
</style>

<script>
function comingSoon(featureName) {
    alert('【' + featureName + '】功能正在開發中，敬請期待！');
}
</script>

<?php include '../templates/footer.php'; ?>