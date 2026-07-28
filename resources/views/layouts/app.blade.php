<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('messages.title')) - LoadOrderHub</title>
    
    <!-- Meta tags for SEO -->
    @hasSection('meta')
        @yield('meta')
    @else
        <meta name="description" content="{{ __('messages.subtitle') }}">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ url()->current() }}">
        <meta property="og:title" content="@yield('title', __('messages.title')) - LoadOrderHub">
        <meta property="og:description" content="{{ __('messages.subtitle') }}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:image" content="{{ asset('images/og-image.png') }}">
        <meta name="twitter:card" content="summary_large_image">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome for Premium Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Theme Initialization Script (Before CSS to prevent FOUC) -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) || true) {
            // Note: We're forcing dark mode as default if nothing is set, keeping the original aesthetic.
            if(localStorage.theme !== 'light') {
                document.documentElement.classList.add('dark');
            }
        }
        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        }
    </script>

    <!-- Tailwind & Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom inline styles for premium visual details and fallbacks -->
    <style>
        body {
            font-family: {{ app()->getLocale() == 'ar' ? "'Cairo', sans-serif" : "'Outfit', sans-serif" }};
            background-color: #080c14;
            background-image: 
                radial-gradient(at 0% 0%, rgba(139, 92, 246, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(59, 130, 246, 0.08) 0px, transparent 50%);
        }
        
        .glass-card {
            background: rgba(20, 28, 45, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .gradient-border-hover:hover {
            border-color: rgba(139, 92, 246, 0.5);
            box-shadow: 0 0 20px rgba(139, 92, 246, 0.15);
        }

        .text-glow {
            text-shadow: 0 0 10px rgba(139, 92, 246, 0.5);
        }
        
        /* Smooth RTL adjustments */
        [dir="rtl"] .rtl-flip {
            transform: scaleX(-1);
        }

        /* Toast Animation */
        @keyframes slideUp {
            from { transform: translateY(100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .toast-animate {
            animation: slideUp 0.3s ease-out forwards;
        }

        /* Skeleton Animation */
        @keyframes skeletonPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .skeleton {
            background-color: rgba(255, 255, 255, 0.05);
            animation: skeletonPulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>
<body class="text-slate-100 min-h-screen flex flex-col antialiased bg-[#0B0F19]">

    <!-- Premium Navigation Bar -->
    <nav class="glass-card sticky top-0 z-50 border-b border-slate-800/80 backdrop-blur-xl bg-[#0B0F19]/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Logo -->
                <div class="flex items-center space-x-6 rtl:space-x-reverse">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2 rtl:space-x-reverse group shrink-0">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-violet-600 to-blue-500 flex items-center justify-center shadow-lg shadow-violet-500/20 group-hover:rotate-6 transition-transform">
                            <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="6" width="20" height="12" rx="4"></rect>
                                <path d="M6 12h4m-2-2v4"></path>
                                <circle cx="15.5" cy="11.5" r="1" fill="currentColor"></circle>
                                <circle cx="18.5" cy="12.5" r="1" fill="currentColor"></circle>
                            </svg>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-white via-slate-200 to-violet-400 bg-clip-text text-transparent tracking-wide">
                            LoadOrder<span class="text-violet-500">Hub</span>
                        </span>
                    </a>

                    <!-- Games Dropdown Menu -->
                    <div class="relative group hidden md:block">
                        <button class="flex items-center gap-1.5 px-3 py-2 text-sm font-bold text-slate-300 hover:text-white transition-colors">
                            Games
                            <i class="fa-solid fa-chevron-down text-[10px] opacity-70 group-hover:rotate-180 transition-transform duration-300"></i>
                        </button>
                        
                        <div class="absolute top-full left-0 mt-2 w-64 bg-slate-950/95 backdrop-blur-xl border border-slate-800 rounded-2xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top-left group-hover:translate-y-0 translate-y-2 z-50">
                            <div class="p-4 space-y-1">
                                @php $navGames = \App\Models\Game::take(6)->get(); @endphp
                                @foreach($navGames as $navG)
                                <a href="{{ route('games.show', $navG->slug) }}" class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-900 transition-colors group/item">
                                    <img src="{{ $navG->thumbnail_url }}" class="w-8 h-8 rounded-lg object-cover">
                                    <span class="text-sm font-bold text-slate-300 group-hover/item:text-violet-400 transition-colors">{{ $navG->name }}</span>
                                </a>
                                @endforeach
                                <div class="border-t border-slate-800/60 mt-2 pt-2">
                                    <a href="{{ route('home') }}" class="block text-center text-xs font-bold text-slate-500 hover:text-violet-400 transition-colors py-2">
                                        View All Games
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Global Autocomplete Search Input -->
                <div class="flex-1 max-w-sm mx-4 relative block" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
                    <div class="relative">
                        <span class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0 pr-3' : 'left-0 pl-3' }} flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input 
                            type="text" 
                            id="global-nav-search" 
                            oninput="doGlobalNavSearch(this.value)" 
                            placeholder="{{ app()->getLocale() == 'ar' ? 'البحث عن لعبة، مود...' : 'Search games, mods...' }}" 
                            class="w-full bg-slate-950/60 border border-slate-800 rounded-xl {{ app()->getLocale() == 'ar' ? 'pr-9 pl-4' : 'pl-9 pr-4' }} py-2 text-xs text-slate-200 focus:outline-none focus:border-violet-600">
                    </div>
                    <div id="global-search-results" class="absolute w-full mt-2 bg-slate-950 border border-slate-800 rounded-xl shadow-2xl overflow-hidden z-50 hidden {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }} text-xs divide-y divide-slate-800/40"></div>
                </div>

                <!-- Right Elements (Create Pack & Toggle Menu ⋮) -->
                <div class="flex items-center space-x-3 rtl:space-x-reverse relative" id="nav-actions-container">
                    <a href="{{ route('modpacks.create') }}" class="btn-shimmer px-4 py-2 rounded-xl bg-gradient-to-r from-violet-600 to-blue-500 hover:from-violet-500 hover:to-blue-400 text-xs text-white font-extrabold transition-all shadow-md shadow-violet-500/10 hover:shadow-violet-500/20 flex items-center gap-1.5 shrink-0">
                        <i class="fa-solid fa-plus text-[10px]"></i>
                        <span>{{ app()->getLocale() == 'ar' ? 'إنشاء تجميعة' : 'Create Pack' }}</span>
                    </a>

                    <!-- Toggle ⋮ Button -->
                    <button onclick="toggleNavMenu(event)" id="nav-menu-btn" class="w-10 h-10 flex items-center justify-center rounded-xl border border-slate-800 hover:border-slate-700 bg-slate-950/40 text-slate-400 hover:text-white transition-all focus:outline-none">
                        <i class="fa-solid fa-ellipsis-vertical text-lg"></i>
                    </button>
                    
                    @php
                        $navGames = \Schema::hasTable('games') ? \App\Models\Game::all() : collect();
                    @endphp

                    <!-- Menu Dropdown Panel -->
                    <div id="nav-dropdown-panel" class="hidden fixed md:absolute top-0 md:top-14 right-0 w-80 md:w-72 h-full md:h-auto bg-slate-950/98 md:bg-slate-950/95 border-l md:border border-slate-800/80 shadow-2xl p-6 md:p-4 space-y-4 z-50 transition-all duration-300 transform translate-x-full md:translate-x-0 md:rounded-2xl {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
                        <!-- Close button visible only on mobile -->
                        <div class="flex justify-between items-center md:hidden pb-2 border-b border-slate-850">
                            <span class="text-xs font-bold text-slate-400">{{ app()->getLocale() == 'ar' ? 'القائمة' : 'Menu' }}</span>
                            <button onclick="toggleNavMenu(event)" class="text-slate-400 hover:text-white text-sm focus:outline-none">
                                <i class="fa-solid fa-xmark text-lg"></i>
                            </button>
                        </div>

                        <!-- Auth State Details -->
                        @guest
                            <div class="grid grid-cols-2 gap-2 pb-3 border-b border-slate-850">
                                <a href="{{ route('login') }}" class="py-2 text-center rounded-xl bg-slate-900 border border-slate-805 text-slate-350 hover:text-white text-xs font-bold transition-all">{{ __('Login') }}</a>
                                <a href="{{ route('register') }}" class="py-2 text-center rounded-xl bg-gradient-to-r from-violet-600 to-blue-500 text-white text-xs font-bold transition-all shadow">{{ __('Register') }}</a>
                            </div>
                        @else
                            <div class="pb-3 border-b border-slate-850 flex items-center gap-3">
                                @if(auth()->user()->profile?->avatar)
                                    <img src="{{ auth()->user()->profile->avatar }}" alt="" class="w-10 h-10 rounded-full object-cover border border-violet-500/30">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center text-sm text-violet-400 font-black">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                                    <div class="text-xs font-black text-white truncate">{{ auth()->user()->name }}</div>
                                    <form action="{{ route('logout') }}" method="POST" class="mt-1">
                                        @csrf
                                        <button type="submit" class="text-[10px] text-slate-500 hover:text-red-400 transition-colors font-bold flex items-center gap-1">
                                            <i class="fa-solid fa-right-from-bracket"></i>
                                            <span>{{ app()->getLocale() == 'ar' ? 'تسجيل الخروج' : 'Logout' }}</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endguest

                        <!-- Games Submenu -->
                        <div class="space-y-1 pb-3 border-b border-slate-850">
                            <div class="text-[9px] font-bold text-slate-600 uppercase tracking-widest px-2 mb-1">{{ app()->getLocale() == 'ar' ? 'الألعاب المدعومة' : 'Supported Games' }}</div>
                            <div class="max-h-28 overflow-y-auto pr-1 space-y-1">
                                @forelse($navGames as $game)
                                    <a href="{{ route('games.show', $game->slug) }}" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-slate-350 hover:text-violet-400 hover:bg-slate-900/60 text-xs transition-colors">
                                        <i class="fa-solid fa-gamepad text-[10px] text-slate-500"></i>
                                        <span>{{ $game->name }}</span>
                                    </a>
                                @empty
                                    <div class="text-[10px] text-slate-600 px-2 py-1">{{ app()->getLocale() == 'ar' ? 'لا توجد ألعاب مضافة حالياً' : 'No games added yet' }}</div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Core Navigation Links -->
                        <div class="space-y-1 pb-3 border-b border-slate-850 text-xs">
                            <a href="{{ route('mods.explorer') }}" class="flex items-center gap-2 px-2 py-2 rounded-lg text-slate-350 hover:text-violet-400 hover:bg-slate-900/60 transition-colors">
                                <i class="fa-solid fa-compass text-violet-500 w-4 text-center"></i>
                                <span>{{ app()->getLocale() == 'ar' ? 'مستكشف المودات' : 'Mods Explorer' }}</span>
                            </a>
                            <a href="{{ route('mods.trending') }}" class="flex items-center gap-2 px-2 py-2 rounded-lg text-slate-350 hover:text-amber-400 hover:bg-slate-900/60 transition-colors">
                                <i class="fa-solid fa-fire text-amber-500 w-4 text-center"></i>
                                <span>{{ app()->getLocale() == 'ar' ? 'المودات الرائجة' : 'Trending Mods' }}</span>
                            </a>
                            <a href="{{ route('mods.compare') }}" class="flex items-center gap-2 px-2 py-2 rounded-lg text-slate-350 hover:text-blue-400 hover:bg-slate-900/60 transition-colors">
                                <i class="fa-solid fa-code-compare text-blue-400 w-4 text-center"></i>
                                <span>{{ app()->getLocale() == 'ar' ? 'مقارنة المودات' : 'Compare Mods' }}</span>
                            </a>
                            <a href="{{ route('mods.top-weekly') }}" class="flex items-center gap-2 px-2 py-2 rounded-lg text-slate-350 hover:text-amber-300 hover:bg-slate-900/60 transition-colors">
                                <i class="fa-solid fa-trophy text-amber-400 w-4 text-center"></i>
                                <span>{{ app()->getLocale() == 'ar' ? 'أفضل المودات' : 'Top Mods' }}</span>
                            </a>

                            @auth
                                <a href="{{ route('home') }}" class="flex items-center gap-2 px-2 py-2 rounded-lg text-slate-350 hover:text-indigo-400 hover:bg-slate-900/60 transition-colors">
                                    <i class="fa-solid fa-house text-indigo-400 w-4 text-center"></i>
                                    <span>{{ app()->getLocale() == 'ar' ? 'الرئيسية' : 'Home' }}</span>
                                </a>
                                <a href="{{ route('profile.show', auth()->user()->id) }}" class="flex items-center gap-2 px-2 py-2 rounded-lg text-slate-350 hover:text-indigo-400 hover:bg-slate-900/60 transition-colors">
                                    <i class="fa-solid fa-user text-indigo-400 w-4 text-center"></i>
                                    <span>{{ app()->getLocale() == 'ar' ? 'ملفي الشخصي' : 'My Profile' }}</span>
                                </a>
                                @if(auth()->user()->is_admin)
                                    @php
                                        $adminBadgeCount = \Illuminate\Support\Facades\Cache::remember('admin_badge_count', 300, function() {
                                            $pendingPacks = \App\Models\ModPack::where('is_published', false)->count();
                                            $missingImages = \App\Models\Mod::whereNull('image_url')->whereNull('local_image_path')->count();
                                            return $pendingPacks + $missingImages;
                                        });
                                    @endphp
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between gap-2 px-2 py-2 rounded-lg text-slate-350 hover:text-violet-400 hover:bg-slate-900/60 transition-colors">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-screwdriver-wrench text-violet-400 w-4 text-center"></i>
                                            <span>{{ app()->getLocale() == 'ar' ? 'إدارة الموقع (Admin)' : 'Site Admin' }}</span>
                                        </div>
                                        @if($adminBadgeCount > 0)
                                            <span class="bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full animate-pulse">{{ $adminBadgeCount }}</span>
                                        @endif
                                    </a>
                                @endif
                            @endauth
                        </div>

                        <!-- Language & Theme Selection -->
                        <div class="flex items-center justify-between text-xs pt-1">
                            <span class="text-slate-500 font-bold">{{ app()->getLocale() == 'ar' ? 'الإعدادات' : 'Settings' }}</span>
                            <div class="flex items-center gap-3">
                                <!-- Theme Toggle -->
                                <button onclick="toggleTheme()" class="text-slate-400 hover:text-amber-400 transition-colors" title="Toggle Light/Dark Mode">
                                    <i class="fa-solid fa-moon dark:hidden"></i>
                                    <i class="fa-solid fa-sun hidden dark:inline-block"></i>
                                </button>
                                
                                <div class="flex gap-2">
                                    <a href="?lang=en" class="px-2.5 py-1 rounded border {{ app()->getLocale() == 'en' ? 'border-violet-500 text-violet-400 font-bold bg-violet-500/10' : 'border-slate-800 text-slate-400 hover:text-white' }} transition-colors">EN</a>
                                    <a href="?lang=ar" class="px-2.5 py-1 rounded border {{ app()->getLocale() == 'ar' ? 'border-violet-500 text-violet-400 font-bold bg-violet-500/10' : 'border-slate-800 text-slate-400 hover:text-white' }} transition-colors">AR</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <!-- Toast Notifications Container -->
    <div id="toast-container" class="fixed bottom-4 right-4 z-[70] flex flex-col gap-2 pointer-events-none"></div>

    <!-- Global Quick View Modal -->
    <div id="quick-view-modal" class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity" onclick="closeQuickView()"></div>
        <!-- Modal Content -->
        <div class="relative bg-slate-900 border border-slate-700 rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all scale-95 opacity-0" id="quick-view-content">
            <button onclick="closeQuickView()" class="absolute top-4 right-4 z-10 w-8 h-8 flex items-center justify-center rounded-full bg-slate-800 text-slate-400 hover:text-white transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div id="quick-view-body" class="p-6 text-left">
                <!-- Content injected here via JS -->
            </div>
        </div>
    </div>

    <!-- AdBlock Warning Modal -->
    <div id="adblock-warning" class="hidden fixed inset-0 z-[100] bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="glass-card max-w-md w-full rounded-2xl p-6 text-center shadow-2xl border border-violet-500/20 transform transition-all">
            <div class="w-16 h-16 bg-red-500/10 rounded-full flex items-center justify-center mx-auto mb-4 border border-red-500/20">
                <i class="fa-solid fa-shield-halved text-2xl text-red-400"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">{{ app()->getLocale() == 'ar' ? 'يرجى إيقاف مانع الإعلانات' : 'Please disable your AdBlocker' }}</h3>
            <p class="text-sm text-slate-400 mb-6 leading-relaxed">
                {{ app()->getLocale() == 'ar' ? 'نحن نعتمد على الإعلانات لإبقاء الموقع مجانياً وتغطية تكاليف الخوادم السريعة. يرجى إضافة موقعنا للقائمة البيضاء لدعمنا!' : 'We rely on ads to keep this website free and cover our fast server costs. Please whitelist us to support our work!' }}
            </p>
            <button onclick="document.getElementById('adblock-warning').classList.add('hidden')" class="w-full py-3 bg-gradient-to-r from-violet-600 to-blue-500 hover:from-violet-500 hover:to-blue-400 text-white rounded-xl font-bold transition-all shadow-lg shadow-violet-500/20">
                {{ app()->getLocale() == 'ar' ? 'فهمت، سأقوم بإيقافه' : 'I understand, I will disable it' }}
            </button>
        </div>
    </div>

    <!-- Global Javascript search autocomplete -->
    <script>
        function doGlobalNavSearch(val) {
            const dropdown = document.getElementById('global-search-results');
            if (val.trim().length < 2) {
                dropdown.innerHTML = '';
                dropdown.classList.add('hidden');
                return;
            }

            fetch(`/api/search?q=${encodeURIComponent(val)}`)
                .then(r => r.json())
                .then(data => {
                    if (data.length === 0) {
                        dropdown.innerHTML = '<div class="p-3 text-slate-500">لا توجد نتائج</div>';
                        dropdown.classList.remove('hidden');
                        return;
                    }

                    dropdown.innerHTML = '';
                    data.forEach(item => {
                        const icon = item.type === 'game' ? 'fa-gamepad text-violet-400' : (item.type === 'modpack' ? 'fa-boxes-stacked text-emerald-400' : 'fa-cube text-cyan-400');
                        const img = item.image ? `<img src="${item.image}" class="w-8 h-8 rounded object-cover shrink-0">` : `<div class="w-8 h-8 bg-slate-900 rounded flex items-center justify-center shrink-0"><i class="fa-solid ${icon}"></i></div>`;
                        
                        dropdown.insertAdjacentHTML('beforeend', `
                            <a href="${item.url}" class="p-3 hover:bg-slate-900 flex items-center justify-between gap-3 text-slate-200 transition-colors">
                                <div class="flex items-center gap-3">
                                    ${img}
                                    <span class="font-bold text-white">${item.title}</span>
                                </div>
                                <span class="text-[10px] px-2 py-0.5 rounded bg-slate-900 border border-slate-800 text-slate-400 font-semibold">${item.type.toUpperCase()}</span>
                            </a>
                        `);
                    });
                    dropdown.classList.remove('hidden');
                });
        }

        // Toggle language selector dropdown
        function toggleLangDropdown() {
            const menu = document.getElementById('lang-dropdown-menu');
            if (menu) menu.classList.toggle('hidden');
        }

        // Toggle navigation collapsed panel (slide-in / dropdown)
        function toggleNavMenu(event) {
            if (event) event.stopPropagation();
            const panel = document.getElementById('nav-dropdown-panel');
            if (panel) {
                if (panel.classList.contains('hidden')) {
                    panel.classList.remove('hidden');
                    setTimeout(() => {
                        panel.classList.remove('translate-x-full');
                    }, 10);
                } else {
                    panel.classList.add('translate-x-full');
                    setTimeout(() => {
                        if (panel.classList.contains('translate-x-full')) {
                            panel.classList.add('hidden');
                        }
                    }, 300);
                }
            }
        }

        // Hide suggestions and dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#global-nav-search') && !e.target.closest('#global-search-results')) {
                const dropdown = document.getElementById('global-search-results');
                if (dropdown) {
                    dropdown.innerHTML = '';
                    dropdown.classList.add('hidden');
                }
            }
            if (!e.target.closest('#lang-selector-parent')) {
                const menu = document.getElementById('lang-dropdown-menu');
                if (menu) menu.classList.add('hidden');
            }
            const panel = document.getElementById('nav-dropdown-panel');
            const toggleBtn = document.getElementById('nav-menu-btn');
            if (panel && !panel.classList.contains('hidden')) {
                if (!panel.contains(e.target) && !toggleBtn.contains(e.target) && !e.target.closest('#nav-dropdown-panel')) {
                    panel.classList.add('translate-x-full');
                    setTimeout(() => {
                        if (panel.classList.contains('translate-x-full')) {
                            panel.classList.add('hidden');
                        }
                    }, 300);
                }
            }
        });

        // Global Report Mod Function
        function reportMod(modId) {
            const reason = prompt("Please provide a reason for reporting this mod (e.g. Broken link, inappropriate content, virus):");
            if (!reason || reason.trim() === '') return;
            
            const url = "{{ url('/mods') }}/" + modId + "/report";
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ reason: reason })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                }
            })
            .catch(err => {
                console.error('Report failed:', err);
                showToast('Failed to submit report.', 'error');
            });
        }

        // Toast Notification System
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const icon = type === 'success' ? '<i class="fa-solid fa-circle-check text-emerald-400"></i>' : '<i class="fa-solid fa-circle-exclamation text-red-400"></i>';
            const borderClass = type === 'success' ? 'border-emerald-500/20' : 'border-red-500/20';

            toast.className = `toast-animate bg-slate-900 border ${borderClass} text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-3 text-sm font-semibold max-w-sm`;
            toast.innerHTML = `${icon} <span>${message}</span>`;
            
            container.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(100%)';
                toast.style.transition = 'all 0.3s ease-in';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // AdBlock Detector
        window.addEventListener('load', function() {
            setTimeout(() => {
                // Simple AdBlock check by creating a bait element
                const bait = document.createElement('div');
                bait.innerHTML = '&nbsp;';
                bait.className = 'adsbox ad-placement doubleclick ad-placeholder';
                bait.style.position = 'absolute';
                bait.style.top = '-9999px';
                document.body.appendChild(bait);
                
                setTimeout(() => {
                    if (bait.offsetHeight === 0 || window.getComputedStyle(bait).display === 'none') {
                        // AdBlock is active
                        if (!localStorage.getItem('adblock_warning_seen')) {
                            document.getElementById('adblock-warning').classList.remove('hidden');
                            localStorage.setItem('adblock_warning_seen', 'true');
                        }
                    }
                    bait.remove();
                }, 100);
            }, 2000);
        });

        function openQuickView(slug) {
            const modal = document.getElementById('quick-view-modal');
            const content = document.getElementById('quick-view-content');
            const body = document.getElementById('quick-view-body');
            
            modal.classList.remove('hidden');
            body.innerHTML = '<div class="flex items-center justify-center p-12 text-violet-500"><i class="fa-solid fa-circle-notch fa-spin text-3xl"></i></div>';
            
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);

            fetch(`/api/mods/${slug}/quick-view`)
                .then(res => res.json())
                .then(data => {
                    body.innerHTML = data.html;
                })
                .catch(() => {
                    body.innerHTML = '<div class="text-center text-red-400 py-8">Failed to load content.</div>';
                });
        }

        function closeQuickView() {
            const modal = document.getElementById('quick-view-modal');
            const content = document.getElementById('quick-view-content');
            
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Live Search System
        let searchTimeout = null;
        function doGlobalNavSearch(query) {
            const dropdown = document.getElementById('global-search-results');
            if (query.length < 2) {
                dropdown.classList.add('hidden');
                dropdown.innerHTML = '';
                return;
            }

            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                fetch(`/api/search?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length === 0) {
                            dropdown.innerHTML = `<div class="px-4 py-3 text-slate-500 text-center">{{ app()->getLocale() == 'ar' ? 'لا توجد نتائج' : 'No results found' }}</div>`;
                        } else {
                            dropdown.innerHTML = data.map(item => `
                                <a href="${item.url}" class="flex items-center gap-3 px-4 py-2 hover:bg-slate-900 transition-colors">
                                    ${item.image ? `<img src="${item.image}" class="w-8 h-8 rounded-lg object-cover flex-shrink-0" onerror="this.src='https://placehold.co/100x100/1e293b/64748b?text=IMG'">` : `<div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-image text-slate-600"></i></div>`}
                                    <div class="flex-1 min-w-0">
                                        <div class="font-bold text-slate-200 truncate">${item.title}</div>
                                        <div class="text-[10px] text-slate-500 uppercase tracking-widest">${item.type}</div>
                                    </div>
                                </a>
                            `).join('');
                        }
                        dropdown.classList.remove('hidden');
                    })
                    .catch(err => {
                        console.error('Search failed:', err);
                    });
            }, 300);
        }

        // Keyboard Shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;

            // Press '/' or 'S' to focus global search
            if (e.key === '/' || e.key.toLowerCase() === 's') {
                e.preventDefault();
                const searchInput = document.getElementById('global-nav-search');
                if(searchInput) {
                    searchInput.focus();
                    searchInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });

        // Expose Toast globally if there's a session success flash message
        @if(session('success'))
            document.addEventListener('DOMContentLoaded', () => showToast("{{ session('success') }}", 'success'));
        @endif
        @if(session('error'))
            document.addEventListener('DOMContentLoaded', () => showToast("{{ session('error') }}", 'error'));
        @endif

        // Global Ad Click Tracking
        function trackAdClick(adName) {
            fetch('{{ route('ads.track') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ ad_name: adName })
            }).then(response => {
                if (response.ok) {
                    console.log('Ad click recorded:', adName);
                }
            }).catch(error => console.error('Error tracking ad:', error));
        }

        // Cookie Consent Banner
        document.addEventListener('DOMContentLoaded', function() {
            if (!localStorage.getItem('cookieConsent')) {
                const banner = document.createElement('div');
                banner.className = 'fixed bottom-0 left-0 right-0 z-[100] p-4 bg-slate-950/95 backdrop-blur-xl border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 animate-slide-up shadow-[0_-10px_40px_rgba(0,0,0,0.5)]';
                banner.innerHTML = `
                    <div class="text-sm text-slate-300">
                        <i class="fa-solid fa-cookie-bite text-amber-500 mr-2 rtl:ml-2"></i>
                        We use cookies and third-party advertising to improve your experience. By continuing to use our site, you accept our <a href="{{ route('privacy') }}" class="text-violet-400 hover:underline">Privacy Policy</a>.
                    </div>
                    <button id="accept-cookies" class="px-6 py-2 rounded-xl bg-violet-600 hover:bg-violet-500 text-white text-xs font-bold whitespace-nowrap transition-colors">
                        Got it!
                    </button>
                `;
                document.body.appendChild(banner);

                document.getElementById('accept-cookies').addEventListener('click', function() {
                    localStorage.setItem('cookieConsent', 'true');
                    banner.style.opacity = '0';
                    setTimeout(() => banner.remove(), 300);
                });
            }
        });

        // Back to Top Button
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.createElement('button');
            btn.innerHTML = '<i class="fa-solid fa-chevron-up"></i>';
            btn.className = 'fixed bottom-6 right-6 z-[90] w-12 h-12 rounded-full bg-violet-600 hover:bg-violet-500 text-white shadow-[0_0_20px_rgba(139,92,246,0.3)] flex items-center justify-center transition-all duration-300 translate-y-20 opacity-0';
            btn.title = 'Back to top';
            document.body.appendChild(btn);

            window.addEventListener('scroll', () => {
                if (window.scrollY > 300) {
                    btn.classList.remove('translate-y-20', 'opacity-0');
                } else {
                    btn.classList.add('translate-y-20', 'opacity-0');
                }
            });

            btn.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
    </script>

    <!-- Custom Page Scripts -->
    @yield('scripts')
</body>
</html>
