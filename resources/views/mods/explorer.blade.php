@extends('layouts.app')

@section('title', 'Mods Catalog Explorer - LoadOrderHub')

@section('content')
<div class="space-y-8">
    <!-- Breadcrumbs -->
    <nav class="flex text-xs text-slate-500 space-x-2 rtl:space-x-reverse">
        <a href="{{ route('home') }}" class="hover:text-slate-300">{{ __('messages.home') }}</a>
        <span>/</span>
        <span class="text-slate-300">Explorer</span>
    </nav>

    <!-- Header -->
    <div class="border-b border-slate-800 pb-4 space-y-1">
        <h1 class="text-3xl font-black bg-gradient-to-r from-white via-slate-200 to-violet-400 bg-clip-text text-transparent flex items-center space-x-3">
            <i class="fa-solid fa-compass text-violet-500 animate-pulse"></i>
            <span>Mods Explorer Catalog</span>
        </h1>
        <p class="text-xs text-slate-400 font-medium">Discover, search, and filter hundreds of mods catalogued across games, versions, and categories.</p>
    </div>


    <!-- Leaderboard Ad Slot -->
    <x-ad-slot type="leaderboard" class="py-2" />


    <!-- Filter Bar Card -->
    <form action="{{ route('mods.explorer') }}" method="GET" class="glass-card rounded-2xl border border-slate-800 p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
        <!-- Search Input -->
        <div class="space-y-1">
            <label class="text-xs text-slate-400 font-bold block">Search Name</label>
            <input type="text" name="search" value="{{ $search }}" placeholder="e.g. Address Library" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2 text-xs text-white placeholder-slate-600 focus:outline-none focus:border-violet-600">
        </div>

        <!-- Game Filter -->
        <div class="space-y-1">
            <label class="text-xs text-slate-400 font-bold block">Select Game</label>
            <select name="game_id" onchange="this.form.submit()" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-violet-600">
                <option value="">All Games</option>
                @foreach($games as $g)
                    <option value="{{ $g->id }}" {{ $selectedGameId == $g->id ? 'selected' : '' }}>{{ $g->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Version Filter -->
        <div class="space-y-1">
            <label class="text-xs text-slate-400 font-bold block">Select Update/Version</label>
            <select name="version_id" onchange="this.form.submit()" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-violet-600" {{ empty($versions) ? 'disabled' : '' }}>
                <option value="">All Versions</option>
                @foreach($versions as $v)
                    <option value="{{ $v->id }}" {{ $selectedVersionId == $v->id ? 'selected' : '' }}>{{ $v->version }}</option>
                @endforeach
            </select>
        </div>

        <!-- Rating Filter -->
        <div class="space-y-1">
            <label class="text-xs text-slate-400 font-bold block">Min. Rating</label>
            <select name="min_rating" onchange="this.form.submit()" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-amber-500">
                <option value="">Any Rating</option>
                <option value="4" {{ request('min_rating') == '4' ? 'selected' : '' }}>4+ Stars</option>
                <option value="3" {{ request('min_rating') == '3' ? 'selected' : '' }}>3+ Stars</option>
                <option value="2" {{ request('min_rating') == '2' ? 'selected' : '' }}>2+ Stars</option>
            </select>
        </div>

        <!-- Sorting -->
        <div class="space-y-1">
            <label class="text-xs text-slate-400 font-bold block">Sort By</label>
            <select name="sort" onchange="this.form.submit()" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-violet-600">
                <option value="views_desc" {{ $sort === 'views_desc' ? 'selected' : '' }}>Most Viewed</option>
                <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
            </select>
        </div>
    </form>

    <!-- Grid/List Toggle & Results Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-slate-300 font-bold text-sm">{{ app()->getLocale() == 'ar' ? 'نتائج البحث' : 'Search Results' }}</h2>
        <div class="flex items-center gap-1 bg-slate-950 border border-slate-800 rounded-xl p-1">
            <button type="button" onclick="toggleViewMode('grid')" id="btn-view-grid" class="p-2 w-10 h-10 flex justify-center items-center rounded-lg bg-violet-600/20 text-violet-400 transition-all" title="Grid View">
                <i class="fa-solid fa-table-cells-large"></i>
            </button>
            <button type="button" onclick="toggleViewMode('list')" id="btn-view-list" class="p-2 w-10 h-10 flex justify-center items-center rounded-lg hover:bg-slate-800 text-slate-500 hover:text-slate-300 transition-all" title="List View">
                <i class="fa-solid fa-list"></i>
            </button>
        </div>
    </div>

    <!-- Results Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="mods-container">
        @forelse($mods as $mod)
            <div class="mod-card glass-card rounded-2xl border border-slate-800/80 hover:border-violet-600/40 p-5 space-y-4 flex flex-col justify-between transition-all duration-300 transform hover:-translate-y-1">
                <div class="space-y-3">
                    <!-- Thumbnail/Image -->
                    <div class="aspect-video w-full rounded-xl overflow-hidden bg-slate-950 border border-slate-800/60 relative">
                        <img src="{{ $mod->display_image }}" alt="{{ $mod->name }}" onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';" class="w-full h-full object-cover">
                    </div>

                    <!-- Metadata -->
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-violet-400 uppercase tracking-wider">{{ $mod->game?->name ?? 'Unknown Game' }}</span>
                        <h3 class="text-base font-bold text-white line-clamp-1">{{ $mod->name }}</h3>
                        
                        @if($mod->fps_impact !== null)
                            @php
                                $fpsImpact = (int) $mod->fps_impact;
                                $fpsColor = $fpsImpact <= 2 ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : ($fpsImpact <= 10 ? 'bg-amber-500/20 text-amber-400 border-amber-500/30' : 'bg-red-500/20 text-red-400 border-red-500/30');
                                $fpsIcon = $fpsImpact <= 2 ? 'fa-bolt' : ($fpsImpact <= 10 ? 'fa-weight-hanging' : 'fa-anchor');
                                $fpsLabel = $fpsImpact <= 2 ? 'تأثير خفيف' : ($fpsImpact <= 10 ? 'تأثير متوسط' : 'تأثير ثقيل');
                            @endphp
                            <div class="inline-flex items-center gap-1.5 px-2 py-0.5 mt-1 rounded-md border text-[9px] font-bold {{ $fpsColor }}" title="{{ $fpsImpact }} FPS Drop">
                                <i class="fa-solid {{ $fpsIcon }}"></i>
                                <span>{{ app()->getLocale() == 'ar' ? $fpsLabel : ($fpsImpact <= 2 ? 'Light Impact' : ($fpsImpact <= 10 ? 'Medium Impact' : 'Heavy Impact')) }} (-{{ $fpsImpact }} FPS)</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="space-y-3 pt-3 border-t border-slate-800/60">
                    <div class="flex items-center justify-between text-xs text-slate-400">
                        <span class="flex items-center gap-1.5"><i class="fa-regular fa-eye text-violet-400"></i> {{ number_format($mod->total_views) }} Views</span>
                        <span class="flex items-center gap-1.5"><i class="fa-regular fa-comment text-blue-400"></i> {{ $mod->comments_count }} Comments</span>
                    </div>

                    <div class="flex items-center gap-3">
                        @if($mod->slug)
                        <button onclick="openQuickView('{{ $mod->slug }}')" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold rounded-xl transition-all shadow-md" title="{{ app()->getLocale() == 'ar' ? 'عرض سريع' : 'Quick View' }}"><i class="fa-solid fa-eye"></i></button>
                        <a href="{{ route('mods.show', $mod->slug) }}" class="flex-1 py-2 bg-gradient-to-r from-violet-600 to-blue-500 hover:from-violet-500 hover:to-blue-400 text-white text-xs font-bold rounded-xl text-center transition-all shadow-md">{{ app()->getLocale() == 'ar' ? 'عرض التفاصيل' : 'View Details' }}</a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Native Ad Spot in Grid -->
            @if($loop->index == 2)
                <x-ad-slot type="in_content" />
            @endif
        @empty
            <div class="col-span-full py-16 text-center text-slate-500">
                <i class="fa-solid fa-folder-open text-4xl mb-4"></i>
                <p>No mods matched your search query or filters.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination / Load More -->
    <div class="pt-6" id="pagination-container">
        @if($mods->hasMorePages())
            <button id="load-more-btn" onclick="loadMoreMods(this, '{{ $mods->nextPageUrl() }}')" class="w-full md:w-auto px-12 py-3 rounded-2xl bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-300 font-bold transition-all flex items-center justify-center gap-2 shadow-lg mx-auto">
                <span id="load-more-text">{{ app()->getLocale() == 'ar' ? 'تحميل المزيد' : 'Load More' }}</span>
                <i class="fa-solid fa-chevron-down text-xs" id="load-more-icon"></i>
            </button>
        @endif
    </div>

    <script>
        function loadMoreMods(btn, url) {
            const textEl = document.getElementById('load-more-text');
            const iconEl = document.getElementById('load-more-icon');
            
            textEl.innerText = "{{ app()->getLocale() == 'ar' ? 'جاري التحميل...' : 'Loading...' }}";
            iconEl.className = "fa-solid fa-circle-notch fa-spin text-xs";
            btn.disabled = true;

            fetch(url)
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    // Extract new grid items
                    const newItems = doc.querySelectorAll('.grid.grid-cols-1.md\\:grid-cols-3.lg\\:grid-cols-4.gap-6 > div');
                    const currentGrid = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-3.lg\\:grid-cols-4.gap-6');
                    
                    newItems.forEach(item => {
                        currentGrid.appendChild(item);
                    });

                    // Update load more button
                    const newBtn = doc.getElementById('load-more-btn');
                    if (newBtn) {
                        const newUrl = newBtn.getAttribute('onclick').match(/'([^']+)'/)[1];
                        btn.setAttribute('onclick', `loadMoreMods(this, '${newUrl}')`);
                        textEl.innerText = "{{ app()->getLocale() == 'ar' ? 'تحميل المزيد' : 'Load More' }}";
                        iconEl.className = "fa-solid fa-chevron-down text-xs";
                        btn.disabled = false;
                    } else {
                        document.getElementById('pagination-container').innerHTML = `<p class="text-xs text-slate-500 text-center">{{ app()->getLocale() == 'ar' ? 'وصلت لنهاية القائمة' : 'End of list' }}</p>`;
                    }
                })
                .catch(() => {
                    textEl.innerText = "Error";
                    iconEl.className = "fa-solid fa-triangle-exclamation text-xs text-red-400";
                    btn.disabled = false;
                });
        }
        }

        // Grid/List View Toggle
        function toggleViewMode(mode) {
            const container = document.getElementById('mods-container');
            const gridBtn = document.getElementById('btn-view-grid');
            const listBtn = document.getElementById('btn-view-list');
            const cards = container.querySelectorAll('.mod-card');

            if (mode === 'list') {
                container.className = 'grid grid-cols-1 gap-4';
                gridBtn.className = 'p-2 w-10 h-10 flex justify-center items-center rounded-lg hover:bg-slate-800 text-slate-500 hover:text-slate-300 transition-all';
                listBtn.className = 'p-2 w-10 h-10 flex justify-center items-center rounded-lg bg-violet-600/20 text-violet-400 transition-all';
                
                cards.forEach(card => {
                    card.classList.remove('flex-col');
                    card.classList.add('flex-row', 'items-center', 'md:items-start');
                    const imgContainer = card.querySelector('.aspect-video');
                    if(imgContainer) {
                        imgContainer.classList.remove('w-full');
                        imgContainer.classList.add('w-32', 'md:w-48', 'shrink-0');
                    }
                });
                localStorage.setItem('explorerViewMode', 'list');
            } else {
                container.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6';
                listBtn.className = 'p-2 w-10 h-10 flex justify-center items-center rounded-lg hover:bg-slate-800 text-slate-500 hover:text-slate-300 transition-all';
                gridBtn.className = 'p-2 w-10 h-10 flex justify-center items-center rounded-lg bg-violet-600/20 text-violet-400 transition-all';
                
                cards.forEach(card => {
                    card.classList.add('flex-col');
                    card.classList.remove('flex-row', 'items-center', 'md:items-start');
                    const imgContainer = card.querySelector('.aspect-video');
                    if(imgContainer) {
                        imgContainer.classList.add('w-full');
                        imgContainer.classList.remove('w-32', 'md:w-48', 'shrink-0');
                    }
                });
                localStorage.setItem('explorerViewMode', 'grid');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const savedMode = localStorage.getItem('explorerViewMode');
            if (savedMode === 'list') {
                toggleViewMode('list');
            }
        });
    </script>
    <!-- Footer Ad Slot -->
    <x-ad-slot type="leaderboard" class="py-4" />
</div>
@endsection
