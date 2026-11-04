<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#f8fafc]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="description" content="Dashboard Administrator FindUMB — Sistem Moderasi dan Tata Kelola Lost & Found Universitas Mercu Buana.">
    <meta name="theme-color" content="#09090b">

    <title>@yield('title', 'Dashboard Administrator — FindUMB')</title>

    <!-- Google Fonts: Inter (Shadcn default typography) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (Vite / CDN Fallback for Admin Layout) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: ["class"],
            theme: {
                extend: {
                    colors: {
                        border: "hsl(240 5.9% 90%)",
                        input: "hsl(240 5.9% 90%)",
                        ring: "hsl(240 5.9% 10%)",
                        background: "hsl(0 0% 100%)",
                        foreground: "hsl(240 10% 3.9%)",
                        primary: {
                            DEFAULT: "hsl(240 5.9% 10%)",
                            foreground: "hsl(0 0% 98%)",
                            umb: "#1572b8",
                            umbdark: "#0f5791",
                        },
                        secondary: {
                            DEFAULT: "hsl(240 4.8% 95.9%)",
                            foreground: "hsl(240 5.9% 10%)",
                        },
                        destructive: {
                            DEFAULT: "hsl(0 84.2% 60.2%)",
                            foreground: "hsl(0 0% 98%)",
                        },
                        muted: {
                            DEFAULT: "hsl(240 4.8% 95.9%)",
                            foreground: "hsl(240 3.8% 46.1%)",
                        },
                        accent: {
                            DEFAULT: "hsl(240 4.8% 95.9%)",
                            foreground: "hsl(240 5.9% 10%)",
                        },
                        card: {
                            DEFAULT: "hsl(0 0% 100%)",
                            foreground: "hsl(240 10% 3.9%)",
                        },
                    },
                    borderRadius: {
                        lg: "0.75rem",
                        md: "calc(0.75rem - 2px)",
                        sm: "calc(0.75rem - 4px)",
                    },
                    fontFamily: {
                        sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            font-family: var(--font-sans);
            color: #09090b;
            background-color: #f8fafc;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Custom scrollbar for data tables and dialogs */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Toast styling */
        .admin-toast-popup {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 100;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            background: #09090b;
            color: #ffffff;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 500;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transform: translateY(12px);
            visibility: hidden;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            max-width: 420px;
        }

        .admin-toast-popup.show {
            opacity: 1;
            transform: translateY(0);
            visibility: visible;
        }

        /* Table row hover & active */
        .shadcn-table tr {
            transition: background-color 0.15s ease;
        }
        .shadcn-table tr:hover {
            background-color: #f8fafc;
        }
    </style>

    @stack('styles')
</head>
<body class="h-full flex flex-col antialiased bg-[#f8fafc] text-foreground">

    <!-- Application Shell Grid: Sidebar (Fixed/Collapsible) + Main Content Area -->
    <div class="min-h-screen flex flex-col lg:flex-row bg-[#f8fafc]">

        <!-- =======================================================================
             1. SHADCN SIDEBAR (Desktop & Mobile Drawer)
             ======================================================================= -->
        
        <!-- Mobile Sidebar Backdrop -->
        <div id="mobile-sidebar-backdrop" onclick="toggleAdminSidebar()" class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm lg:hidden hidden transition-opacity"></div>

        <aside id="admin-sidebar" class="fixed top-0 bottom-0 left-0 z-50 w-72 bg-white border-r border-border flex flex-col transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 lg:static lg:h-screen lg:shrink-0">
            <!-- Brand & Campus Header -->
            <div class="h-16 px-5 border-b border-border flex items-center justify-between shrink-0">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#0e5090] via-[#1572b8] to-[#29a0dc] flex items-center justify-center text-white shadow-sm shadow-[#1572b8]/20">
                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                    </div>
                    <div class="flex flex-col">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-base tracking-tight text-slate-900">Find<span class="text-[#1572b8]">UMB</span></span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-900 text-white tracking-wide uppercase">Admin</span>
                        </div>
                        <span class="text-[11px] text-muted-foreground font-medium">Universitas Mercu Buana</span>
                    </div>
                </a>
                <button type="button" onclick="toggleAdminSidebar()" class="lg:hidden p-1.5 rounded-md hover:bg-slate-100 text-slate-500" aria-label="Tutup Menu">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Organization Campus Switcher Widget -->
            <div class="px-4 py-3 border-b border-border bg-slate-50/70">
                <div class="flex items-center justify-between p-2 rounded-lg bg-white border border-border shadow-xs text-xs">
                    <div class="flex items-center gap-2 min-w-0">
                        <div class="w-6 h-6 rounded-md bg-[#e0f2fe] text-[#0284c7] flex items-center justify-center shrink-0">
                            <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="font-semibold text-slate-900 truncate">Pusat Kampus Meruya</span>
                            <span class="text-[10px] text-slate-500 truncate">Semua Wilayah Operasional</span>
                        </div>
                    </div>
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 ring-4 ring-emerald-100 shrink-0" title="Sistem Aktif Normal"></span>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1 overflow-y-auto px-3 py-4 space-y-6">
                <!-- Group 1: Moderasi & Pelaporan -->
                <div class="space-y-1">
                    <div class="px-3 pb-1 text-[11px] font-semibold text-muted-foreground tracking-wider uppercase">Menu Utama</div>
                    <a href="#overview" onclick="switchAdminTab('overview')" id="nav-tab-overview" class="admin-nav-item flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors bg-slate-900 text-white shadow-xs">
                        <div class="flex items-center gap-3">
                            <i data-lucide="layout-dashboard" class="w-4 h-4 shrink-0"></i>
                            <span>Ringkasan Dashboard</span>
                        </div>
                    </a>

                    <a href="#moderation" onclick="switchAdminTab('moderation')" id="nav-tab-moderation" class="admin-nav-item flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors text-slate-700 hover:text-slate-900 hover:bg-slate-100">
                        <div class="flex items-center gap-3">
                            <i data-lucide="shield-alert" class="w-4 h-4 shrink-0 text-rose-500"></i>
                            <span>Aduan &amp; Moderasi</span>
                        </div>
                        <span id="sidebar-abuse-badge" class="px-2 py-0.5 text-[11px] font-bold rounded-full bg-rose-100 text-rose-700">3</span>
                    </a>

                    <a href="#reports" onclick="switchAdminTab('reports')" id="nav-tab-reports" class="admin-nav-item flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors text-slate-700 hover:text-slate-900 hover:bg-slate-100">
                        <div class="flex items-center gap-3">
                            <i data-lucide="package-search" class="w-4 h-4 shrink-0 text-slate-500"></i>
                            <span>Kelola Laporan Barang</span>
                        </div>
                        <span id="sidebar-reports-count" class="px-2 py-0.5 text-[11px] font-medium rounded-full bg-slate-100 text-slate-600">28</span>
                    </a>
                </div>

                <!-- Group 2: Pengguna & Sistem -->
                <div class="space-y-1">
                    <div class="px-3 pb-1 text-[11px] font-semibold text-muted-foreground tracking-wider uppercase">Pengguna &amp; Keamanan</div>
                    <a href="#users" onclick="switchAdminTab('users')" id="nav-tab-users" class="admin-nav-item flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors text-slate-700 hover:text-slate-900 hover:bg-slate-100">
                        <div class="flex items-center gap-3">
                            <i data-lucide="users" class="w-4 h-4 shrink-0 text-slate-500"></i>
                            <span>Manajemen Pengguna</span>
                        </div>
                    </a>

                    <a href="#audit" onclick="switchAdminTab('audit')" id="nav-tab-audit" class="admin-nav-item flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors text-slate-700 hover:text-slate-900 hover:bg-slate-100">
                        <div class="flex items-center gap-3">
                            <i data-lucide="history" class="w-4 h-4 shrink-0 text-slate-500"></i>
                            <span>Log Audit Moderasi</span>
                        </div>
                    </a>

                    <a href="#categories" onclick="switchAdminTab('categories')" id="nav-tab-categories" class="admin-nav-item flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors text-slate-700 hover:text-slate-900 hover:bg-slate-100">
                        <div class="flex items-center gap-3">
                            <i data-lucide="layers" class="w-4 h-4 shrink-0 text-slate-500"></i>
                            <span>Kategori Barang</span>
                        </div>
                    </a>
                </div>

                <!-- Group 3: Navigasi Cepat -->
                <div class="space-y-1 pt-2 border-t border-border">
                    <div class="px-3 pb-1 text-[11px] font-semibold text-muted-foreground tracking-wider uppercase">Pintasan Portal</div>
                    <a href="{{ route('home') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium text-slate-700 hover:text-[#1572b8] hover:bg-sky-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <i data-lucide="external-link" class="w-4 h-4 shrink-0"></i>
                            <span>Buka Portal Civitas</span>
                        </div>
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                    </a>
                </div>
            </div>

            <!-- Footer: Admin Profile Card -->
            <div class="p-3 border-t border-border bg-slate-50/50 shrink-0">
                <div class="p-2.5 rounded-xl bg-white border border-border shadow-xs flex items-center justify-between">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-full bg-slate-900 text-white flex items-center justify-center font-semibold text-xs shrink-0 shadow-inner">
                            AD
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="text-xs font-semibold text-slate-900 truncate" id="admin-user-display-name">Biro Kemahasiswaan</span>
                            <span class="text-[11px] text-muted-foreground truncate" id="admin-user-display-email">admin.lostfound@mercubuana.ac.id</span>
                        </div>
                    </div>
                    <button type="button" onclick="openAdminSettingsModal()" class="p-1.5 rounded-md hover:bg-slate-100 text-slate-500 hover:text-slate-900 transition-colors" title="Pengaturan Akun Admin" aria-label="Pengaturan Admin">
                        <i data-lucide="settings" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        </aside>

        <!-- =======================================================================
             2. MAIN WRAPPER: Header + Content
             ======================================================================= -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Navbar (Shadcn Header) -->
            <header class="h-16 bg-white border-b border-border sticky top-0 z-30 flex items-center justify-between px-4 sm:px-6 lg:px-8">
                <!-- Left: Sidebar toggle & Breadcrumbs -->
                <div class="flex items-center gap-3">
                    <button type="button" onclick="toggleAdminSidebar()" class="lg:hidden p-2 rounded-lg border border-border text-slate-600 hover:bg-slate-100" aria-label="Buka Menu">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    <div class="hidden sm:flex items-center gap-2 text-xs font-medium text-slate-500">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-900 transition-colors">Admin</a>
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                        <span class="text-slate-900 font-semibold" id="top-breadcrumb-title">Ringkasan Dashboard</span>
                    </div>
                </div>

                <!-- Right: Global Actions & Profile -->
                <div class="flex items-center gap-3">
                    <!-- Status Mode Pill -->
                    <div class="hidden md:flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Mode Admin Aktif</span>
                    </div>

                    <!-- Quick Global Search Input -->
                    <div class="relative hidden sm:block w-52 md:w-64">
                        <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" id="admin-global-search" oninput="handleAdminGlobalSearch(this.value)" placeholder="Cari laporan, NIM, aduan..." class="w-full pl-9 pr-8 py-1.5 text-xs bg-slate-50 border border-border rounded-lg text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-all">
                        <span class="absolute right-2.5 top-1/2 -translate-y-1/2 text-[10px] font-mono text-slate-400 bg-white border border-border px-1.5 py-0.5 rounded shadow-2xs">⌘K</span>
                    </div>

                    <!-- Notifications Dropdown Trigger -->
                    <button type="button" onclick="toggleAdminNotifications()" class="relative p-2 rounded-lg border border-border text-slate-600 hover:bg-slate-100 transition-colors" title="Pemberitahuan Aduan">
                        <i data-lucide="bell" class="w-4 h-4"></i>
                        <span id="header-notif-indicator" class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-rose-500"></span>
                    </button>

                    <!-- Back to Civitas Portal Link -->
                    <a href="{{ route('home') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-border bg-white text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition-colors shadow-2xs">
                        <i data-lucide="home" class="w-3.5 h-3.5"></i>
                        <span>Web Civitas</span>
                    </a>

                    <!-- Admin Profile Quick Dropdown -->
                    <div class="relative">
                        <button type="button" onclick="toggleAdminDropdownMenu()" class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs font-bold hover:ring-2 hover:ring-slate-400 transition-all">
                            AD
                        </button>

                        <div id="admin-user-dropdown" class="hidden absolute right-0 mt-2 w-56 bg-white border border-border rounded-xl shadow-lg py-1.5 z-50 text-xs">
                            <div class="px-3.5 py-2 border-b border-border">
                                <div class="font-semibold text-slate-900" id="dropdown-admin-name">Biro Kemahasiswaan</div>
                                <div class="text-slate-500 text-[11px] truncate" id="dropdown-admin-email">admin.lostfound@mercubuana.ac.id</div>
                            </div>
                            <a href="#users" onclick="switchAdminTab('users')" class="flex items-center gap-2 px-3.5 py-2 hover:bg-slate-50 text-slate-700">
                                <i data-lucide="users" class="w-4 h-4 text-slate-500"></i>
                                <span>Manajemen Pengguna</span>
                            </a>
                            <a href="#audit" onclick="switchAdminTab('audit')" class="flex items-center gap-2 px-3.5 py-2 hover:bg-slate-50 text-slate-700">
                                <i data-lucide="history" class="w-4 h-4 text-slate-500"></i>
                                <span>Log Audit Moderasi</span>
                            </a>
                            <a href="{{ route('home') }}" class="flex items-center gap-2 px-3.5 py-2 hover:bg-slate-50 text-slate-700">
                                <i data-lucide="external-link" class="w-4 h-4 text-slate-500"></i>
                                <span>Kembali ke Halaman Civitas</span>
                            </a>
                            <div class="border-t border-border mt-1"></div>
                            <button type="button" onclick="handleAdminSignOut()" class="w-full flex items-center gap-2 px-3.5 py-2 hover:bg-rose-50 text-rose-600 font-medium text-left">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                                <span>Keluar dari Admin</span>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Scrollable Content Area -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Floating Global Toast Popup -->
    <div id="admin-toast" class="admin-toast-popup" role="status" aria-live="polite">
        <div id="admin-toast-icon" class="text-emerald-400">
            <i data-lucide="check-circle-2" class="w-5 h-5"></i>
        </div>
        <div class="flex-1" id="admin-toast-message">Pemberitahuan admin.</div>
    </div>

    <!-- Lucide Icons Scripts -->
    <script src="{{ asset('js/lucide.min.js') }}"></script>
    <script>
        if (typeof lucide === 'undefined') {
            document.write('<script src="https://unpkg.com/lucide@latest"><\/script>');
        }
    </script>

    <script>
        function toggleAdminSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('mobile-sidebar-backdrop');
            if (!sidebar || !backdrop) return;
            const isClosed = sidebar.classList.contains('-translate-x-full');
            if (isClosed) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }

        function toggleAdminDropdownMenu() {
            const dropdown = document.getElementById('admin-user-dropdown');
            if (dropdown) dropdown.classList.toggle('hidden');
        }

        function toggleAdminNotifications() {
            switchAdminTab('moderation');
            showAdminToast('Menampilkan daftar aduan penyalahgunaan yang perlu ditinjau.');
        }

        function showAdminToast(message, isError = false) {
            const toast = document.getElementById('admin-toast');
            const msgEl = document.getElementById('admin-toast-message');
            const iconEl = document.getElementById('admin-toast-icon');
            if (!toast || !msgEl) return;

            msgEl.textContent = message;
            if (iconEl) {
                iconEl.innerHTML = isError ? '<i data-lucide="alert-triangle" class="w-5 h-5 text-rose-400"></i>' : '<i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-400"></i>';
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }

            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        function handleAdminSignOut() {
            if (confirm('Keluar dari sesi Administrator FindUMB?')) {
                showAdminToast('Sesi administrator ditutup.');
                setTimeout(() => {
                    window.location.href = "{{ route('home') }}";
                }, 800);
            }
        }

        // Global click listener to close dropdowns
        document.addEventListener('click', (e) => {
            const dropdown = document.getElementById('admin-user-dropdown');
            if (dropdown && !dropdown.contains(e.target) && !e.target.closest('button[onclick="toggleAdminDropdownMenu()"]')) {
                dropdown.classList.add('hidden');
            }
        });

        // Keyboard Shortcut ⌘K / Ctrl+K to focus search
        document.addEventListener('keydown', (e) => {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                const search = document.getElementById('admin-global-search');
                if (search) search.focus();
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>

    @stack('scripts')
</body>
</html>