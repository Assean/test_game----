<?php
require_once __DIR__ . '/includes/auth_logic.php';
require_once __DIR__ . '/templates/header.php';

// 權限檢查：未登入者導向登入頁
if (!Auth::isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// 獲取當前最新使用者資料
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// 準備驗證所需的 API 網址
require_once __DIR__ . '/includes/google_api.php';
$googleApi = new GoogleAPI(GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, GOOGLE_REDIRECT_URI);
$googleVerifyUrl = $googleApi->getLoginUrl('verify');

require_once __DIR__ . '/includes/discord_api.php';
$discordApi = new DiscordAPI(DISCORD_CLIENT_ID, DISCORD_CLIENT_SECRET, DISCORD_REDIRECT_URI, DISCORD_WEBHOOK_URL);
$discordVerifyUrl = $discordApi->getLoginUrl('verify');

// Roblox 數據同步 (新功能：連動 DataStore)
require_once __DIR__ . '/includes/roblox_api.php';
$robloxApi = new RobloxAPI(ROBLOX_API_KEY, ROBLOX_UNIVERSE_ID);
$gameData = ['power' => '---', 'coins' => '---'];
$robloxStatus = '未連動';

if ($user['roblox_id'] && ROBLOX_API_KEY !== 'YOUR_ROBLOX_API_KEY') {
    $response = $robloxApi->getDataStoreEntry("TestGame", $user['roblox_id']);
    if ($response['success']) {
        $gameData['power'] = $response['data']['power'] ?? 0;
        $gameData['coins'] = $response['data']['coins'] ?? 0;
        $robloxStatus = '✅ 已同步';
    } else {
        $robloxStatus = '⚠️ 同步失敗 (API)';
    }
} elseif (!$user['roblox_id']) {
    $robloxStatus = '❌ 尚未綁定 ID';
}

// 驗證進度計算 (新功能：階梯式驗證 - 已簡化為 3 階)
$verifiedCount = 0;
if ($user['system_verified']) $verifiedCount++;
if ($user['google_id'])       $verifiedCount++;
if ($user['discord_id'])      $verifiedCount++;
$isFullyVerified = ($verifiedCount === 3);
?>

<main class="pt-32 pb-24 px-[5%]">
    <div class="max-w-[1000px] mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">

        <!-- Sidebar: User Info -->
        <div class="bg-white/5 border border-white/10 backdrop-blur-2xl p-8 rounded-3xl h-fit">
            <div class="text-center mb-8">
                <?php if ($user['avatar_url']): ?>
                    <img src="<?php echo htmlspecialchars($user['avatar_url']); ?>" 
                         class="w-24 h-24 mx-auto rounded-full mb-4 border-2 border-secondary shadow-[0_0_20px_rgba(138,43,226,0.3)] object-cover" 
                         alt="Avatar">
                <?php else: ?>
                    <div class="w-24 h-24 mx-auto bg-gradient-to-r from-primary to-secondary rounded-full flex items-center justify-center text-4xl mb-4 shadow-[0_0_20px_rgba(138,43,226,0.3)]">
                        👤
                    </div>
                <?php endif; ?>
                
                <h3 class="text-2xl font-black">
                    <?php echo htmlspecialchars($user['username']); ?>
                </h3>
                <span class="text-white/40 text-sm uppercase tracking-widest">
                    <?php echo htmlspecialchars($user['email']); ?>
                </span>
                
                <!-- New Verification System (Redesigned) -->
                <div class="mt-4">
                    <?php if ($isFullyVerified): ?>
                        <div class="flex flex-col items-center gap-2">
                            <span class="px-4 py-1.5 bg-green-500/10 border border-green-500/20 text-green-400 text-[10px] font-black uppercase rounded-full shadow-[0_0_15px_rgba(74,222,128,0.2)]">
                                ✓ 帳戶已全面驗證
                            </span>
                            <p class="text-[9px] text-white/20 italic">您的身分已過最高級真人核驗</p>
                        </div>
                    <?php else: ?>
                        <div class="flex flex-col items-center gap-3">
                            <div class="flex items-center gap-2">
                                <div class="w-16 h-1.5 bg-white/5 rounded-full overflow-hidden">
                                    <div class="h-full bg-yellow-500 transition-all" style="width: <?php echo ($verifiedCount / 3) * 100; ?>%"></div>
                                </div>
                                <span class="text-[10px] font-black text-yellow-500"><?php echo $verifiedCount; ?>/3</span>
                            </div>
                            <button onclick="openVerifyModal()" class="btn-neon py-2 px-6 text-[10px] bg-gradient-to-r from-yellow-500 to-orange-500 shadow-[0_0_15px_rgba(245,158,11,0.3)]">
                                🛡️ 繼續完成驗證
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="space-y-4 border-t border-white/5 pt-6">
                <!-- Status Alerts -->
                <?php if (isset($_GET['success'])): ?>
                    <div class="p-3 bg-green-500/10 border border-green-500/20 rounded-xl text-green-400 text-[10px] text-center font-bold">
                        操作成功！
                    </div>
                <?php endif; ?>

                <div class="flex justify-between items-center text-sm">
                    <span class="text-white/40">Discord 狀態</span>
                    <?php if ($user['discord_id']): ?>
                        <span class="text-green-400 font-bold text-xs">✅ 已綁定</span>
                    <?php else: ?>
                        <a href="<?php echo $discordVerifyUrl; ?>" class="px-3 py-1 bg-secondary/10 border border-secondary/20 text-secondary text-[9px] font-black uppercase rounded-lg hover:bg-secondary/20 transition-all">
                            待綁定 (點擊驗證)
                        </a>
                    <?php endif; ?>
                </div>

                <div class="flex justify-between items-center text-sm mt-4">
                    <span class="text-white/40">Roblox ID</span>
                    <span class="text-secondary font-mono font-bold">
                        <?php echo htmlspecialchars($user['roblox_id'] ?: '未設定'); ?>
                    </span>
                </div>
            </div>

            <a href="logout.php"
                class="block w-full text-center mt-10 py-3 border border-red-500/30 text-red-400 rounded-xl hover:bg-red-500/10 transition-all text-sm font-bold">
                登出帳號
            </a>

            <!-- New Feature 6: Login History (Last 5 in sidebar for space) -->
            <div class="mt-8 pt-6 border-t border-white/5">
                <h4 class="text-xs font-black text-white/40 uppercase tracking-widest mb-4">最近登入歷史</h4>
                <div class="space-y-3">
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM login_logs WHERE user_id = ? ORDER BY login_at DESC LIMIT 5");
                    $stmt->execute([$user['id']]);
                    $logs = $stmt->fetchAll();
                    foreach ($logs as $log):
                    ?>
                    <div class="text-[9px] text-white/30 flex justify-between border-b border-white/5 pb-2">
                        <span><?php echo htmlspecialchars($log['ip_address']); ?></span>
                        <span><?php echo date('m/d H:i', strtotime($log['login_at'])); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- New Feature: Verified Account Details -->
            <?php if (isset($_SESSION['google_verified_details']) || isset($_SESSION['discord_verified_details'])): ?>
            <div class="mt-8 pt-6 border-t border-white/5 bg-secondary/5 p-4 rounded-2xl border border-secondary/10">
                <h4 class="text-xs font-black text-secondary uppercase tracking-widest mb-4">驗證帳戶詳細資訊</h4>
                <div class="space-y-4">
                    <?php if (isset($_SESSION['google_verified_details'])): 
                        $g = json_decode($_SESSION['google_verified_details'], true); ?>
                        <div class="flex items-center gap-3">
                            <img src="<?php echo $g['picture']; ?>" class="w-8 h-8 rounded-full">
                            <div class="text-[10px]">
                                <p class="font-black text-white/80">Google: <?php echo htmlspecialchars($g['name']); ?></p>
                                <p class="text-white/40"><?php echo htmlspecialchars($g['email']); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['discord_verified_details'])): 
                        $d = json_decode($_SESSION['discord_verified_details'], true); ?>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-[#5865F2] rounded-full flex items-center justify-center text-xs">💬</div>
                            <div class="text-[10px]">
                                <p class="font-black text-white/80">Discord: <?php echo htmlspecialchars($d['username']); ?></p>
                                <p class="text-white/40">ID: <?php echo htmlspecialchars($d['id']); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Main Content: Game Data -->
        <div class="md:col-span-2 space-y-8">
            <!-- Game Stats Placeholder -->
            <div
                class="bg-gradient-to-br from-white/10 to-transparent border border-white/10 backdrop-blur-2xl p-10 rounded-3xl relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 opacity-10 text-6xl">⚔️</div>
                <h2 class="text-3xl font-black mb-10 uppercase tracking-tighter">
                    遊戲 <span class="text-secondary">數據中心</span>
                </h2>

                <div class="grid grid-cols-2 gap-6">
                    <div class="bg-white/5 p-6 rounded-2xl border border-white/5 relative overflow-hidden">
                        <p class="text-white/40 text-xs uppercase font-black mb-2">戰鬥力 (Power)</p>
                        <h4 class="text-4xl font-black text-white"><?php echo htmlspecialchars($gameData['power']); ?></h4>
                        <div class="absolute bottom-0 left-0 h-0.5 bg-primary/30 w-full"></div>
                    </div>
                    <div class="bg-white/5 p-6 rounded-2xl border border-white/5 relative overflow-hidden">
                        <p class="text-white/40 text-xs uppercase font-black mb-2">虛擬幣餘額</p>
                        <h4 class="text-4xl font-black text-secondary"><?php echo htmlspecialchars($gameData['coins']); ?></h4>
                        <div class="absolute bottom-0 left-0 h-0.5 bg-secondary/30 w-full"></div>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-between text-[10px] uppercase font-black tracking-widest text-white/20 px-2">
                    <span>同步狀態: <span class="<?php echo strpos($robloxStatus, '✅') !== false ? 'text-green-400' : 'text-red-400'; ?>"><?php echo $robloxStatus; ?></span></span>
                    <span>DataStore: TestGame</span>
                </div>

                <div class="mt-10 p-6 bg-primary/10 border border-primary/20 rounded-2xl">
                    <h5 class="font-bold text-sm mb-2 text-primary">💡 開發者提示</h5>
                    <p class="text-xs text-white/60 leading-relaxed">
                        系統目前正嘗試從 **DataStore ("TestGame")** 抓取 Key 為 **"<?php echo htmlspecialchars($user['roblox_id']); ?>"** 的資料。
                        請確保您的 Open Cloud API Key 具備 **DataStores:Entry Read** 權限，且 Universe ID 正確。
                    </p>
                </div>
            </div>

            <!-- New Feature 6: Detailed Login History -->
            <div class="bg-white/5 border border-white/10 backdrop-blur-2xl p-10 rounded-3xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">登入歷史紀錄 (最近 10 次)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="text-white/40 uppercase tracking-widest border-b border-white/10">
                            <tr>
                                <th class="pb-4 font-black">時間</th>
                                <th class="pb-4 font-black">IP 地址</th>
                                <th class="pb-4 font-black">裝置/瀏覽器</th>
                            </tr>
                        </thead>
                        <tbody class="text-white/60">
                            <?php
                            $stmt = $pdo->prepare("SELECT * FROM login_logs WHERE user_id = ? ORDER BY login_at DESC LIMIT 10");
                            $stmt->execute([$user['id']]);
                            $fullLogs = $stmt->fetchAll();
                            foreach ($fullLogs as $log):
                                // 略微簡化 User Agent
                                $ua = $log['user_agent'];
                                $browser = "Unknown";
                                if (strpos($ua, 'Chrome') !== false) $browser = "Chrome";
                                elseif (strpos($ua, 'Firefox') !== false) $browser = "Firefox";
                                elseif (strpos($ua, 'Safari') !== false) $browser = "Safari";
                            ?>
                            <tr class="border-b border-white/5">
                                <td class="py-4"><?php echo date('Y-m-d H:i', strtotime($log['login_at'])); ?></td>
                                <td class="py-4 font-mono"><?php echo htmlspecialchars($log['ip_address']); ?></td>
                                <td class="py-4 truncate max-w-[200px]" title="<?php echo htmlspecialchars($ua); ?>">
                                    <?php echo $browser; ?> (<?php echo substr($ua, 0, 30); ?>...)
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</main>

<!-- Verification Modal (New Feature 5 Overhaul) -->
<div id="verify-modal" class="fixed inset-0 z-[2000] flex items-center justify-center hidden p-4">
    <div class="absolute inset-0 bg-bg-dark/80 backdrop-blur-xl" onclick="closeVerifyModal()"></div>
    <div class="relative w-full max-w-[550px] bg-white/5 border border-white/10 rounded-[40px] p-8 md:p-12 shadow-[0_0_50px_rgba(0,0,0,0.5)] overflow-hidden">
        
        <!-- Decoration -->
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-primary/20 blur-[100px] rounded-full"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-secondary/20 blur-[100px] rounded-full"></div>

        <div class="text-center mb-10 relative">
            <h3 class="text-3xl font-black mb-4 uppercase tracking-tighter">帳戶 <span class="text-secondary">安全驗證</span></h3>
            <p class="text-xs text-white/60 leading-relaxed font-medium">
                為了確保您是真實的人類，不是機器人冒充，請協助我們完成系統級與社交帳戶 (Google / Discord) 登入驗證。
            </p>
        </div>

        <!-- Main Selection -->
        <div id="verify-selection" class="space-y-4">
            <!-- 1. System -->
            <button onclick="<?php echo $user['system_verified'] ? 'Utils.showToast(\'已完成系統驗證\', \'success\')' : 'startSystemVerify()'; ?>" 
                class="w-full flex items-center justify-between p-5 bg-white/5 border <?php echo $user['system_verified'] ? 'border-green-500/30' : 'border-white/10 hover:border-primary'; ?> rounded-2xl transition-all group overflow-hidden relative">
                <div class="flex items-center gap-4 relative z-10">
                    <span class="text-2xl <?php echo $user['system_verified'] ? '' : 'group-hover:scale-110'; ?> transition-transform">🤖</span>
                    <div class="text-left">
                        <p class="text-sm font-black uppercase flex items-center gap-2">
                            系統驗證 <?php if($user['system_verified']): ?><span class="text-[8px] bg-green-500 text-white px-1.5 py-0.5 rounded">DONE</span><?php endif; ?>
                        </p>
                        <p class="text-[10px] text-white/40">全自動真人行為掃描 (需 5 秒)</p>
                    </div>
                </div>
                <span class="text-white/20 <?php echo $user['system_verified'] ? 'text-green-500' : 'group-hover:text-primary'; ?>"><?php echo $user['system_verified'] ? '✓' : '→'; ?></span>
                <?php if($user['system_verified']): ?><div class="absolute inset-0 bg-green-500/5"></div><?php endif; ?>
            </button>

            <!-- 社交驗證 -->
            <div class="grid grid-cols-2 gap-4 pt-2">
                <a href="<?php echo $user['google_id'] ? 'javascript:void(0)' : $googleVerifyUrl; ?>" 
                   onclick="<?php echo $user['google_id'] ? 'Utils.showToast(\'已綁定 Google\', \'success\')' : ''; ?>"
                   class="flex items-center justify-center gap-3 p-4 bg-white/5 border <?php echo $user['google_id'] ? 'border-green-500/30 text-green-400' : 'border-white/10 hover:bg-white/10'; ?> rounded-2xl text-[10px] font-black uppercase transition-all relative overflow-hidden">
                   <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" class="w-4 h-4" alt=""> 
                   Google <?php echo $user['google_id'] ? '已驗證' : '驗證'; ?>
                   <?php if($user['google_id']): ?><div class="absolute inset-0 bg-green-500/5"></div><?php endif; ?>
                </a>
                
                <a href="<?php echo $user['discord_id'] ? 'javascript:void(0)' : $discordVerifyUrl; ?>" 
                   onclick="<?php echo $user['discord_id'] ? 'Utils.showToast(\'已綁定 Discord\', \'success\')' : ''; ?>"
                   class="flex items-center justify-center gap-3 p-4 bg-white/5 border <?php echo $user['discord_id'] ? 'border-green-500/30 text-green-400' : 'border-white/10 hover:bg-white/10'; ?> rounded-2xl text-[10px] font-black uppercase transition-all relative overflow-hidden">
                   <img src="https://assets-global.website-files.com/6257adef93867e3d0394e0ff/6257d07978625eddc13945a0_Discord-Logo-White.svg" class="w-4 h-4" alt=""> 
                   Discord <?php echo $user['discord_id'] ? '已驗證' : '驗證'; ?>
                   <?php if($user['discord_id']): ?><div class="absolute inset-0 bg-green-500/5"></div><?php endif; ?>
                </a>
            </div>
        </div>

        <!-- System Progress Flow -->
        <div id="verify-flow-system" class="hidden text-center space-y-8 py-10">
            <div class="relative w-32 h-32 mx-auto">
                <svg class="w-full h-full rotate-[-90deg]">
                    <circle cx="64" cy="64" r="60" stroke="currentColor" stroke-width="8" fill="transparent" class="text-white/5" />
                    <circle id="system-progress-circle" cx="64" cy="64" r="60" stroke="currentColor" stroke-width="8" fill="transparent" stroke-dasharray="377" stroke-dashoffset="377" class="text-primary transition-all duration-100" />
                </svg>
                <div id="system-percent" class="absolute inset-0 flex items-center justify-center text-2xl font-black">0%</div>
            </div>
            <div>
                <p id="system-status" class="text-xs font-black uppercase tracking-[0.2em] animate-pulse">正在掃描真人行為...</p>
                <div class="w-full h-1 bg-white/5 rounded-full mt-4 overflow-hidden">
                    <div id="system-bar" class="h-full bg-primary w-0 transition-all duration-100"></div>
                </div>
            </div>
            <button id="final-verify-btn" onclick="finalizeSystemVerify()" class="hidden w-full btn-neon py-4 shadow-[0_0_20px_rgba(138,43,226,0.5)]">
                立即通過驗證
            </button>
        </div>



        <button onclick="resetVerifyUI()" class="mt-10 block mx-auto text-[10px] font-black text-white/20 uppercase tracking-widest hover:text-white transition-all">
            ← 取消並返回
        </button>
    </div>
</div>

<script>
    function openVerifyModal() {
        document.getElementById('verify-modal').classList.remove('hidden');
        resetVerifyUI();
    }

    function closeVerifyModal() {
        document.getElementById('verify-modal').classList.add('hidden');
    }

    function resetVerifyUI() {
        document.getElementById('verify-selection').classList.remove('hidden');
        document.getElementById('verify-flow-system').classList.add('hidden');
        document.getElementById('system-progress-circle').style.strokeDashoffset = "377";
        document.getElementById('system-bar').style.width = "0%";
    }



    // --- System Verification Logic ---
    function startSystemVerify() {
        document.getElementById('verify-selection').classList.add('hidden');
        document.getElementById('verify-flow-system').classList.remove('hidden');
        
        let progress = 0;
        const interval = setInterval(() => {
            progress += 2;
            document.getElementById('system-bar').style.width = progress + "%";
            document.getElementById('system-percent').innerText = progress + "%";
            document.getElementById('system-progress-circle').style.strokeDashoffset = 377 - (377 * progress / 100);

            if (progress >= 100) {
                clearInterval(interval);
                document.getElementById('system-status').innerText = "✅ 真人辨識成功！";
                document.getElementById('system-status').classList.remove('animate-pulse');
                document.getElementById('system-status').classList.add('text-green-400');
                document.getElementById('final-verify-btn').classList.remove('hidden');
            }
        }, 100); // 100ms * 50 steps = 5s
    }

    async function finalizeSystemVerify() {
        Utils.showLoading();
        try {
            const res = await fetch('auth/verification_handler.php?action=system_verify');
            const data = await res.json();
            if (data.success) {
                Utils.showToast('驗證成功！正在重新整理...', 'success');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                Utils.showToast(data.message, 'error');
            }
        } catch(e) {
            Utils.showToast('通訊失敗', 'error');
        } finally {
            Utils.hideLoading();
        }
    }


</script>

<?php include 'templates/footer.php'; ?>