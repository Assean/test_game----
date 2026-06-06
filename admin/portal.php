<?php
require_once __DIR__ . '/../includes/auth_logic.php';

// 權限檢查：必須是管理員
if (!Auth::isLoggedIn() || !Auth::isAdmin()) {
    header("Location: ../admin_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>身份授權門戶 | SYSTEM PORTAL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;900&family=Noto+Sans+TC:wght@300;900&display=swap"
        rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'cyber-cyan': '#00f2ff',
                        'cyber-purple': '#8a2be2',
                        'cyber-pink': '#ff007f',
                        'bg-dark': '#010103',
                    },
                    fontFamily: {
                        'orbitron': ['Orbitron', 'sans-serif'],
                        'noto': ['Noto Sans TC', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #010103;
            overflow: hidden;
        }

        .portal-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            width: 100%;
            max-width: 1200px;
        }

        .portal-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.03) 0%, rgba(255, 255, 255, 0.01) 100%);
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            cursor: pointer;
            border-radius: 1.5rem;
        }

        .portal-card:hover {
            transform: translateY(-8px) scale(1.02);
            border-color: rgba(0, 242, 255, 0.4);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), 0 0 20px rgba(0, 242, 255, 0.1);
        }

        .portal-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.05), transparent);
            transition: 0.5s;
        }

        .portal-card:hover::before {
            left: 100%;
        }

        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            transition: transform 0.3s;
        }

        .portal-card:hover .card-icon {
            transform: scale(1.2) rotate(5deg);
        }

        .fade-in {
            animation: fadeIn 0.8s ease-out forwards;
            opacity: 0;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stagger-1 {
            animation-delay: 0.1s;
        }

        .stagger-2 {
            animation-delay: 0.2s;
        }

        .stagger-3 {
            animation-delay: 0.3s;
        }

        .stagger-4 {
            animation-delay: 0.4s;
        }

        .stagger-5 {
            animation-delay: 0.5s;
        }

        .stagger-6 {
            animation-delay: 0.6s;
        }

        .stagger-7 {
            animation-delay: 0.7s;
        }
    </style>
</head>

<body class="text-white min-h-screen flex flex-col items-center justify-center p-8 font-noto">

    <!-- Animated background -->
    <div class="fixed inset-0 z-[-1]">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(138,43,226,0.05)_0%,transparent_50%)]">
        </div>
        <div
            class="absolute top-0 left-0 w-full h-full bg-[linear-gradient(rgba(0,242,255,0.02)_1px,transparent_1px),linear-gradient(90deg,rgba(0,242,255,0.02)_1px,transparent_1px)] bg-[size:100px_100px]">
        </div>
    </div>

    <div class="text-center mb-16 fade-in">
        <h1 class="text-xs font-orbitron font-black tracking-[1em] text-cyber-cyan mb-4 uppercase">Identity
            Authorization</h1>
        <h2 class="text-5xl font-black tracking-tighter mb-2 italic">SELECT YOUR <span
                class="text-transparent bg-clip-text bg-gradient-to-r from-cyber-cyan to-cyber-purple">IDENTITY</span>
        </h2>
        <div class="h-1 w-32 bg-gradient-to-r from-cyber-cyan to-transparent mx-auto"></div>
    </div>

    <div class="portal-grid">
        <!-- 01 Developer -->
        <div onclick="window.location.href='dashboard.php'"
            class="portal-card p-8 fade-in stagger-1 group border-l-4 border-l-cyber-cyan">
            <div class="text-cyber-cyan font-orbitron text-[10px] mb-4 tracking-widest font-black uppercase">Level 07 /
                System Root</div>
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-black mb-1">開發者登入</h3>
                    <p class="text-white/40 text-xs uppercase tracking-widest font-orbitron">Core Developer</p>
                </div>
                <div class="text-3xl text-cyber-cyan opacity-20 group-hover:opacity-100 transition-opacity">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- 02 Admin -->
        <div onclick="window.location.href='dashboard.php'"
            class="portal-card p-8 fade-in stagger-2 group border-l-4 border-l-cyber-purple">
            <div class="text-cyber-purple font-orbitron text-[10px] mb-4 tracking-widest font-black uppercase">Level 05
                / Management</div>
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-black mb-1">管理員登入</h3>
                    <p class="text-white/40 text-xs uppercase tracking-widest font-orbitron">Central Manager</p>
                </div>
                <div class="text-3xl text-cyber-purple opacity-20 group-hover:opacity-100 transition-opacity">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- 03 Verification -->
        <div onclick="window.location.href='users.php'"
            class="portal-card p-8 fade-in stagger-3 group border-l-4 border-l-amber-500">
            <div class="text-amber-500 font-orbitron text-[10px] mb-4 tracking-widest font-black uppercase">Level 04 /
                Security</div>
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-black mb-1">驗證系統</h3>
                    <p class="text-white/40 text-xs uppercase tracking-widest font-orbitron">Security Audit</p>
                </div>
                <div class="text-3xl text-amber-500 opacity-20 group-hover:opacity-100 transition-opacity">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-4.514A11.952 11.952 0 0012 20.052c3.13-1.028 5.823-2.947 7.794-5.502M12 6.134V3m0 3.134a4.501 4.501 0 100 9.002 4.501 4.501 0 000-9.002z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- 04 Omni -->
        <div onclick="window.location.href='users.php'"
            class="portal-card p-8 fade-in stagger-4 group border-l-4 border-l-red-500">
            <div class="text-red-500 font-orbitron text-[10px] mb-4 tracking-widest font-black uppercase">Level 06 /
                Override</div>
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-black mb-1">全端管理系統</h3>
                    <p class="text-white/40 text-xs uppercase tracking-widest font-orbitron">Full-Stack Omni</p>
                </div>
                <div class="text-3xl text-red-500 opacity-20 group-hover:opacity-100 transition-opacity">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- 05 Monitoring -->
        <div onclick="window.location.href='dashboard.php'"
            class="portal-card p-8 fade-in stagger-5 group border-l-4 border-l-emerald-500">
            <div class="text-emerald-500 font-orbitron text-[10px] mb-4 tracking-widest font-black uppercase">Level 03 /
                Operations</div>
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-black mb-1">運維監控中心</h3>
                    <p class="text-white/40 text-xs uppercase tracking-widest font-orbitron">System Monitor</p>
                </div>
                <div class="text-3xl text-emerald-500 opacity-20 group-hover:opacity-100 transition-opacity">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- 06 Finance -->
        <div onclick="window.location.href='dashboard.php'"
            class="portal-card p-8 fade-in stagger-6 group border-l-4 border-l-pink-500">
            <div class="text-pink-500 font-orbitron text-[10px] mb-4 tracking-widest font-black uppercase">Level 03 /
                Economy</div>
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-black mb-1">金流交易系統</h3>
                    <p class="text-white/40 text-xs uppercase tracking-widest font-orbitron">Financial Hub</p>
                </div>
                <div class="text-3xl text-pink-500 opacity-20 group-hover:opacity-100 transition-opacity">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zM17 16v2a2 2 0 01-2 2H9a2 2 0 01-2-2v-2m3-12V4a2 2 0 012-2h2a2 2 0 012 2v2m3 4h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- 07 DevOps -->
        <div onclick="window.location.href='dashboard.php'"
            class="portal-card p-8 fade-in stagger-7 group border-l-4 border-l-sky-500">
            <div class="text-sky-500 font-orbitron text-[10px] mb-4 tracking-widest font-black uppercase">Level 04 /
                Automation</div>
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-black mb-1">自動化部署</h3>
                    <p class="text-white/40 text-xs uppercase tracking-widest font-orbitron">DevOps Terminal</p>
                </div>
                <div class="text-3xl text-sky-500 opacity-20 group-hover:opacity-100 transition-opacity">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-16 text-[10px] text-white/10 tracking-[0.5em] uppercase font-orbitron">
        Secure Tunnel Established // Identity Layer Active
    </div>

</body>

</html>