<?php
require_once 'config/config.php';
require_once 'includes/auth_logic.php';

// 如果已經是管理員且登入，直接去後台
if (Auth::isLoggedIn() && Auth::isAdmin()) {
    header("Location: admin/dashboard.php");
    exit;
}

$csrf_token = Auth::generateCsrfToken();
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理員加密登入 | TEST GAME</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#8a2be2',
                        'secondary': '#00f2ff',
                        'accent': '#ff007f',
                        'bg-dark': '#010103',
                    },
                    fontFamily: {
                        'orbitron': ['Orbitron', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .glitch-text {
            text-shadow: 2px 0 #ff007f, -2px 0 #00f2ff;
            animation: glitch 2s infinite linear alternate-reverse;
        }

        @keyframes glitch {
            0% {
                text-shadow: 2px 0 #ff007f, -2px 0 #00f2ff;
            }

            50% {
                text-shadow: -2px 0 #ff007f, 2px 0 #00f2ff;
            }

            100% {
                text-shadow: 2px 0 #ff007f, -2px 0 #00f2ff;
            }
        }

        .cyber-panel {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.01) 100%);
            clip-path: polygon(0 0, 95% 0, 100% 5%, 100% 100%, 5% 100%, 0 95%);
        }

        .stage-transition {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>

<body class="bg-bg-dark text-white h-screen flex items-center justify-center overflow-hidden font-orbitron">
    <!-- Grid Background -->
    <div
        class="fixed inset-0 z-[-1] opacity-20 bg-[linear-gradient(rgba(0,242,255,0.1)_1px,transparent_1px),linear-gradient(90deg,rgba(0,242,255,0.1)_1px,transparent_1px)] bg-[size:50px_50px]">
    </div>

    <!-- Animating Circle -->
    <div
        class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-secondary/5 rounded-full blur-[120px] animate-pulse">
    </div>

    <div class="relative z-10 w-full max-w-[500px] p-8">
        <div class="text-center mb-12">
            <h1 class="text-5xl font-black mb-2 glitch-text tracking-tighter italic">ADMIN</h1>
            <div class="h-1 w-24 bg-secondary mx-auto mb-4"></div>
            <p class="text-[10px] text-white/40 uppercase tracking-[0.5em]">System Authorization Required</p>
        </div>

        <div class="cyber-panel border border-white/10 p-10 backdrop-blur-3xl relative overflow-hidden">
            <!-- Loading Bar -->
            <div id="loadingBar" class="absolute top-0 left-0 h-[2px] bg-secondary w-0 transition-all duration-300">
            </div>

            <!-- Quick Auth Section (New Feature) -->
            <div class="mb-10 space-y-4">
                <p class="text-[9px] font-black text-white/30 uppercase tracking-[0.4em] text-center mb-4 italic">Quick Authorization</p>
                <div class="grid grid-cols-2 gap-4">
                    <?php
                    require_once 'includes/google_api.php';
                    $googleApi = new GoogleAPI(GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, GOOGLE_REDIRECT_URI);
                    require_once 'includes/discord_api.php';
                    $discordApi = new DiscordAPI(DISCORD_CLIENT_ID, DISCORD_CLIENT_SECRET, DISCORD_REDIRECT_URI, DISCORD_WEBHOOK_URL);
                    ?>
                    <a href="<?php echo $googleApi->getLoginUrl('admin_login'); ?>" 
                       class="flex items-center justify-center gap-2 py-4 bg-white/5 border border-white/10 rounded-lg text-[9px] font-black uppercase hover:bg-white/10 transition-all group">
                        <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" class="w-4 h-4 grayscale group-hover:grayscale-0 transition-all" alt=""> Google
                    </a>
                    <a href="<?php echo $discordApi->getLoginUrl('admin_login'); ?>" 
                       class="flex items-center justify-center gap-2 py-4 bg-white/5 border border-white/10 rounded-lg text-[9px] font-black uppercase hover:bg-white/10 transition-all group">
                        <img src="https://assets-global.website-files.com/6257adef93867e3d0394e0ff/6257d07978625eddc13945a0_Discord-Logo-White.svg" class="w-4 h-4 opacity-40 group-hover:opacity-100 transition-all" alt=""> Discord
                    </a>
                </div>
                <div class="flex items-center gap-4 text-white/5 pt-2">
                    <div class="h-px bg-current flex-1"></div>
                    <span class="text-[8px] font-black uppercase tracking-widest">or secure manual access</span>
                    <div class="h-px bg-current flex-1"></div>
                </div>
            </div>

            <form id="adminForm" class="space-y-8">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

                <!-- Stage 1: Auth -->
                <div id="stage1" class="stage-transition">
                    <div class="space-y-6">
                        <div>
                            <label
                                class="block text-[10px] font-black text-secondary uppercase tracking-widest mb-3">Operator
                                ID</label>
                            <input type="text" name="username" required
                                class="w-full bg-white/5 border-b-2 border-white/10 px-0 py-4 focus:border-secondary outline-none transition-all text-xl font-light tracking-widest"
                                placeholder="IDENTIFY YOURSELF">
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-black text-secondary uppercase tracking-widest mb-3">Access
                                Key</label>
                            <input type="password" name="password" required
                                class="w-full bg-white/5 border-b-2 border-white/10 px-0 py-4 focus:border-secondary outline-none transition-all text-xl font-light tracking-widest"
                                placeholder="••••••••">
                        </div>
                        <div id="errorMsg1"
                            class="hidden text-accent text-xs font-bold uppercase tracking-widest text-center mt-4">
                            Invalid Access Level</div>
                    </div>
                </div>

                <!-- Stage 2: 2FA (Hidden by default) -->
                <div id="stage2"
                    class="stage-transition absolute top-0 left-0 w-full h-full p-10 translate-x-full opacity-0 pointer-events-none">
                    <div class="space-y-6">
                        <div class="text-center mb-8">
                            <span class="text-xs text-white/40 uppercase tracking-[0.3em]">Identity Conflict
                                Detected</span>
                            <h3 class="text-lg font-bold mt-1">TWO-FACTOR ENCRYPTION</h3>
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-black text-secondary uppercase tracking-widest mb-3">Virtual
                                Gateway Email</label>
                            <input type="email" name="admin_email"
                                class="w-full bg-white/5 border-b-2 border-white/10 px-0 py-4 focus:border-secondary outline-none transition-all text-xl font-light tracking-widest"
                                placeholder="xxx@game.web.com">
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-black text-secondary uppercase tracking-widest mb-3">High-Security
                                Code</label>
                            <input type="text" name="security_code"
                                class="w-full bg-white/5 border-b-2 border-white/10 px-0 py-4 focus:border-secondary outline-none transition-all text-xl font-light tracking-widest"
                                placeholder="0000-0000">
                        </div>
                        <div id="errorMsg2"
                            class="hidden text-accent text-xs font-bold uppercase tracking-widest text-center mt-4">
                            Verification Terminated</div>
                    </div>
                </div>

                <div class="relative pt-6">
                    <button type="submit" id="submitBtn"
                        class="w-full py-5 bg-white/5 border border-white/20 hover:bg-secondary hover:text-black hover:border-secondary transition-all font-black uppercase tracking-[0.3em] group relative overflow-hidden">
                        <span class="relative z-10 transition-transform group-hover:scale-110 block">Execute
                            Protocol</span>
                        <div
                            class="absolute inset-x-0 bottom-0 h-[2px] bg-secondary scale-x-0 group-hover:scale-x-100 transition-transform">
                        </div>
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-8 flex justify-between items-center text-[10px] text-white/20 tracking-widest uppercase">
            <span>Core: 2.1.0</span>
            <a href="index.php" class="hover:text-secondary hover:underline">Abort Protocol</a>
            <span>Encrypted Layer 7</span>
        </div>
    </div>

    <script>
        let currentStage = 1;
        const form = document.getElementById('adminForm');
        const stage1 = document.getElementById('stage1');
        const stage2 = document.getElementById('stage2');
        const btn = document.getElementById('submitBtn');
        const bar = document.getElementById('loadingBar');

        form.onsubmit = async (e) => {
            e.preventDefault();
            bar.style.width = '70%';
            btn.disabled = true;
            btn.innerText = 'PROCESSING...';

            const formData = new FormData(form);
            formData.append('stage', currentStage);

            try {
                const response = await fetch('auth/admin_login_handler.php', {
                    method: 'POST',
                    body: formData
                });
                const res = await response.json();

                if (res.success) {
                    if (currentStage === 1) {
                        // Move to Stage 2
                        bar.style.width = '100%';
                        setTimeout(() => {
                            bar.style.width = '0%';
                            stage1.classList.add('-translate-x-full', 'opacity-0', 'pointer-events-none');
                            stage2.classList.remove('translate-x-full', 'opacity-0', 'pointer-events-none', 'absolute');
                            currentStage = 2;
                            btn.disabled = false;
                            btn.innerText = 'VERIFY PROTOCOL';
                            document.getElementById('errorMsg1').classList.add('hidden');
                        }, 500);
                    } else {
                        // Success Login
                        bar.style.width = '100%';
                        btn.innerText = 'AUTHORIZED';
                        setTimeout(() => {
                            window.location.href = 'admin/portal.php';
                        }, 1000);
                    }
                } else {
                    bar.style.width = '10%';
                    btn.disabled = false;
                    btn.innerText = currentStage === 1 ? 'EXECUTE PROTOCOL' : 'VERIFY PROTOCOL';
                    if (currentStage === 1) {
                        document.getElementById('errorMsg1').classList.remove('hidden');
                        document.getElementById('errorMsg1').innerText = res.message || 'Invalid Access Level';
                    } else {
                        document.getElementById('errorMsg2').classList.remove('hidden');
                        document.getElementById('errorMsg2').innerText = res.message || 'Verification Terminated';
                    }
                }
            } catch (err) {
                console.error(err);
                bar.style.width = '10%';
                btn.disabled = false;
                btn.innerText = 'SYSTEM ERROR';
            }
        };
    </script>
</body>

</html>