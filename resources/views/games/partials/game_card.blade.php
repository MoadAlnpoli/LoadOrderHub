@php
    $categoryMap = function($slug) {
        $s = strtolower($slug);
        if (str_contains($s, 'skyrim') || str_contains($s, 'cyberpunk') || str_contains($s, 'witcher') || str_contains($s, 'fallout') || str_contains($s, 'baldursgate')) return 'rpg';
        if (str_contains($s, 'hearts-of-iron') || str_contains($s, 'bannerlord') || str_contains($s, 'crusader')) return 'strategy';
        if (str_contains($s, 'minecraft') || str_contains($s, 'roblox')) return 'sandbox';
        return 'survival';
    };
    $cat = $categoryMap($game->slug);
    $isTrending = isset($isTrending) ? $isTrending : false;
@endphp

<a href="{{ route('games.show', $game->slug) }}" 
   class="game-card-item group block glass-card rounded-2xl overflow-hidden border border-slate-800/80 hover:border-violet-500/50 transition-all duration-300 transform hover:-translate-y-1.5 shadow-xl hover:shadow-violet-500/10"
   data-name="{{ strtolower($game->name) }}"
   data-category="{{ $cat }}">
    
    <!-- Thumbnail Container -->
    <div class="relative h-48 overflow-hidden bg-slate-950">
        <img src="{{ $game->thumbnail_url }}" 
             alt="{{ $game->name }}" 
             loading="lazy"
             class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-500">
        
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>

        <!-- Badges -->
        <div class="absolute top-3 right-3 rtl:right-auto rtl:left-3 flex items-center gap-2">
            @if($isTrending)
                <span class="bg-amber-500/20 backdrop-blur-md border border-amber-500/40 text-amber-300 text-[10px] px-2.5 py-1 rounded-full font-bold flex items-center gap-1 shadow-lg">
                    <i class="fa-solid fa-fire text-amber-400 animate-pulse"></i>
                    <span>{{ app()->getLocale() == 'ar' ? 'شائع' : 'Trending' }}</span>
                </span>
            @endif

            <span class="bg-slate-950/80 backdrop-blur-md border border-slate-800/80 text-slate-300 text-[11px] px-2.5 py-1 rounded-lg font-semibold shadow-md">
                {{ $game->versions_count }} {{ trans_choice('Version|Versions', $game->versions_count) }}
            </span>
        </div>

        <!-- Pin Button -->
        <button type="button" 
                onclick="togglePinGame(event, {{ $game->id }})" 
                class="absolute top-3 left-3 rtl:left-auto rtl:right-3 z-10 w-8 h-8 rounded-lg bg-slate-950/70 hover:bg-violet-600 border border-slate-800 text-slate-400 hover:text-white flex items-center justify-center transition-all pin-btn shadow-md" 
                id="pin-btn-{{ $game->id }}"
                title="{{ app()->getLocale() == 'ar' ? 'تثبيت اللعبة' : 'Pin Game' }}">
            <i class="fa-solid fa-thumbtack text-xs"></i>
        </button>
    </div>

    <!-- Info Content -->
    <div class="p-5 space-y-3">
        <div class="flex items-start justify-between gap-2">
            <h3 class="text-base font-bold text-white group-hover:text-violet-400 transition-colors line-clamp-1">
                {{ $game->name }}
            </h3>
        </div>

        @if(!empty($game->description))
            <p class="text-xs text-slate-400 line-clamp-2 leading-relaxed font-normal">
                {{ $game->description }}
            </p>
        @endif

        <div class="flex items-center justify-between text-xs text-slate-400 pt-3 border-t border-slate-800/60 mt-2">
            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider px-2 py-0.5 rounded bg-slate-900 border border-slate-800">
                {{ strtoupper($cat) }}
            </span>

            <span class="inline-flex items-center gap-1.5 text-violet-400 font-bold text-xs group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform">
                <span>{{ app()->getLocale() == 'ar' ? 'استكشف التجميعات' : 'Explore Packs' }}</span>
                <i class="fa-solid fa-arrow-right text-[10px] rtl:rotate-180"></i>
            </span>
        </div>
    </div>
</a>
