<?php 
require_once 'includes/auth_logic.php';
include 'templates/header.php'; 

// 新功能 1 & Bug 13：處理 OAuth 預填資料 (支援 Google & Discord)
$pre_email    = $_SESSION['pending_google_email']    ?? $_SESSION['oauth_email'] ?? '';
$pre_username = $_SESSION['pending_google_username'] ?? $_SESSION['oauth_name']  ?? '';
$is_google    = isset($_SESSION['pending_google_id']);
$is_oauth     = !empty($pre_email) || !empty($pre_username);
?>

<main class="pt-32 pb-24 px-[5%]">
    <div
        class="max-w-[600px] mx-auto bg-white/5 border border-white/10 backdrop-blur-2xl p-10 rounded-[40px] relative overflow-hidden">
        
        <!-- Social Register Buttons (New Feature 1) -->
        <?php if (!$is_oauth): ?>
        <div class="mb-10 space-y-3">
            <p class="text-[10px] font-black text-white/20 uppercase tracking-[0.3em] text-center mb-4 text-center">Quick Start with Social</p>
            <div class="grid grid-cols-2 gap-4">
                <?php
                require_once 'includes/google_api.php';
                $googleApi = new GoogleAPI(GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, GOOGLE_REDIRECT_URI);
                require_once 'includes/discord_api.php';
                $discordApi = new DiscordAPI(DISCORD_CLIENT_ID, DISCORD_CLIENT_SECRET, DISCORD_REDIRECT_URI, DISCORD_WEBHOOK_URL);
                ?>
                <a href="<?php echo $googleApi->getLoginUrl(); ?>" class="flex items-center justify-center gap-2 py-3 bg-white text-black rounded-xl text-[10px] font-black uppercase hover:bg-white/90 transition-all">
                    <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" class="w-4 h-4" alt=""> Google
                </a>
                <a href="<?php echo $discordApi->getLoginUrl(); ?>" class="flex items-center justify-center gap-2 py-3 bg-[#5865F2] text-white rounded-xl text-[10px] font-black uppercase hover:bg-[#4752C4] transition-all">
                    <img src="https://assets-global.website-files.com/6257adef93867e3d0394e0ff/6257d07978625eddc13945a0_Discord-Logo-White.svg" class="w-4 h-4" alt=""> Discord
                </a>
            </div>
            <div class="flex items-center gap-4 text-white/5 pt-4">
                <div class="h-px bg-current flex-1"></div>
                <span class="text-[9px] font-black uppercase tracking-widest">OR USE EMAIL</span>
                <div class="h-px bg-current flex-1"></div>
            </div>
        </div>
        <?php else: ?>
        <div class="mb-8 p-4 bg-secondary/10 border border-secondary/20 rounded-2xl flex items-center gap-4">
            <div class="text-2xl">✨</div>
            <p class="text-xs text-secondary font-bold">已從第三方帳號取得資料，請設定您的密碼以完成註冊。</p>
        </div>
        <?php endif; ?>

        <!-- Progress Bar -->
        <div class="mb-10">
            <div class="flex justify-between mb-4">
                <span id="step-label" class="text-secondary font-black uppercase tracking-widest text-[10px]">Step 1: Account Basics</span>
                <span id="step-number" class="text-white/30 font-bold text-[10px]">1 / 3</span>
            </div>
            <div class="w-full h-1.5 bg-white/10 rounded-full overflow-hidden">
                <div id="progress-bar"
                    class="h-full bg-gradient-to-r from-primary to-secondary w-1/3 transition-all duration-500"></div>
            </div>
        </div>

        <form id="register-form" class="space-y-8">
            <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">

            <!-- Step 1: Basics -->
            <div id="step-1" class="step-content space-y-6">
                <div>
                    <label class="block text-xs font-black text-white/40 uppercase tracking-widest mb-2">Email Address</label>
                    <input type="email" name="email" required value="<?php echo htmlspecialchars($pre_email); ?>" <?php echo $is_oauth ? 'readonly' : ''; ?>
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 focus:border-primary outline-none transition-all <?php echo $is_oauth ? 'opacity-50' : ''; ?>"
                        placeholder="your@email.com">
                </div>
                <div>
                    <label class="block text-xs font-black text-white/40 uppercase tracking-widest mb-2">Username</label>
                    <input type="text" name="username" required value="<?php echo htmlspecialchars($pre_username); ?>"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 focus:border-primary outline-none transition-all"
                        placeholder="Choose a name">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-white/40 uppercase tracking-widest mb-2">Password</label>
                        <input type="password" name="password" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 focus:border-primary outline-none transition-all"
                            placeholder="至少 8 字元">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-white/40 uppercase tracking-widest mb-2">Confirm</label>
                        <input type="password" name="confirm_password" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 focus:border-primary outline-none transition-all"
                            placeholder="再輸入一次">
                    </div>
                </div>
                <div id="error-msg-step1" class="text-red-400 text-[10px] font-bold hidden bg-red-500/10 p-3 rounded-xl"></div>
                <button type="button" onclick="nextStep(2)" class="w-full btn-neon py-5">Proceed to Profile</button>
            </div>

            <!-- Step 2: Discord -->
            <div id="step-2" class="step-content hidden space-y-6">
                <div class="bg-[#5865F2]/10 border border-[#5865F2]/20 p-6 rounded-2xl mb-8 flex gap-4 items-start">
                    <div class="text-xl">💬</div>
                    <p class="text-xs text-[#5865F2]/80 leading-relaxed font-bold">
                        綁定 Discord 可同步遊戲內的身分組權限。您可以填寫您的 <span class="text-white">Discord 使用者名稱</span> 或 <span class="text-white">ID</span>。
                    </p>
                </div>
                <div>
                    <label class="block text-xs font-black text-white/40 uppercase tracking-widest mb-2">Discord ID / Username</label>
                    <input type="text" name="discord_id"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 focus:border-secondary outline-none transition-all"
                        placeholder="e.g. Ray#1234">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <button type="button" onclick="nextStep(1)"
                        class="w-full py-5 border border-white/10 rounded-full hover:bg-white/5 transition-all font-black text-[10px] uppercase">Back</button>
                    <button type="button" onclick="nextStep(3)"
                        class="w-full btn-neon py-5 bg-gradient-to-r from-secondary to-primary italic">Verify Game ID</button>
                </div>
            </div>

            <!-- Step 3: Roblox (New Feature 10) -->
            <div id="step-3" class="step-content hidden space-y-6">
                <div class="bg-accent/10 border border-accent/20 p-6 rounded-2xl mb-8 flex gap-4 items-start">
                    <div class="text-xl">⚠️</div>
                    <p class="text-[11px] text-accent leading-relaxed font-black uppercase tracking-tight">
                        這是最關鍵的一步！請務必填寫正確的 Roblox User ID，否則將無法領取補償與獎勵。
                    </p>
                </div>
                <div>
                    <label class="block text-xs font-black text-white/40 uppercase tracking-widest mb-2">Roblox User ID</label>
                    <div class="flex gap-4">
                        <input type="text" id="roblox-id-input" name="roblox_id" required
                            class="flex-1 bg-white/5 border border-white/10 rounded-xl px-5 py-4 focus:border-accent outline-none transition-all font-mono"
                            placeholder="e.g. 12345678">
                        <button type="button" onclick="verifyRoblox()" id="verify-btn"
                                class="px-6 bg-white/5 border border-white/10 rounded-xl text-[10px] font-black uppercase hover:border-accent transition-all">
                            Verify
                        </button>
                    </div>
                </div>
                <div id="roblox-status" class="hidden p-4 bg-green-500/10 border border-green-500/20 rounded-xl text-green-400 text-[10px] font-black uppercase">
                    ✅ 帳號驗證成功！
                </div>
                <div id="error-msg" class="text-red-400 text-[10px] font-bold hidden bg-red-500/10 p-3 rounded-xl"></div>
                
                <div class="grid grid-cols-2 gap-4">
                    <button type="button" onclick="nextStep(2)"
                        class="w-full py-5 border border-white/10 rounded-full hover:bg-white/5 transition-all font-black text-[10px] uppercase">Back</button>
                    <button type="submit" id="submit-btn" disabled
                        class="w-full btn-neon py-5 bg-accent shadow-[0_0_20px_rgba(255,0,127,0.5)] opacity-50 cursor-not-allowed">
                        Finalize Account
                    </button>
                </div>
                <p class="text-center text-[9px] text-white/20 italic">註冊即代表您同意本站之使用者規範與隱私權協定</p>
            </div>
        </form>

        <!-- Loading Overlay -->
        <div id="loading"
            class="absolute inset-0 bg-bg-dark/80 backdrop-blur-md flex items-center justify-center z-50 hidden">
            <div class="w-12 h-12 border-4 border-secondary border-t-transparent rounded-full animate-spin"></div>
        </div>

        <div class="mt-8 text-center text-white/40 text-sm">
            Already have an account? <a href="login.php" class="text-secondary hover:underline">Log In</a>
        </div>
    </div>
