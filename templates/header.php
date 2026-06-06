<?php
require_once __DIR__ . '/../includes/auth_logic.php';

// 自動判斷路徑深度 (修正子目錄連結失效問題)
$request_uri = $_SERVER['REQUEST_URI'];
$is_subdir = (strpos($request_uri, '/features/') !== false || strpos($request_uri, '/admin/') !== false);
$prefix = $is_subdir ? '../' : '';
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TEST GAME | 官方網站</title>
    <link rel="stylesheet" href="<?php echo $prefix; ?>style.css">
    <!-- Tailwind CSS CDN for faster UI development if needed -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Override Tailwind defaults to match original style if needed
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#8a2be2',
                        'secondary': '#00f2ff',
                        'accent': '#ff007f',
                        'bg-dark': '#05050b',
                    },
                    animation: {
                        'fade-in-down': 'fade-in-down 0.8s ease-out',
                        'fade-in-up': 'fade-in-up 0.8s ease-out',
                    },
                    keyframes: {
                        'fade-in-down': {
                            '0%': { opacity: '0', transform: 'translateY(-20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        'fade-in-up': {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <script src="<?php echo $prefix; ?>assets/js/utils.js"></script>
    <style type="text/tailwindcss">
        @layer utilities {
            .btn-neon {
                @apply relative px-8 py-3 rounded-full font-bold uppercase transition-all duration-300;
                @apply bg-gradient-to-r from-primary to-secondary text-white;
                @apply shadow-[0_0_15px_rgba(138,43,226,0.5)] hover:shadow-[0_0_25px_rgba(0,242,255,0.7)];
                @apply hover:-translate-y-1 hover:scale-105;
            }
        }
    </style>
</head>

<body class="bg-[#05050b] text-white">
    <header id="main-header" class="fixed top-0 left-0 w-full z-[1000] py-5 transition-all duration-500 bg-transparent">
        <nav class="max-w-[1400px] mx-auto px-5 flex justify-between items-center">
            <!-- Logo -->
            <div class="text-[1.8rem] font-black tracking-wider relative z-[1001]">
                <a href="<?php echo $prefix; ?>index.php">TEST <span class="text-secondary drop-shadow-[0_0_10px_rgba(0,242,255,0.7)]">GAME</span></a>
            </div>

            <!-- Desktop Menu -->
            <ul class="hidden md:flex items-center gap-10">
                <li><a href="<?php echo $prefix; ?>index.php#hero"
                        class="font-semibold text-white/70 hover:text-white transition-colors">首頁</a></li>
                <li><a href="<?php echo $prefix; ?>feedback.php"
                        class="font-semibold text-white/70 hover:text-white transition-colors">反饋</a>
                </li>
                <?php if (Auth::isLoggedIn()): ?>
                    <li><a href="<?php echo $prefix; ?>points.php"
                            class="font-semibold text-white/70 hover:text-white transition-colors">積分</a>
                    </li>
                    <li class="relative">
                        <a href="<?php echo $prefix; ?>features/notifications.php" class="text-white/70 hover:text-white transition-all text-xl">
                            🔔
                            <span id="notif-count" class="absolute -top-1 -right-2 bg-accent text-[8px] font-black w-4 h-4 rounded-full flex items-center justify-center border border-bg-dark hidden">0</span>
                        </a>
                    </li>
                    <li><a href="<?php echo $prefix; ?>profile.php"
                            class="font-semibold text-white/70 hover:text-white transition-colors">個人檔案</a></li>
                    <li><a href="<?php echo $prefix; ?>more.php"
                            class="font-semibold text-white/70 hover:text-white transition-colors">更多</a>
                    </li>
                    <?php if (Auth::isAdmin()): ?>
                        <li><a href="<?php echo $prefix; ?>admin/dashboard.php" class="text-accent font-bold">管理後台</a></li>
                    <?php endif; ?>
                    <li class="flex items-center gap-3">
                        <?php if (isset($_SESSION['avatar_url'])): ?>
                            <img src="<?php echo htmlspecialchars($_SESSION['avatar_url']); ?>" class="w-8 h-8 rounded-full border border-secondary" alt="">
                        <?php endif; ?>
                        <span class="text-secondary font-semibold">
                            <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </span>
                        <a href="<?php echo $prefix; ?>logout.php"
                            class="text-[10px] bg-white/10 px-2 py-1 rounded hover:bg-white/20 transition-all font-black uppercase">登出</a>
                    </li>
                <?php else: ?>
                    <li><a href="<?php echo $prefix; ?>login.php" class="btn-neon text-sm">登入 / 註冊</a></li>
                <?php endif; ?>
            </ul>

            <!-- Mobile Menu Toggle -->
            <button id="menu-toggle" class="md:hidden relative z-[1001] w-10 h-10 flex flex-col items-center justify-center gap-1.5 focus:outline-none">
                <span class="block w-6 h-0.5 bg-white transition-transform duration-300 origin-center" id="bar1"></span>
                <span class="block w-6 h-0.5 bg-white transition-opacity duration-300" id="bar2"></span>
                <span class="block w-6 h-0.5 bg-white transition-transform duration-300 origin-center" id="bar3"></span>
            </button>

            <!-- Mobile Overlay -->
            <div id="mobile-menu" class="fixed inset-0 bg-bg-dark/95 backdrop-blur-2xl flex flex-col items-center justify-center gap-8 -translate-y-full transition-transform duration-500 md:hidden z-[1000]">
                <a href="<?php echo $prefix; ?>index.php" class="text-2xl font-black text-white/70 hover:text-secondary transition-colors" onclick="toggleMenu()">首頁</a>
                <a href="<?php echo $prefix; ?>feedback.php" class="text-2xl font-black text-white/70 hover:text-secondary transition-colors" onclick="toggleMenu()">反饋</a>
                <?php if (Auth::isLoggedIn()): ?>
                    <a href="<?php echo $prefix; ?>points.php" class="text-2xl font-black text-white/70 hover:text-secondary transition-colors" onclick="toggleMenu()">積分</a>
                    <a href="<?php echo $prefix; ?>profile.php" class="text-2xl font-black text-white/70 hover:text-secondary transition-colors" onclick="toggleMenu()">個人檔案</a>
                    <a href="<?php echo $prefix; ?>more.php" class="text-2xl font-black text-white/70 hover:text-secondary transition-colors" onclick="toggleMenu()">更多服務</a>
                    <?php if (Auth::isAdmin()): ?>
                        <a href="<?php echo $prefix; ?>admin/dashboard.php" class="text-2xl font-black text-accent" onclick="toggleMenu()">管理後台</a>
                    <?php endif; ?>
                    <div class="h-px w-24 bg-white/10"></div>
                    <div class="flex flex-col items-center gap-4 text-center">
                        <span class="text-secondary font-black tracking-widest uppercase"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <a href="<?php echo $prefix; ?>logout.php" class="px-8 py-3 bg-white/5 border border-white/10 rounded-full text-xs font-black uppercase tracking-[0.2em] hover:bg-white/10 transition-all">SIGN OUT</a>
                    </div>
                <?php else: ?>
                    <a href="<?php echo $prefix; ?>login.php" class="btn-neon" onclick="toggleMenu()">JOIN THE BATTLE</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const bar1 = document.getElementById('bar1');
        const bar2 = document.getElementById('bar2');
        const bar3 = document.getElementById('bar3');
        const header = document.getElementById('main-header');

        function toggleMenu() {
            const isOpen = mobileMenu.classList.contains('-translate-y-full');
            if (isOpen) {
                mobileMenu.classList.remove('-translate-y-full');
                bar1.style.transform = 'translateY(8px) rotate(45deg)';
                bar2.style.opacity = '0';
                bar3.style.transform = 'translateY(-8px) rotate(-45deg)';
                header.style.backgroundColor = 'transparent';
                document.body.style.overflow = 'hidden';
            } else {
                mobileMenu.classList.add('-translate-y-full');
                bar1.style.transform = 'none';
                bar2.style.opacity = '1';
                bar3.style.transform = 'none';
                document.body.style.overflow = 'auto';
                if (window.scrollY > 50) header.style.backgroundColor = 'rgba(5, 5, 11, 0.8)';
            }
        }

        menuToggle.addEventListener('click', toggleMenu);

        // Header scroll effect
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('py-3', 'backdrop-blur-md');
                header.style.backgroundColor = 'rgba(5, 5, 11, 0.8)';
                header.style.borderBottom = '1px solid rgba(255, 255, 255, 0.1)';
            } else {
                header.classList.remove('py-3', 'backdrop-blur-md');
                header.style.backgroundColor = 'transparent';
                header.style.borderBottom = 'none';
            }
        });
    </script>

    <?php if (Auth::isLoggedIn()): ?>
    <script>
    async function updateNotificationCount() {
        try {
            const res = await fetch('<?php echo $prefix; ?>auth/notification_handler.php?action=unread_count');
            const data = await res.json();
            const badge = document.getElementById('notif-count');
            if (data.success && data.count > 0) {
                badge.textContent = data.count > 99 ? '99+' : data.count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        } catch(e) {}
    }
    updateNotificationCount();
    setInterval(updateNotificationCount, 30000); // 30s check
    </script>
    <?php endif; ?>