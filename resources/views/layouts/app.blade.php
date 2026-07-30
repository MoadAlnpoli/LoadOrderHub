<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('messages.title')) - LoadOrderHub</title>
    
    <!-- Favicon & Brand Icons -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    
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

    <!-- Google AdSense Code Script (Replace ca-pub-XXXXXXXXXXXXXXXX with your publisher ID) -->
    {{-- <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-XXXXXXXXXXXXXXXX" crossorigin="anonymous"></script> --}}

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Theme Initialization Script -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) || true) {
            if (localStorage.theme !== 'light') {
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

        /* Toast Animation */
        @keyframes slideUp {
            from { transform: translateY(100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .toast-animate {
            animation: slideUp 0.3s ease-out forwards;
        }

        .skeleton {
            background-color: rgba(255, 255, 255, 0.05);
            animation: skeletonPulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>
<body class="text-slate-100 min-h-screen flex flex-col antialiased bg-[#0B0F19]">

    <!-- Dynamic Header Navigation Bar -->
    <nav class="glass-card sticky top-0 z-50 border-b border-slate-800/80 backdrop-blur-xl bg-[#0B0F19]/85">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">
                
                <!-- Logo & Games Dropdown -->
                <div class="flex items-center gap-6">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group shrink-0">
                        <img src="{{ asset('images/logo.png') }}" class="h-10 w-auto max-w-[160px] object-contain group-hover:scale-105 transition-transform" alt="LoadOrderHub Logo">
                    </a>

                    <!-- Games Dropdown (Desktop) -->
                    <div class="relative group hidden lg:block">
                        <button class="flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-slate-300 hover:text-white transition-colors">
                            <i class="fa-solid fa-gamepad text-violet-400"></i>
                            <span>{{ app()->getLocale() == 'ar' ? 'الألعاب' : 'Games' }}</span>
                            <i class="fa-solid fa-chevron-down text-[10px] opacity-70 group-hover:rotate-180 transition-transform duration-300"></i>
                        </button>
                        
                        <div class="absolute top-full left-0 rtl:left-auto rtl:right-0 mt-2 w-64 bg-slate-950/95 backdrop-blur-xl border border-slate-800 rounded-2xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top-left rtl:origin-top-right group-hover:translate-y-0 translate-y-2 z-50">
                            <div class="p-3 space-y-1">
                                @php $navGames = \App\Models\Game::withCount('versions')->take(6)->get(); @endphp
                                @foreach($navGames as $navG)
                                <a href="{{ route('games.show', $navG->slug) }}" class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-900 transition-colors group/item">
                                    <img src="{{ $navG->thumbnail_url }}" class="w-8 h-8 rounded-lg object-cover border border-slate-800 shrink-0" loading="lazy">
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs font-bold text-slate-200 group-hover/item:text-violet-400 transition-colors truncate">{{ $navG->name }}</div>
                                        <div class="text-[10px] text-slate-500">{{ $navG->versions_count }} {{ app()->getLocale() == 'ar' ? 'نسخة' : 'Versions' }}</div>
                                    </div>
                                </a>
                                @endforeach
                                <div class="border-t border-slate-800/60 mt-2 pt-2 text-center">
                                    <a href="{{ route('home') }}#all-games-section" class="text-xs font-bold text-violet-400 hover:text-violet-300 transition-colors">
                                        {{ app()->getLocale() == 'ar' ? 'استعرض كل الألعاب' : 'View All Games' }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Global Autocomplete Search Input -->
                <div class="flex-1 max-w-md relative" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
                    <div class="relative">
                        <span class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0 pr-3' : 'left-0 pl-3' }} flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input 
                            type="text" 
                            id="global-nav-search" 
                            oninput="doGlobalNavSearch(this.value)" 
                            placeholder="{{ app()->getLocale() == 'ar' ? 'ابحث عن لعبة، مود، تجميعة...' : 'Search games, mods, collections...' }}" 
                            class="w-full bg-slate-950/70 border border-slate-800 rounded-xl {{ app()->getLocale() == 'ar' ? 'pr-9 pl-4' : 'pl-9 pr-4' }} py-2 text-xs text-slate-200 focus:outline-none focus:border-violet-600 transition-colors">
                    </div>
                    <div id="global-search-results" class="absolute w-full mt-2 bg-slate-950/98 backdrop-blur-xl border border-slate-800 rounded-xl shadow-2xl overflow-hidden z-50 hidden {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }} text-xs divide-y divide-slate-800/40 max-h-80 overflow-y-auto"></div>
                </div>

                <!-- Action Controls & Sidebar Toggle -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('modpacks.create') }}" class="btn-shimmer px-4 py-2 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-xs text-white font-bold transition-all shadow-md shadow-violet-500/15 flex items-center gap-1.5 shrink-0">
                        <i class="fa-solid fa-plus text-[10px]"></i>
                        <span class="hidden sm:inline">{{ app()->getLocale() == 'ar' ? 'إنشاء تجميعة' : 'Create Pack' }}</span>
                    </a>

                    <!-- Sidebar Drawer Trigger Button -->
                    <button onclick="toggleSidebarDrawer()" id="sidebar-toggle-btn" class="w-10 h-10 flex items-center justify-center rounded-xl border border-slate-800 hover:border-slate-700 bg-slate-950/50 text-slate-300 hover:text-white transition-all focus:outline-none" title="Menu">
                        <i class="fa-solid fa-bars text-base"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Overlay Backdrop for Sidebar Drawer -->
    <div id="sidebar-backdrop" onclick="toggleSidebarDrawer()" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 hidden transition-opacity duration-300"></div>

    <!-- Structured Slide-In Navigation Sidebar Drawer (Right Aligned) -->
    <aside id="sidebar-drawer" class="fixed top-0 bottom-0 right-0 z-50 w-80 bg-slate-950/98 border-l border-slate-800/80 shadow-2xl p-6 flex flex-col justify-between transition-transform duration-300 ease-in-out transform translate-x-full" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="space-y-6 overflow-y-auto pr-1">
            
            <!-- Drawer Header -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-800/80">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-violet-600 flex items-center justify-center text-white">
                        <i class="fa-solid fa-gamepad text-sm"></i>
                    </div>
                    <span class="text-base font-bold text-white tracking-tight">LoadOrderHub</span>
                </div>
                <button onclick="toggleSidebarDrawer()" class="w-8 h-8 rounded-lg bg-slate-900 border border-slate-800 text-slate-400 hover:text-white flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- SECTION 1: QUICK NAVIGATION HUB -->
            <div class="space-y-2">
                <div class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider px-2">{{ app()->getLocale() == 'ar' ? 'التنقل السريع' : 'Quick Navigation' }}</div>
                
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-slate-900/80 font-semibold text-xs transition-colors">
                    <i class="fa-solid fa-house text-violet-400 w-4 text-center"></i>
                    <span>{{ app()->getLocale() == 'ar' ? 'الرئيسية' : 'Home' }}</span>
                </a>

                <a href="{{ route('mods.explorer') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-slate-900/80 font-semibold text-xs transition-colors">
                    <i class="fa-solid fa-compass text-cyan-400 w-4 text-center"></i>
                    <span>{{ app()->getLocale() == 'ar' ? 'مستكشف المودات' : 'Mods Explorer' }}</span>
                </a>

                <a href="{{ route('mods.trending') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-slate-900/80 font-semibold text-xs transition-colors">
                    <i class="fa-solid fa-fire text-amber-400 w-4 text-center"></i>
                    <span>{{ app()->getLocale() == 'ar' ? 'المودات الشائعة' : 'Trending Mods' }}</span>
                </a>

                <a href="{{ route('mods.top-weekly') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-slate-900/80 font-semibold text-xs transition-colors">
                    <i class="fa-solid fa-trophy text-amber-300 w-4 text-center"></i>
                    <span>{{ app()->getLocale() == 'ar' ? 'أفضل المودات الأسبوعية' : 'Top Weekly Mods' }}</span>
                </a>

                <a href="{{ route('mods.compare') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-slate-900/80 font-semibold text-xs transition-colors">
                    <i class="fa-solid fa-code-compare text-blue-400 w-4 text-center"></i>
                    <span>{{ app()->getLocale() == 'ar' ? 'مقارنة المودات' : 'Compare Mods' }}</span>
                </a>
            </div>

            <!-- SECTION 2: SUPPORTED GAMES SHORTCUTS -->
            <div class="space-y-2 pt-2 border-t border-slate-800/60">
                <div class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider px-2">{{ app()->getLocale() == 'ar' ? 'الألعاب المدعومة' : 'Supported Games' }}</div>
                <div class="space-y-1 max-h-40 overflow-y-auto">
                    @forelse($navGames as $g)
                        <a href="{{ route('games.show', $g->slug) }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-slate-300 hover:text-violet-400 hover:bg-slate-900/60 text-xs transition-colors group">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <img src="{{ $g->thumbnail_url }}" class="w-6 h-6 rounded-md object-cover border border-slate-800 shrink-0" loading="lazy">
                                <span class="font-semibold truncate">{{ $g->name }}</span>
                            </div>
                            <span class="text-[10px] text-slate-500 font-mono">{{ $g->versions_count }}v</span>
                        </a>
                    @empty
                        <div class="text-[10px] text-slate-600 px-3 py-1">{{ app()->getLocale() == 'ar' ? 'لا توجد ألعاب حالياً' : 'No games found' }}</div>
                    @endforelse
                </div>
            </div>

            <!-- SECTION 3: USER ACCOUNT & ADMIN CONTROL -->
            <div class="space-y-2 pt-2 border-t border-slate-800/60">
                <div class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider px-2">{{ app()->getLocale() == 'ar' ? 'الحساب والإدارة' : 'Account & Control' }}</div>

                @guest
                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <a href="{{ route('login') }}" class="py-2 text-center rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:text-white text-xs font-bold transition-all">{{ __('Login') }}</a>
                        <a href="{{ route('register') }}" class="py-2 text-center rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 text-white text-xs font-bold transition-all shadow">{{ __('Register') }}</a>
                    </div>
                @else
                    <a href="{{ route('profile.show', auth()->user()->id) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-slate-900/80 font-semibold text-xs transition-colors">
                        <i class="fa-solid fa-user text-indigo-400 w-4 text-center"></i>
                        <span>{{ app()->getLocale() == 'ar' ? 'الملف الشخصي' : 'My Profile' }}</span>
                    </a>

                    @if(auth()->user()->is_admin)
                        @php
                            $adminBadgeCount = \Illuminate\Support\Facades\Cache::remember('admin_badge_count', 300, function() {
                                return \App\Models\ModPack::where('is_published', false)->count();
                            });
                        @endphp
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-300 hover:text-violet-400 hover:bg-slate-900/80 font-semibold text-xs transition-colors">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-screwdriver-wrench text-violet-400 w-4 text-center"></i>
                                <span>{{ app()->getLocale() == 'ar' ? 'لوحة التحكم (Admin)' : 'Admin Dashboard' }}</span>
                            </div>
                            @if($adminBadgeCount > 0)
                                <span class="bg-red-500 text-white text-[9px] font-bold px-2 py-0.5 rounded-full animate-pulse">{{ $adminBadgeCount }}</span>
                            @endif
                        </a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" class="pt-1">
                        @csrf
                        <button type="submit" class="w-full text-right rtl:text-right ltr:text-left px-3 py-2.5 rounded-xl text-red-400 hover:bg-red-500/10 font-bold text-xs transition-colors flex items-center gap-3">
                            <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
                            <span>{{ app()->getLocale() == 'ar' ? 'تسجيل الخروج' : 'Logout' }}</span>
                        </button>
                    </form>
                @endguest
            </div>

        </div>

        <!-- Drawer Footer: Preferences & Controls -->
        <div class="pt-4 border-t border-slate-800/80 space-y-3">
            <div class="flex items-center justify-between text-xs">
                <span class="text-slate-500 font-bold">{{ app()->getLocale() == 'ar' ? 'اللغة والمظهر' : 'Preferences' }}</span>
                <div class="flex items-center gap-2">
                    <button onclick="toggleTheme()" class="w-8 h-8 rounded-lg bg-slate-900 border border-slate-800 text-slate-400 hover:text-amber-400 flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-moon dark:hidden"></i>
                        <i class="fa-solid fa-sun hidden dark:inline-block"></i>
                    </button>
                    <div class="flex gap-1 text-[11px] font-bold">
                        <a href="?lang=en" class="px-2 py-1 rounded border {{ app()->getLocale() == 'en' ? 'border-violet-500 text-violet-400 bg-violet-500/10' : 'border-slate-800 text-slate-400' }}">EN</a>
                        <a href="?lang=ar" class="px-2 py-1 rounded border {{ app()->getLocale() == 'ar' ? 'border-violet-500 text-violet-400 bg-violet-500/10' : 'border-slate-800 text-slate-400' }}">AR</a>
                    </div>
                </div>
            </div>
            <div class="text-[10px] text-slate-600 text-center">LoadOrderHub &copy; {{ date('Y') }}</div>
        </div>
    </aside>

    <!-- Main Content Container -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <!-- ================================================================= -->
    <!-- SITE FOOTER -->
    <!-- ================================================================= -->
    <footer class="border-t border-slate-800/60 bg-slate-950/50 mt-8" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">

                {{-- Brand --}}
                <div class="md:col-span-1 space-y-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-violet-600 to-blue-500 flex items-center justify-center">
                            <i class="fa-solid fa-gamepad text-white text-sm"></i>
                        </div>
                        <span class="text-base font-extrabold text-white">LoadOrderHub</span>
                    </a>
                    <p class="text-slate-500 text-xs leading-relaxed">
                        {{ app()->getLocale() == 'ar'
                            ? 'المنصة الأولى لمجتمع لاعبي الكمبيوتر لاكتشاف ومشاركة أفضل قوائم تحميل المودات.'
                            : 'The ultimate platform for PC gamers to discover, share, and install optimized mod configurations.' }}
                    </p>
                </div>

                {{-- Explore --}}
                <div class="space-y-3">
                    <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'ar' ? 'استكشف' : 'Explore' }}</h3>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('home') }}" class="text-slate-500 hover:text-violet-400 transition-colors">{{ app()->getLocale() == 'ar' ? 'الرئيسية' : 'Home' }}</a></li>
                        <li><a href="{{ route('mods.explorer') }}" class="text-slate-500 hover:text-violet-400 transition-colors">{{ app()->getLocale() == 'ar' ? 'مستكشف المودات' : 'Mods Explorer' }}</a></li>
                        <li><a href="{{ route('mods.trending') }}" class="text-slate-500 hover:text-violet-400 transition-colors">{{ app()->getLocale() == 'ar' ? 'المودات الشائعة' : 'Trending Mods' }}</a></li>
                        <li><a href="{{ route('mods.top-weekly') }}" class="text-slate-500 hover:text-violet-400 transition-colors">{{ app()->getLocale() == 'ar' ? 'أفضل الأسبوع' : 'Top Weekly' }}</a></li>
                        <li><a href="{{ route('mods.compare') }}" class="text-slate-500 hover:text-violet-400 transition-colors">{{ app()->getLocale() == 'ar' ? 'مقارنة المودات' : 'Compare Mods' }}</a></li>
                    </ul>
                </div>

                {{-- About --}}
                <div class="space-y-3">
                    <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'ar' ? 'عن الموقع' : 'About' }}</h3>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('about') }}" class="text-slate-500 hover:text-violet-400 transition-colors">{{ app()->getLocale() == 'ar' ? 'من نحن' : 'About Us' }}</a></li>
                        <li><a href="{{ route('faq') }}" class="text-slate-500 hover:text-violet-400 transition-colors">{{ app()->getLocale() == 'ar' ? 'الأسئلة الشائعة' : 'FAQ' }}</a></li>
                        <li><a href="{{ route('contact') }}" class="text-slate-500 hover:text-violet-400 transition-colors">{{ app()->getLocale() == 'ar' ? 'اتصل بنا' : 'Contact Us' }}</a></li>
                        <li><a href="{{ route('changelog') }}" class="text-slate-500 hover:text-violet-400 transition-colors">{{ app()->getLocale() == 'ar' ? 'سجل التغييرات' : 'Changelog' }}</a></li>
                    </ul>
                </div>

                {{-- Legal --}}
                <div class="space-y-3">
                    <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'ar' ? 'قانوني' : 'Legal' }}</h3>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('privacy') }}" class="text-slate-500 hover:text-violet-400 transition-colors">{{ app()->getLocale() == 'ar' ? 'سياسة الخصوصية' : 'Privacy Policy' }}</a></li>
                        <li><a href="{{ route('terms') }}" class="text-slate-500 hover:text-violet-400 transition-colors">{{ app()->getLocale() == 'ar' ? 'شروط الاستخدام' : 'Terms of Service' }}</a></li>
                        <li>
                            <button onclick="
                                localStorage.removeItem('cookie_consent');
                                document.getElementById('cookie-consent-banner').style.display='block';
                            " class="text-slate-500 hover:text-violet-400 transition-colors text-left">
                                {{ app()->getLocale() == 'ar' ? 'إعدادات الكوكيز' : 'Cookie Settings' }}
                            </button>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="border-t border-slate-800/60 pt-6 flex flex-wrap items-center justify-between gap-4 text-xs text-slate-600">
                <div>
                    &copy; {{ date('Y') }} LoadOrderHub. {{ app()->getLocale() == 'ar' ? 'جميع الحقوق محفوظة.' : 'All rights reserved.' }}
                    &nbsp;|&nbsp;
                    {{ app()->getLocale() == 'ar' ? 'المحتوى بأذن أصحابه الأصليين.' : 'Third-party mod content belongs to their respective owners.' }}
                </div>
                <div class="flex items-center gap-1 text-slate-700">
                    <i class="fa-solid fa-heart text-red-800 text-[10px]"></i>
                    <span>{{ app()->getLocale() == 'ar' ? 'صُنع بحب لمجتمع المودينج' : 'Made with love for the modding community' }}</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Global Image Lightbox Preview Modal -->
    <div id="image-lightbox-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-950/90 backdrop-blur-md transition-opacity duration-300">
        <!-- Close Button -->
        <button onclick="closeImageLightbox()" class="absolute top-4 right-4 z-10 w-10 h-10 rounded-full bg-slate-900/80 hover:bg-violet-600 border border-slate-800 text-white flex items-center justify-center transition-colors">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>

        <!-- Counter Indicator -->
        <div id="lightbox-counter" class="absolute top-4 left-4 z-10 px-3 py-1 rounded-full bg-slate-900/80 border border-slate-800 text-xs font-bold text-slate-300">1 / 1</div>

        <!-- Prev / Next Controls -->
        <button id="lightbox-prev-btn" onclick="prevLightboxImage()" class="absolute left-4 top-1/2 -translate-y-1/2 z-10 w-12 h-12 rounded-full bg-slate-900/80 hover:bg-violet-600 border border-slate-800 text-white flex items-center justify-center transition-colors">
            <i class="fa-solid fa-chevron-left text-lg rtl:rotate-180"></i>
        </button>
        <button id="lightbox-next-btn" onclick="nextLightboxImage()" class="absolute right-4 top-1/2 -translate-y-1/2 z-10 w-12 h-12 rounded-full bg-slate-900/80 hover:bg-violet-600 border border-slate-800 text-white flex items-center justify-center transition-colors">
            <i class="fa-solid fa-chevron-right text-lg rtl:rotate-180"></i>
        </button>

        <!-- Image Showcase -->
        <div class="relative max-w-5xl max-h-[85vh] overflow-hidden rounded-2xl border border-slate-800/80 shadow-2xl flex items-center justify-center">
            <img id="lightbox-image" src="" class="max-w-full max-h-[85vh] object-contain rounded-2xl transition-all duration-300">
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-4 right-4 z-[70] flex flex-col gap-2 pointer-events-none"></div>

    <!-- Global Javascript Handlers -->
    <script>
        function toggleSidebarDrawer() {
            const drawer = document.getElementById('sidebar-drawer');
            const backdrop = document.getElementById('sidebar-backdrop');
            
            if (drawer && backdrop) {
                const isHidden = backdrop.classList.contains('hidden');
                if (isHidden) {
                    backdrop.classList.remove('hidden');
                    drawer.classList.remove('translate-x-full');
                    drawer.classList.add('translate-x-0');
                } else {
                    backdrop.classList.add('hidden');
                    drawer.classList.remove('translate-x-0');
                    drawer.classList.add('translate-x-full');
                }
            }
        }

        // Global Lightbox Image Carousel State
        let currentLightboxImages = [];
        let currentLightboxIndex = 0;

        function openImageLightbox(images, startIndex = 0) {
            if (!images || images.length === 0) return;
            currentLightboxImages = Array.isArray(images) ? images : [images];
            currentLightboxIndex = startIndex;
            
            updateLightboxContent();
            
            const modal = document.getElementById('image-lightbox-modal');
            if (modal) {
                modal.classList.remove('hidden');
            }
        }

        function closeImageLightbox() {
            const modal = document.getElementById('image-lightbox-modal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        function updateLightboxContent() {
            const img = document.getElementById('lightbox-image');
            const counter = document.getElementById('lightbox-counter');
            const prevBtn = document.getElementById('lightbox-prev-btn');
            const nextBtn = document.getElementById('lightbox-next-btn');

            if (img && currentLightboxImages.length > 0) {
                img.src = currentLightboxImages[currentLightboxIndex];
            }
            if (counter) {
                counter.innerText = `${currentLightboxIndex + 1} / ${currentLightboxImages.length}`;
            }
            if (prevBtn) {
                prevBtn.style.display = currentLightboxImages.length > 1 ? 'flex' : 'none';
            }
            if (nextBtn) {
                nextBtn.style.display = currentLightboxImages.length > 1 ? 'flex' : 'none';
            }
        }

        function prevLightboxImage() {
            if (currentLightboxImages.length === 0) return;
            currentLightboxIndex = (currentLightboxIndex - 1 + currentLightboxImages.length) % currentLightboxImages.length;
            updateLightboxContent();
        }

        function nextLightboxImage() {
            if (currentLightboxImages.length === 0) return;
            currentLightboxIndex = (currentLightboxIndex + 1) % currentLightboxImages.length;
            updateLightboxContent();
        }

        // Keyboard Controls for Lightbox
        document.addEventListener('keydown', (e) => {
            const modal = document.getElementById('image-lightbox-modal');
            if (modal && !modal.classList.contains('hidden')) {
                if (e.key === 'Escape') closeImageLightbox();
                if (e.key === 'ArrowLeft') prevLightboxImage();
                if (e.key === 'ArrowRight') nextLightboxImage();
            }
        });

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
                    if (!data || data.length === 0) {
                        dropdown.innerHTML = '<div class="p-3 text-slate-500 text-center">{{ app()->getLocale() == "ar" ? "لا توجد نتائج" : "No results found" }}</div>';
                        dropdown.classList.remove('hidden');
                        return;
                    }

                    dropdown.innerHTML = '';
                    data.forEach(item => {
                        const icon = item.type === 'game' ? 'fa-gamepad text-violet-400' : (item.type === 'modpack' ? 'fa-boxes-stacked text-emerald-400' : 'fa-cube text-cyan-400');
                        const img = item.image ? `<img src="${item.image}" class="w-8 h-8 rounded-lg object-cover shrink-0 border border-slate-800">` : `<div class="w-8 h-8 bg-slate-900 rounded-lg flex items-center justify-center shrink-0 border border-slate-800"><i class="fa-solid ${icon}"></i></div>`;
                        
                        dropdown.insertAdjacentHTML('beforeend', `
                            <a href="${item.url}" class="p-3 hover:bg-slate-900 flex items-center justify-between gap-3 text-slate-200 transition-colors">
                                <div class="flex items-center gap-3 min-w-0">
                                    ${img}
                                    <span class="font-bold text-white truncate">${item.title}</span>
                                </div>
                                <span class="text-[9px] px-2 py-0.5 rounded bg-slate-900 border border-slate-800 text-slate-400 font-semibold shrink-0">${item.type.toUpperCase()}</span>
                            </a>
                        `);
                    });
                    dropdown.classList.remove('hidden');
                })
                .catch(() => {
                    dropdown.classList.add('hidden');
                });
        }

        // Close search results when clicking outside
        document.addEventListener('click', (e) => {
            const searchBox = document.getElementById('global-nav-search');
            const searchResults = document.getElementById('global-search-results');
            if (searchResults && searchBox && !searchBox.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.add('hidden');
            }
        });
    </script>

    @yield('scripts')

    <!-- ================================================================= -->
    <!-- COOKIE CONSENT BANNER (GDPR / AdSense Compliance) -->
    <!-- ================================================================= -->
    <div id="cookie-consent-banner"
         class="fixed bottom-0 inset-x-0 z-[300] bg-slate-950/98 backdrop-blur-xl border-t border-slate-800/80 shadow-2xl"
         style="display: none;"
         dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-4 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-start gap-3 flex-1 min-w-0">
                <i class="fa-solid fa-cookie-bite text-amber-400 text-xl shrink-0 mt-0.5"></i>
                <div class="space-y-0.5">
                    <p class="text-xs font-bold text-white">
                        {{ app()->getLocale() == 'ar' ? 'نستخدم ملفات تعريف الارتباط' : 'We use cookies' }}
                    </p>
                    <p class="text-[11px] text-slate-400 leading-relaxed">
                        {{ app()->getLocale() == 'ar'
                            ? 'نستخدم ملفات الارتباط وتقنيات مماثلة لتحسين تجربتك وعرض إعلانات مخصصة. بالمتابعة توافق على سياسة الخصوصية.'
                            : 'We use cookies and similar technologies to improve your experience and serve personalized ads. By continuing, you agree to our Privacy Policy.' }}
                        <a href="{{ route('privacy') }}" class="text-violet-400 hover:text-violet-300 underline transition-colors">
                            {{ app()->getLocale() == 'ar' ? 'سياسة الخصوصية' : 'Privacy Policy' }}
                        </a>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <button onclick="declineCookies()"
                        class="px-4 py-2 text-xs font-bold text-slate-400 hover:text-white border border-slate-800 hover:border-slate-700 rounded-xl transition-all">
                    {{ app()->getLocale() == 'ar' ? 'رفض' : 'Decline' }}
                </button>
                <button onclick="acceptCookies()"
                        class="px-5 py-2 text-xs font-bold text-white bg-gradient-to-r from-violet-600 to-blue-600 hover:from-violet-500 hover:to-blue-500 rounded-xl transition-all shadow-lg shadow-violet-500/20">
                    {{ app()->getLocale() == 'ar' ? 'قبول الكل ✓' : 'Accept All ✓' }}
                </button>
            </div>
        </div>
    </div>

    <script>
        // Cookie Consent Logic
        (function() {
            const consent = localStorage.getItem('cookie_consent');
            if (!consent) {
                document.getElementById('cookie-consent-banner').style.display = 'block';
            }
        })();

        function acceptCookies() {
            localStorage.setItem('cookie_consent', 'accepted');
            localStorage.setItem('cookie_consent_date', new Date().toISOString());
            document.getElementById('cookie-consent-banner').style.display = 'none';
        }

        function declineCookies() {
            localStorage.setItem('cookie_consent', 'declined');
            document.getElementById('cookie-consent-banner').style.display = 'none';
        }
    </script>
</body>
</html>
