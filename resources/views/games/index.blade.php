@extends('layouts.app')

@section('title', __('messages.home'))

@section('meta')
    <meta name="description" content="LoadOrderHub is the ultimate destination for curated PC game mod packs, optimized load orders, and game modifications. Enhance your gaming experience with stability.">
    <link rel="canonical" href="{{ url()->current() }}">
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "WebSite",
      "name": "LoadOrderHub",
      "url": "{{ url('/') }}",
      "potentialAction": {
        "@@type": "SearchAction",
        "target": "{{ url('/mods-explorer') }}?search={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "Organization",
      "name": "LoadOrderHub",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('logo.png') }}"
    }
    </script>
@endsection

@section('content')
<div class="space-y-16" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    <!-- ================================================================= -->
    <!-- HERO SECTION WITH INTEGRATED SEARCH & QUICK ACTIONS -->
    <!-- ================================================================= -->
    <div class="relative overflow-hidden rounded-3xl bg-slate-950 border border-slate-800/80 p-8 md:p-16 text-center shadow-2xl group">
        <!-- Ambient Radial Background Highlights -->
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-violet-600/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute inset-0 bg-[radial-gradient(#1e293b_1px,transparent_1px)] [background-size:16px_16px] opacity-25"></div>

        <div class="relative space-y-6 z-10 max-w-4xl mx-auto">
            <!-- Badge Header -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold bg-violet-500/10 border border-violet-500/30 text-violet-300 backdrop-blur-md">
                <i class="fa-solid fa-sparkles text-amber-400"></i>
                <span>{{ app()->getLocale() == 'ar' ? 'منصة إدارة وتجميع المودات الاحترافية' : 'Next-Gen Game Modding & Load Order Platform' }}</span>
            </div>
            
            <!-- Main Title -->
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-black tracking-tight leading-tight">
                <span class="text-white">LoadOrder</span><span class="bg-gradient-to-r from-violet-400 via-indigo-400 to-cyan-400 bg-clip-text text-transparent">Hub</span>
            </h1>
            
            <!-- Description -->
            <p class="max-w-2xl mx-auto text-sm md:text-base text-slate-300 leading-relaxed font-normal">
                {{ app()->getLocale() == 'ar' 
                    ? 'استكشف أفضل تجميعات المودات وقوائم التحميل المحسّنة التي تم اختبارها وتدقيقها لضمان تجربة ألعاب مستقرة وبدون تعارضات.' 
                    : 'Discover curated mod packs and optimized load orders built and tested by top community creators. Enjoy a stable, crash-free modded gaming experience.' }}
            </p>
            
            <!-- Hero Search Form -->
            <form action="{{ route('mods.explorer') }}" method="GET" class="pt-4 max-w-2xl mx-auto">
                <div class="relative flex items-center bg-slate-900/90 border border-slate-700/80 rounded-2xl p-2 shadow-2xl focus-within:border-violet-500 transition-all">
                    <i class="fa-solid fa-magnifying-glass text-slate-400 px-3 text-sm"></i>
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="{{ app()->getLocale() == 'ar' ? 'ابحث عن أي مود، تجميعة، أو لعبة...' : 'Search for any mod, collection, or game...' }}" 
                        class="w-full bg-transparent text-sm text-white placeholder-slate-500 focus:outline-none px-2 py-1"
                        required>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white font-bold text-xs transition-all shadow-lg shadow-violet-500/25 shrink-0 flex items-center gap-2">
                        <span>{{ app()->getLocale() == 'ar' ? 'بحث' : 'Search' }}</span>
                        <i class="fa-solid fa-arrow-right text-[10px] rtl:rotate-180"></i>
                    </button>
                </div>
            </form>

            <!-- Quick Action Links -->
            <div class="pt-4 flex flex-wrap items-center justify-center gap-3 text-xs">
                <a href="{{ route('mods.explorer') }}" class="px-4 py-2 rounded-xl bg-slate-900/80 hover:bg-slate-800 border border-slate-800 text-slate-300 hover:text-white transition-colors flex items-center gap-2 font-semibold">
                    <i class="fa-solid fa-cube text-cyan-400"></i>
                    <span>{{ app()->getLocale() == 'ar' ? 'مكتبة المودات' : 'Mods Catalog' }}</span>
                </a>
                <a href="{{ route('mods.trending') }}" class="px-4 py-2 rounded-xl bg-slate-900/80 hover:bg-slate-800 border border-slate-800 text-slate-300 hover:text-white transition-colors flex items-center gap-2 font-semibold">
                    <i class="fa-solid fa-fire text-amber-400"></i>
                    <span>{{ app()->getLocale() == 'ar' ? 'الأكثر تداولاً' : 'Trending' }}</span>
                </a>
                <a href="{{ route('mods.compare') }}" class="px-4 py-2 rounded-xl bg-slate-900/80 hover:bg-slate-800 border border-slate-800 text-slate-300 hover:text-white transition-colors flex items-center gap-2 font-semibold">
                    <i class="fa-solid fa-code-compare text-emerald-400"></i>
                    <span>{{ app()->getLocale() == 'ar' ? 'مقارنة المودات' : 'Compare Mods' }}</span>
                </a>
            </div>
        </div>
    </div>

    <!-- ================================================================= -->
    <!-- GLOBAL PLATFORM STATS BAR -->
    <!-- ================================================================= -->
    @if(isset($globalStats))
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="glass-card rounded-2xl p-5 text-center border border-slate-800 border-b-2 border-b-cyan-500/80 hover:-translate-y-1 transition-transform">
            <i class="fa-solid fa-cube text-cyan-400 text-2xl mb-2"></i>
            <div class="text-2xl font-extrabold text-white">{{ number_format($globalStats['mods']) }}</div>
            <div class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider mt-1">{{ app()->getLocale() == 'ar' ? 'مود متاح' : 'Available Mods' }}</div>
        </div>
        <div class="glass-card rounded-2xl p-5 text-center border border-slate-800 border-b-2 border-b-emerald-500/80 hover:-translate-y-1 transition-transform">
            <i class="fa-solid fa-boxes-stacked text-emerald-400 text-2xl mb-2"></i>
            <div class="text-2xl font-extrabold text-white">{{ number_format($globalStats['modpacks']) }}</div>
            <div class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider mt-1">{{ app()->getLocale() == 'ar' ? 'تجميعة منشورة' : 'Curated Packs' }}</div>
        </div>
        <div class="glass-card rounded-2xl p-5 text-center border border-slate-800 border-b-2 border-b-violet-500/80 hover:-translate-y-1 transition-transform">
            <i class="fa-solid fa-download text-violet-400 text-2xl mb-2"></i>
            <div class="text-2xl font-extrabold text-white">{{ number_format($globalStats['downloads']) }}</div>
            <div class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider mt-1">{{ app()->getLocale() == 'ar' ? 'إجمالي التحميلات' : 'Total Downloads' }}</div>
        </div>
        <div class="glass-card rounded-2xl p-5 text-center border border-slate-800 border-b-2 border-b-amber-500/80 hover:-translate-y-1 transition-transform">
            <i class="fa-solid fa-users text-amber-400 text-2xl mb-2"></i>
            <div class="text-2xl font-extrabold text-white">{{ number_format($globalStats['users']) }}</div>
            <div class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider mt-1">{{ app()->getLocale() == 'ar' ? 'عضو مسجل' : 'Active Members' }}</div>
        </div>
    </div>
    @endif

    <!-- ================================================================= -->
    <!-- FEATURED / TRENDING GAMES SECTION -->
    <!-- ================================================================= -->
    @if($trendingGames && $trendingGames->count() > 0)
    <div class="space-y-6">
        <div class="flex items-center justify-between border-b border-slate-800/80 pb-4">
            <div class="space-y-1">
                <h2 class="text-xl font-bold tracking-wide flex items-center gap-2">
                    <i class="fa-solid fa-fire text-amber-500"></i>
                    <span>{{ app()->getLocale() == 'ar' ? 'الألعاب الأكثر شعبية' : 'Popular & Trending Games' }}</span>
                </h2>
                <p class="text-xs text-slate-400">{{ app()->getLocale() == 'ar' ? 'الألعاب الأكثر تفاعلاً وزيارة في التجميعات هذا الأسبوع' : 'Games with the highest modpack activity and views this week' }}</p>
            </div>
            <a href="#all-games-section" class="text-xs text-violet-400 hover:text-violet-300 font-bold flex items-center gap-1 transition-colors">
                <span>{{ app()->getLocale() == 'ar' ? 'عرض كل الألعاب' : 'View All Games' }}</span>
                <i class="fa-solid fa-chevron-down text-[10px]"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($trendingGames as $game)
                @include('games.partials.game_card', ['game' => $game, 'isTrending' => true])
            @endforeach
        </div>
    </div>
    @endif

    <!-- ================================================================= -->
    <!-- TOP COMMUNITY MODS SHOWCASE (NEW) -->
    <!-- ================================================================= -->
    @if(isset($topMods) && $topMods->count() > 0)
    <div class="space-y-6">
        <div class="flex items-center justify-between border-b border-slate-800/80 pb-4">
            <div class="space-y-1">
                <h2 class="text-xl font-bold tracking-wide flex items-center gap-2">
                    <i class="fa-solid fa-cube text-cyan-400"></i>
                    <span>{{ app()->getLocale() == 'ar' ? 'أبرز المودات الشائعة' : 'Top Community Mods' }}</span>
                </h2>
                <p class="text-xs text-slate-400">{{ app()->getLocale() == 'ar' ? 'المودات الأكثر تحميلاً وتقييماً من قِبل مجتمع اللاعبين' : 'Most downloaded and highly rated mods across all supported games' }}</p>
            </div>
            <a href="{{ route('mods.explorer') }}" class="text-xs text-cyan-400 hover:text-cyan-300 font-bold flex items-center gap-1 transition-colors">
                <span>{{ app()->getLocale() == 'ar' ? 'تصفح كل المودات' : 'Explore All Mods' }}</span>
                <i class="fa-solid fa-arrow-right text-[10px] rtl:rotate-180"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($topMods as $mod)
            <a href="{{ route('mods.show', $mod->slug) }}" class="group block glass-card rounded-2xl overflow-hidden border border-slate-800 hover:border-cyan-500/40 p-4 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg hover:shadow-cyan-500/10">
                <div class="flex gap-4 items-center">
                    <!-- Mod Thumbnail -->
                    <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-950 shrink-0 border border-slate-800">
                        <img src="{{ $mod->image_url ?: asset('images/mod-placeholder.png') }}" 
                             alt="{{ $mod->name }}"
                             loading="lazy" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>

                    <!-- Mod Details -->
                    <div class="flex-1 min-w-0 space-y-1">
                        <div class="flex items-center justify-between gap-1">
                            <h3 class="text-sm font-bold text-white group-hover:text-cyan-400 transition-colors truncate">
                                {{ $mod->name }}
                            </h3>
                        </div>

                        <p class="text-[11px] text-slate-400 line-clamp-1">
                            {{ $mod->author ? (app()->getLocale() == 'ar' ? 'بواسطة: ' : 'By ') . $mod->author : (app()->getLocale() == 'ar' ? 'مود موثق' : 'Verified Mod') }}
                        </p>

                        <div class="flex items-center justify-between text-[10px] text-slate-500 pt-1">
                            <span class="px-2 py-0.5 rounded bg-slate-900 border border-slate-800 text-slate-300 font-semibold truncate max-w-[110px]">
                                {{ $mod->game->name ?? 'PC Game' }}
                            </span>
                            <span class="text-cyan-400 font-bold flex items-center gap-1">
                                <i class="fa-solid fa-download text-[9px]"></i>
                                {{ number_format($mod->downloads_count) }}
                            </span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- ================================================================= -->
    <!-- LATEST MOD PACKS (COLLECTIONS) SECTION -->
    <!-- ================================================================= -->
    @if(isset($latestPacks) && $latestPacks->count() > 0)
    <div class="space-y-6">
        <div class="flex items-center justify-between border-b border-slate-800/80 pb-4">
            <div class="space-y-1">
                <h2 class="text-xl font-bold tracking-wide flex items-center gap-2">
                    <i class="fa-solid fa-boxes-stacked text-emerald-400"></i>
                    <span>{{ app()->getLocale() == 'ar' ? 'أحدث تجميعات المودات' : 'Latest Mod Packs' }}</span>
                </h2>
                <p class="text-xs text-slate-400">{{ app()->getLocale() == 'ar' ? 'تجميعات جاهزة ومنسقة تم استخراجها حديثاً لتجربة فورية' : 'Freshly curated and tested mod collections ready to install' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($latestPacks as $pack)
            <a href="{{ route('modpacks.show', $pack->id) }}" class="group block glass-card rounded-2xl overflow-hidden border border-slate-800/80 hover:border-emerald-500/50 p-5 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-500/10">
                <div class="space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="text-base font-bold text-white group-hover:text-emerald-400 transition-colors line-clamp-1">
                            {{ app()->getLocale() == 'ar' ? $pack->title_ar : $pack->title_en }}
                        </h3>
                        <span class="px-2 py-0.5 rounded-md bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-[10px] font-bold shrink-0">
                            {{ $pack->mods_count ?? $pack->mods()->count() }} {{ app()->getLocale() == 'ar' ? 'مود' : 'Mods' }}
                        </span>
                    </div>

                    <p class="text-xs text-slate-400 line-clamp-2 leading-relaxed font-normal">
                        {{ strip_tags(app()->getLocale() == 'ar' ? $pack->description_ar : $pack->description_en) }}
                    </p>

                    <div class="flex items-center justify-between text-[11px] text-slate-400 pt-3 border-t border-slate-800/60 mt-2">
                        <span class="flex items-center gap-1.5 font-medium text-slate-300">
                            <i class="fa-solid fa-gamepad text-slate-400"></i>
                            {{ $pack->gameVersions->first()?->game?->name ?? 'PC Gaming' }}
                        </span>
                        <span class="text-slate-400 font-mono text-[10px]">{{ $pack->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- ================================================================= -->
    <!-- SPONSORED ADVERTISEMENT BANNER -->
    <!-- ================================================================= -->
    <x-ad-slot type="leaderboard" />

    <!-- ================================================================= -->
    <!-- ALL GAMES INTERACTIVE DIRECTORY -->
    <!-- ================================================================= -->
    <div class="space-y-6 pt-4" id="all-games-section">
        <div class="border-b border-slate-800/80 pb-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="space-y-1">
                <h2 class="text-xl font-bold tracking-wide flex items-center gap-2">
                    <i class="fa-solid fa-gamepad text-violet-500"></i>
                    <span>{{ __('messages.all_games') }}</span>
                </h2>
                <p class="text-xs text-slate-400">{{ __('messages.all_games_desc') }}</p>
            </div>

            <!-- Frontend Filter Controls -->
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto items-center">
                <div class="relative w-full sm:w-64">
                    <span class="absolute inset-y-0 left-0 rtl:left-auto rtl:right-0 px-3 flex items-center pointer-events-none text-slate-500">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input 
                        type="text" 
                        id="search-games-input" 
                        oninput="filterGames()" 
                        placeholder="{{ __('messages.search_placeholder') }}" 
                        class="w-full bg-slate-900/90 border border-slate-800 rounded-xl pl-9 rtl:pl-4 rtl:pr-9 pr-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-violet-600 transition-colors">
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <span class="text-xs text-slate-500 font-bold whitespace-nowrap">{{ __('messages.category') }}:</span>
                    <select id="filter-category-select" onchange="filterGames()" class="w-full sm:w-36 bg-slate-900/90 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-violet-600">
                        <option value="all">{{ __('messages.all_categories') }}</option>
                        <option value="rpg">RPG</option>
                        <option value="strategy">Strategy</option>
                        <option value="survival">Survival</option>
                        <option value="sandbox">Sandbox</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Pinned Games Section -->
        <div class="mb-4 hidden" id="pinned-games-section">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                <i class="fa-solid fa-thumbtack text-violet-400"></i>
                <span>{{ app()->getLocale() == 'ar' ? 'الألعاب المثبتة' : 'Pinned Games' }}</span>
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="pinned-games-grid"></div>
            <div class="w-full h-px bg-slate-800/60 my-6"></div>
        </div>

        <!-- Games Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="games-grid-container">
            <!-- No Match Fallback -->
            <div class="col-span-full py-16 text-center text-slate-500 hidden" id="no-games-fallback">
                <i class="fa-solid fa-folder-open text-4xl mb-4 text-slate-600"></i>
                <p>{{ app()->getLocale() == 'ar' ? 'عفواً، لا توجد ألعاب تطابق معايير البحث الحالية.' : 'Sorry, no games match your search criteria.' }}</p>
            </div>

            @forelse($games as $game)
                @include('games.partials.game_card', ['game' => $game, 'isTrending' => false])
            @empty
                <div class="col-span-full py-16 text-center text-slate-500">
                    <i class="fa-solid fa-folder-open text-4xl mb-4"></i>
                    <p>{{ app()->getLocale() == 'ar' ? 'لا توجد ألعاب مسجلة حتى الآن.' : 'No games registered yet.' }}</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- ================================================================= -->
    <!-- NEWSLETTER SUBSCRIPTION CTA -->
    <!-- ================================================================= -->
    <div class="w-full rounded-3xl bg-gradient-to-r from-slate-900 via-slate-950 to-slate-900 border border-slate-800 p-8 md:p-12 relative overflow-hidden shadow-2xl">
        <div class="absolute top-0 right-0 w-64 h-64 bg-violet-600/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-cyan-600/10 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="text-center md:text-left rtl:md:text-right max-w-lg space-y-2">
                <h3 class="text-2xl md:text-3xl font-extrabold text-white">
                    {{ app()->getLocale() == 'ar' ? 'اشترك للحصول على أحدث التحديثات' : 'Stay Updated with Latest Mods' }}
                </h3>
                <p class="text-xs md:text-sm text-slate-400 leading-relaxed font-normal">
                    {{ app()->getLocale() == 'ar' 
                        ? 'انضم إلى النشرة البريدية المجانية للحصول على أهم تجميعات المودات والتحديثات الأسبوعية مباشرة في بريدك.' 
                        : 'Join our weekly newsletter to get the best mod packs, updates, and compatibility guides delivered straight to your inbox.' }}
                </p>
            </div>
            
            <div class="w-full md:w-auto flex-grow max-w-md">
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                    @csrf
                    <input 
                        type="email" 
                        name="email" 
                        required 
                        placeholder="{{ app()->getLocale() == 'ar' ? 'أدخل بريدك الإلكتروني...' : 'Enter your email address...' }}" 
                        class="w-full flex-grow bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-violet-600 placeholder-slate-600 transition-colors">
                    <button type="submit" class="whitespace-nowrap px-6 py-3 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white font-bold text-xs shadow-lg shadow-violet-500/20 transition-all">
                        <i class="fa-solid fa-paper-plane mr-2 rtl:ml-2"></i>
                        <span>{{ app()->getLocale() == 'ar' ? 'اشترك الآن' : 'Subscribe' }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    function filterGames() {
        const query = document.getElementById('search-games-input').value.toLowerCase().trim();
        const category = document.getElementById('filter-category-select').value;
        const cards = document.querySelectorAll('.game-card-item');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.getAttribute('data-name');
            const cardCategory = card.getAttribute('data-category');

            const matchesQuery = name.includes(query);
            const matchesCategory = (category === 'all' || cardCategory === category);

            if (matchesQuery && matchesCategory) {
                card.classList.remove('hidden');
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.classList.add('hidden');
                card.style.display = 'none';
            }
        });

        // Toggle fallback display
        const fallback = document.getElementById('no-games-fallback');
        if (fallback) {
            if (visibleCount === 0 && cards.length > 0) {
                fallback.classList.remove('hidden');
                fallback.style.display = 'block';
            } else {
                fallback.classList.add('hidden');
                fallback.style.display = 'none';
            }
        }
    }

    // Pinning Logic
    document.addEventListener('DOMContentLoaded', () => {
        loadPinnedGames();
    });

    function togglePinGame(event, gameId) {
        event.preventDefault();
        let pinned = JSON.parse(localStorage.getItem('pinnedGames') || '[]');
        
        if (pinned.includes(gameId)) {
            pinned = pinned.filter(id => id !== gameId);
        } else {
            pinned.push(gameId);
        }
        
        localStorage.setItem('pinnedGames', JSON.stringify(pinned));
        loadPinnedGames();
    }

    function loadPinnedGames() {
        let pinned = JSON.parse(localStorage.getItem('pinnedGames') || '[]');
        const pinnedSection = document.getElementById('pinned-games-section');
        const pinnedGrid = document.getElementById('pinned-games-grid');
        const mainGrid = document.getElementById('games-grid-container');
        
        if (!pinnedSection || !pinnedGrid || !mainGrid) return;

        const allCards = document.querySelectorAll('.game-card-item');
        allCards.forEach(card => {
            const btn = card.querySelector('.pin-btn');
            if (btn) {
                btn.classList.remove('bg-violet-600', 'text-white', 'border-violet-500');
                btn.classList.add('bg-slate-950/70', 'text-slate-400', 'border-slate-800');
            }
            if (card.parentElement.id === 'pinned-games-grid') {
                mainGrid.appendChild(card);
            }
        });

        if (pinned.length > 0) {
            pinnedSection.classList.remove('hidden');
            allCards.forEach(card => {
                const pinBtn = card.querySelector('.pin-btn');
                if (!pinBtn) return;
                const gameId = parseInt(pinBtn.id.split('-').pop());
                
                if (pinned.includes(gameId)) {
                    pinBtn.classList.remove('bg-slate-950/70', 'text-slate-400', 'border-slate-800');
                    pinBtn.classList.add('bg-violet-600', 'text-white', 'border-violet-500');
                    pinnedGrid.appendChild(card);
                }
            });
        } else {
            pinnedSection.classList.add('hidden');
        }
    }
</script>
@endsection
