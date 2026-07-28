@extends('layouts.app')

@section('title', 'Trending Mods - Popular game configuration files')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="border-b border-slate-800 pb-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1">
            <h1 class="text-3xl font-black bg-gradient-to-r from-white via-slate-200 to-violet-400 bg-clip-text text-transparent flex items-center space-x-3">
                <i class="fa-solid fa-fire-flame-curved text-amber-500 animate-pulse"></i>
                <span>Trending Game Mods</span>
            </h1>
            <p class="text-xs text-slate-400 font-medium">Discover the most demanded and downloaded mods currently active in player load orders.</p>
        </div>

        <!-- Game Filter Dropdown -->
        <div class="w-full md:w-64">
            <select onchange="filterTrendingByGame(this.value)" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-violet-600">
                <option value="">All Games</option>
                @foreach($games as $g)
                    <option value="{{ $g->id }}" {{ $selectedGameId == $g->id ? 'selected' : '' }}>{{ $g->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Sponsored AdSense Banner Ad (Leaderboard) -->
    <div class="w-full text-center space-y-2 py-4">
        <span class="text-[9px] text-slate-650 font-bold uppercase tracking-widest">{{ __('messages.ad_space') }}</span>
        <div class="mx-auto max-w-4xl h-24 rounded-2xl border border-dashed border-slate-800 bg-slate-950/30 flex items-center justify-center text-xs text-slate-500">
            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                <i class="fa-solid fa-rectangle-ad text-3xl text-slate-700"></i>
                <span class="text-slate-400 font-medium">Google AdSense Leaderboard Banner<br><span class="text-[10px] text-slate-600">728x90 responsive advertisement banner</span></span>
            </div>
        </div>
    </div>

    <!-- Trending Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($mods as $index => $mod)
            <div class="glass-card rounded-2xl border border-slate-800/80 hover:border-violet-600/40 p-5 space-y-4 flex flex-col justify-between transition-all duration-300 transform hover:-translate-y-1">
                <div class="space-y-3">
                    <!-- Thumbnail/Image -->
                    <div class="aspect-video w-full rounded-xl overflow-hidden bg-slate-950 border border-slate-800/60 relative">
                        @if($mod->local_image_path || $mod->image_url)
                            <img src="{{ $mod->local_image_path ?: $mod->image_url }}" alt="{{ $mod->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-700 bg-slate-900/40">
                                <i class="fa-solid fa-cube text-4xl"></i>
                            </div>
                        @endif
                        
                        <!-- Rank Badge -->
                        <span class="absolute top-3 right-3 bg-violet-600 border border-violet-500 text-white text-[10px] px-2.5 py-0.5 rounded-full font-bold">
                            Trending
                        </span>
                    </div>

                    <!-- Metadata -->
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-violet-400 uppercase tracking-wider">{{ $mod->game?->name ?? 'Unknown Game' }}</span>
                        <h3 class="text-base font-bold text-white line-clamp-1">{{ $mod->name }}</h3>
                    </div>
                </div>

                <div class="space-y-3 pt-3 border-t border-slate-800/60">
                    <div class="flex items-center justify-between text-xs text-slate-400">
                        <span class="flex items-center gap-1.5"><i class="fa-regular fa-eye text-violet-400"></i> {{ number_format($mod->total_views) }} Views</span>
                        <span class="flex items-center gap-1.5"><i class="fa-regular fa-comment text-blue-400"></i> {{ $mod->comments_count }} Comments</span>
                    </div>

                    <div class="flex items-center gap-3">
                        @if($mod->slug)
                        <a href="{{ route('mods.show', $mod->slug) }}" class="flex-1 py-2 bg-gradient-to-r from-violet-600 to-blue-500 hover:from-violet-500 hover:to-blue-400 text-white text-xs font-bold rounded-xl text-center transition-all shadow-md shadow-violet-500/10">View Mod</a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Insert Native Ad after 3rd item -->
            @if($loop->index == 2)
                <div class="glass-card rounded-2xl border border-dashed border-slate-800 p-5 flex flex-col justify-between items-center text-center space-y-4 min-h-[300px]">
                    <span class="text-[9px] text-slate-650 font-bold uppercase tracking-widest">{{ __('messages.ad_space') }}</span>
                    <div class="space-y-2">
                        <i class="fa-solid fa-rectangle-ad text-4xl text-slate-700"></i>
                        <h4 class="text-sm font-bold text-slate-300">In-Feed Native Mod Ad</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">Integrated advertisement banner matching card metrics for high CTR.</p>
                    </div>
                    <div class="w-full h-10 rounded-lg bg-slate-950/60 flex items-center justify-center text-[10px] text-slate-650 font-mono">
                        Auto-optimized Ad slot
                    </div>
                </div>
            @endif
        @empty
            <div class="col-span-full py-16 text-center text-slate-500">
                <i class="fa-solid fa-fire-burner text-4xl mb-4"></i>
                <p>No popular mods found for this selection yet.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pt-6">
        {{ $mods->appends(request()->query())->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    function filterTrendingByGame(gameId) {
        if (gameId) {
            window.location.href = `{{ route('mods.trending') }}?game_id=${gameId}`;
        } else {
            window.location.href = `{{ route('mods.trending') }}`;
        }
    }
</script>
@endsection
