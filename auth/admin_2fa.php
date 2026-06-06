<?php
/**
 * admin_2fa.php - 管理員二次驗證頁面
 */
require_once __DIR__ . '/../config/config.php';

if (!isset($_SESSION['pending_admin_id'])) {
    header("Location: ../login.php");
    exit;
}

$username = $_SESSION['pending_admin_user'];
require_once __DIR__ . '/../includes/auth_logic.php';
$csrf_token = Auth::generateCsrfToken();
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理員二次驗證 | TEST GAME</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#8a2be2',
                        'secondary': '#00f2ff',
                        'accent': '#ff007f',
                        'bg-dark': '#05050b',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-[#05050b] text-white h-screen flex items-center justify-center p-5">
    <!-- Dynamic Background -->
    <div class="fixed inset-0 z-[-1] bg-[radial-gradient(circle_at_center,#1a0b3b_0%,#05050b_100%)]"></div>

    <div
        class="max-w-[450px] w-full bg-white/5 border border-white/10 backdrop-blur-xl rounded-[40px] p-10 shadow-2xl animate-fade-in-up">
        <div class="text-center mb-10">
            <div class="text-4xl mb-4">🛡️</div>
            <h2 class="text-2xl font-black uppercase tracking-widest text-secondary">管理員二次驗證</h2>
            <p class="text-white/40 text-xs mt-2">身分：<span class="text-white font-bold">
                    <?php echo htmlspecialchars($username); ?>
                </span></p>
        </div>

        <form action="admin_2fa_handler.php" method="POST" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

            <div>
                <label
                    class="block text-[10px] font-black uppercase text-white/40 tracking-[0.2em] mb-2 ml-4">管理員專屬虛擬電子郵件</label>
                <input type="email" name="admin_email" required placeholder="example@game.web.com"
                    class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 focus:border-secondary outline-none transition-all placeholder:text-white/10">
            </div>

            <button type="submit"
                class="w-full py-4 bg-gradient-to-r from-primary to-secondary rounded-2xl font-black uppercase tracking-widest hover:shadow-[0_0_20px_rgba(0,242,255,0.5)] transition-all">
                驗證身分
            </button>

            <p class="text-center text-[10px] text-white/30 uppercase tracking-widest">
                Verification Required for Admin Access
            </p>
        </form>

        <?php if (isset($_GET['error'])): ?>
            <div
                class="mt-6 p-4 bg-red-500/20 border border-red-500/30 rounded-2xl text-red-500 text-xs text-center font-bold">
                驗證失敗，請檢查電子郵件是否正確。
            </div>
        <?php endif; ?>
    </div>

    <style>
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.8s ease-out;
        }
    </style>
</body>

</html>