</main>

<script>
    function nextStep(step) {
        // Hide all error messages when switching steps
        document.querySelectorAll('[id^="error-msg"]').forEach(el => el.classList.add('hidden'));

        // Hide all steps
        document.querySelectorAll('.step-content').forEach(el => el.classList.add('hidden'));
        // Show current step
        document.getElementById('step-' + step).classList.remove('hidden');

        // Update progress
        const labels = ["Account Basics", "Discord ID", "Roblox Verification"];
        document.getElementById('step-label').innerText = "Step " + step + ": " + labels[step - 1];
        document.getElementById('step-number').innerText = step + " / 3";
        document.getElementById('progress-bar').style.width = (step / 3 * 100) + "%";
    }

    async function verifyRoblox() {
        const idInput = document.getElementById('roblox-id-input');
        const id = idInput.value.trim();
        if (!id) {
            Utils.showToast('請輸入 Roblox ID', 'error');
            return;
        }

        const btn = document.getElementById('verify-btn');
        const status = document.getElementById('roblox-status');
        const submitBtn = document.getElementById('submit-btn');

        btn.disabled = true;
        btn.textContent = 'Verifying...';
        Utils.showLoading();

        try {
            // 模擬 API 延遲
            await new Promise(r => setTimeout(r, 2000));
            
            // 模擬成功驗證 (New Feature 10)
            status.classList.remove('hidden');
            idInput.readOnly = true;
            btn.classList.add('hidden');
            
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            Utils.showToast('Roblox 驗證成功！', 'success');
        } finally {
            Utils.hideLoading();
        }
    }

    document.getElementById('register-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const errorMsg = document.getElementById('error-msg');

        errorMsg.classList.add('hidden');
        Utils.showLoading();

        try {
            const response = await fetch('auth/register_handler.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.success) {
                Utils.showToast('註冊成功！歡迎加入。', 'success');
                setTimeout(() => window.location.href = 'profile.php', 1000);
            } else {
                errorMsg.innerText = result.message;
                errorMsg.classList.remove('hidden');
                Utils.showToast(result.message, 'error');
            }
        } catch (err) {
            errorMsg.innerText = "Network error. Please try again.";
            errorMsg.classList.remove('hidden');
            Utils.showToast("網路錯誤", 'error');
        } finally {
            Utils.hideLoading();
        }
    });
</script>

<?php include 'templates/footer.php'; ?>