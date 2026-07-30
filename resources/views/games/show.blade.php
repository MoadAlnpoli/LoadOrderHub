@extends('layouts.app')

@section('title', $game->name)

@section('meta')
    <meta name="description" content="{{ Str::limit(strip_tags($game->description), 150) }}">
    <meta property="og:title" content="{{ $game->name }} - LoadOrderHub">
    <meta property="og:description" content="{{ Str::limit(strip_tags($game->description), 150) }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('games.show', $game->slug) }}">
    @if($game->thumbnail_url)
        <meta property="og:image" content="{{ $game->thumbnail_url }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">

    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "VideoGame",
      "name": "{{ $game->name }}",
      "description": "{{ addslashes(strip_tags($game->description)) }}",
      @if($game->thumbnail_url)
      "image": "{{ $game->thumbnail_url }}",
      @endif
      "url": "{{ route('games.show', $game->slug) }}"
    }
    </script>
@endsection

@section('content')
<div class="space-y-8">
    <!-- Breadcrumbs -->
    <nav class="flex text-xs text-slate-500 space-x-2 rtl:space-x-reverse">
        <a href="{{ route('home') }}" class="hover:text-slate-300">{{ __('messages.home') }}</a>
        <span>/</span>
        <span class="text-slate-300">{{ $game->name }}</span>
    </nav>

    <!-- Game Detail Header -->
    <div class="relative rounded-3xl overflow-hidden bg-slate-900 border border-slate-800 p-6 md:p-8 flex flex-col md:flex-row items-center md:items-start gap-6">
        <div class="w-full md:w-48 h-48 rounded-2xl overflow-hidden bg-slate-950 flex-shrink-0">
            <img src="{{ $game->thumbnail_url }}" alt="{{ $game->name }}" class="w-full h-full object-cover">
        </div>
        <div class="space-y-4 text-center md:text-left rtl:md:text-right flex-grow">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h1 class="text-2xl md:text-4xl font-extrabold text-white">{{ $game->name }}</h1>
                <a href="{{ route('games.mods', $game->slug) }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-gradient-to-tr from-violet-600 to-blue-500 hover:from-violet-500 hover:to-blue-400 text-white font-semibold text-xs tracking-wide transition-all shadow-md shadow-violet-500/10 hover:shadow-violet-500/20">
                    <i class="fa-solid fa-list mr-2 rtl:ml-2"></i>
                    View Mods Library
                </a>
            </div>
            <p class="text-sm text-slate-400 max-w-3xl leading-relaxed">{{ $game->description }}</p>
        </div>
    </div>

    <!-- Filter Control & Listings Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Filter Control -->
        <div class="lg:col-span-1 space-y-6">
            <div class="glass-card p-6 rounded-2xl border border-slate-800 space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">
                    <i class="fa-solid fa-filter text-violet-500 mr-1.5 rtl:ml-1.5"></i>
                    {{ __('messages.filter_version') }}
                </h3>
                
                <!-- Version Selection Dropdown -->
                <div class="relative">
                    <select id="version-filter-select" class="w-full block bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-violet-600 appearance-none cursor-pointer">
                        @foreach($versions as $v)
                            <option value="{{ $v->id }}" {{ $selectedVersionId == $v->id ? 'selected' : '' }}>
                                {{ __('messages.game') }} {{ $v->version }}
                            </option>
                        @endforeach
                    </select>
                    <!-- Custom Arrow Icon -->
                    <div class="absolute inset-y-0 right-0 rtl:left-0 rtl:right-auto flex items-center px-4 pointer-events-none text-slate-500">
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>
            
            <!-- Sticky Sidebar Ad Slot -->
            <div class="sticky top-24">
                <x-ad-slot type="sidebar" />
            </div>
        </div>

        <!-- Mod Packs Container -->
        <div class="lg:col-span-3 space-y-6">
            <h2 class="text-xl font-bold tracking-wide text-white flex items-center space-x-2 rtl:space-x-reverse border-b border-slate-800 pb-3">
                <i class="fa-solid fa-cubes text-violet-500"></i>
                <span>{{ __('messages.game_packs') }}</span>
            </h2>

            <div id="mod-packs-container" class="transition-opacity duration-200">
                @include('games.partials.mod_packs_list', ['modPacks' => $modPacks])
            </div>

            <!-- Mods in this Game Section -->
            <div class="pt-8 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <h2 class="text-xl font-bold tracking-wide text-white flex items-center space-x-2 rtl:space-x-reverse">
                        <i class="fa-solid fa-cube text-violet-500"></i>
                        <span>{{ app()->getLocale() == 'ar' ? 'مودات هذه اللعبة' : 'Mods in this Game' }}</span>
                    </h2>
                    <a href="{{ route('games.mods', $game->slug) }}" class="text-xs font-bold text-violet-400 hover:text-violet-300 flex items-center gap-1">
                        <span>{{ app()->getLocale() == 'ar' ? 'عرض جميع المودات' : 'View All Mods' }} ({{ $game->mods()->count() }})</span>
                        <i class="fa-solid fa-arrow-left rtl:rotate-180"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($gameMods as $gMod)
                        <a href="{{ route('mods.show', $gMod->slug) }}" class="flex items-center gap-3 p-3 rounded-xl bg-slate-900/60 border border-slate-800 hover:border-violet-500/40 transition-all group">
                            <div class="w-14 h-14 rounded-lg overflow-hidden bg-slate-950 shrink-0">
                                <img src="{{ $gMod->display_image }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform" onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs font-bold text-white group-hover:text-violet-400 transition-colors truncate">{{ $gMod->name }}</h4>
                                <p class="text-[10px] text-slate-400 truncate mt-0.5">{{ $gMod->author ?: 'Nexus Creator' }}</p>
                                <span class="text-[9px] font-mono text-violet-400 mt-1 inline-block">Order #{{ $gMod->load_order }}</span>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full text-center py-6 text-xs text-slate-500">
                            {{ app()->getLocale() == 'ar' ? 'لا توجد مودات مسجلة لهذه اللعبة حالياً.' : 'No mods catalogued for this game yet.' }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const versionSelect = document.getElementById('version-filter-select');
        const container = document.getElementById('mod-packs-container');

        if (versionSelect) {
            versionSelect.addEventListener('change', function () {
                const versionId = this.value;
                const url = new URL(window.location.href);
                url.searchParams.set('version_id', versionId);

                // Update the browser URL without page reload for clean navigation
                window.history.pushState({}, '', url);

                // Show visual fade transition
                container.style.opacity = '0.4';

                // Query database dynamically via AJAX
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    container.innerHTML = data.html;
                    container.style.opacity = '1';
                })
                .catch(error => {
                    console.error('Filtering error:', error);
                    container.style.opacity = '1';
                });
            });
        }
    });
</script>
@endsection
