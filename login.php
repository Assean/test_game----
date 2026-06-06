<?php require_once 'includes/auth_logic.php'; ?>
<?php include 'templates/header.php'; ?>

<main class="pt-32 pb-24 px-[5%]">
    <div class="max-w-[500px] mx-auto bg-white/5 border border-white/10 backdrop-blur-2xl p-10 rounded-3xl">
        <h2 class="text-3xl font-black mb-8 uppercase text-secondary">Member <span class="text-white">Login</span></h2>

        <form action="auth/login_handler.php" method="POST" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
            <div>
                <label class="block text-xs font-black text-white/40 uppercase tracking-widest mb-2">Email Address</label>
                <input type="email" name="email" required
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 focus:border-secondary outline-none transition-all"
                    placeholder="your@email.com">
            </div>
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-xs font-black text-white/40 uppercase tracking-widest">Password</label>
                    <a href="forgot_password.php" class="text-[10px] text-secondary hover:underline font-bold uppercase">Forgot Password?</a>
                </div>
                <input type="password" name="password" required
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 focus:border-secondary transition-all outline-none"
                    placeholder="••••••••">
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 text-xs">
                    <?php
                    switch ($_GET['error']) {
                        case '1': echo "無效的 Email 或密碼。"; break;
                        case 'too_many_attempts': echo "嘗試次數過多，請稍後再試。"; break;
                        case 'invalid_session': echo "工作階段已過期，請重新登入。"; break;
                        case 'banned': echo "您的帳號已被封鎖。"; break;
                        case 'google_failed': echo "Google 登入失敗。"; break;
                        case 'discord_failed': echo "Discord 登入失敗。"; break;
                        default: echo "發生錯誤，請稍後再試。";
                    }
                    ?>
                </div>
            <?php endif; ?>

            <button type="submit" class="w-full btn-neon py-5">Login Now</button>
        </form>

        <div class="mt-8 flex items-center gap-4 text-white/20">
            <div class="h-px bg-current flex-1"></div>
            <span class="text-[10px] font-black uppercase tracking-widest">OR</span>
            <div class="h-px bg-current flex-1"></div>
        </div>

        <div class="mt-8 space-y-3">
            <?php
            require_once 'includes/google_api.php';
            $googleApi = new GoogleAPI(GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, GOOGLE_REDIRECT_URI);
            
            require_once 'includes/discord_api.php';
            $discordApi = new DiscordAPI(DISCORD_CLIENT_ID, DISCORD_CLIENT_SECRET, DISCORD_REDIRECT_URI, DISCORD_WEBHOOK_URL);
            ?>
            <a href="<?php echo $googleApi->getLoginUrl(); ?>"
                class="block w-full text-center py-4 bg-white text-black font-black rounded-xl hover:bg-white/90 transition-all flex items-center justify-center gap-3 text-sm">
                <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" class="w-5 h-5" alt="Google">
                CONTINUE WITH GOOGLE
            </a>
            <a href="<?php echo $discordApi->getLoginUrl(); ?>"
                class="block w-full text-center py-4 bg-[#5865F2] text-white font-black rounded-xl hover:bg-[#4752C4] transition-all flex items-center justify-center gap-3 text-sm">
                <img src="https://assets-global.website-files.com/6257adef93867e3d0394e0ff/6257d07978625eddc13945a0_Discord-Logo-White.svg" class="w-6 h-6" alt="Discord">
                CONTINUE WITH DISCORD
            </a>
        </div>

        <div class="mt-8 text-center text-white/40 text-sm">
            Don't have an account? <a href="register.php" class="text-secondary hover:underline font-bold">Register Here</a>
        </div>
    </div>
</main>

<?php include 'templates/footer.php'; ?>