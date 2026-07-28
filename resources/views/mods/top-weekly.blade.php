@extends('layouts.app')

@section('title', 'Top Mods Weekly - LoadOrderHub')

@section('content')
<div class="space-y-8">
    <!-- Breadcrumbs -->
    <nav class="flex text-xs text-slate-500 space-x-2 rtl:space-x-reverse">
        <a href="{{ route('home') }}" class="hover:text-slate-300">{{ __('messages.home') }}</a>
        <span>/</span>
        <span class="text-slate-300">Top Mods Weekly</span>
    </nav>

    <!-- Header -->
    <div class="border-b border-slate-800 pb-4 space-y-1">
        <h1 class="text-3xl font-black bg-gradient-to-r from-amber-400 via-yellow-200 to-amber-600 bg-clip-text text-transparent flex items-center space-x-3">
            <i class="fa-solid fa-trophy text-amber-500 animate-pulse"></i>
            <span>{{ app()->getLocale() == 'ar' ? 'أفضل المودات هذا الأسبوع' : 'Top Mods Weekly' }}</span>
        </h1>
        <p class="text-xs text-slate-400 font-medium">Discover the most popular and highly viewed mods of the past 7 days.</p>
    </div>

    <!-- Filter Bar -->
    <form action="{{ route('mods.top-weekly') }}" method="GET" class="flex flex-col sm:flex-row items-center justify-between gap-4 glass-card rounded-2xl border border-slate-800 p-5">
        <div class="flex items-center space-x-3 rtl:space-x-reverse w-full sm:w-auto">
            <label class="text-xs text-slate-400 font-bold whitespace-nowrap">Filter by Game:</label>
            <select name="game_id" onchange="this.form.submit()" class="bg-slate-950 border border-slate-800 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-amber-500 w-full sm:w-48">
                <option value="">All Games</option>
                @foreach($games as $g)
                    <option value="{{ $g->id }}" {{ $selectedGameId == $g->id ? 'selected' : '' }}>{{ $g->name }}</option>
                @endforeach
            </select>
        </div>
    </form>

    <!-- Results Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($mods as $index => $mod)
            <div class="relative glass-card rounded-2xl border border-slate-800/80 hover:border-amber-500/40 p-5 space-y-4 flex flex-col justify-between transition-all duration-300 transform hover:-translate-y-1">
                
                <!-- Rank Badge -->
                <div class="absolute -top-3 -right-3 rtl:-left-3 rtl:-right-auto w-10 h-10 rounded-full bg-gradient-to-br {{ $index == 0 ? 'from-amber-400 to-yellow-600' : ($index == 1 ? 'from-slate-300 to-slate-500' : ($index == 2 ? 'from-amber-700 to-amber-900' : 'from-slate-800 to-slate-900')) }} shadow-lg flex items-center justify-center text-white font-black border-2 border-slate-950 z-10">
                    #{{ $mods->firstItem() + $index }}
                </div>

                <div class="space-y-3">
                    <!-- Thumbnail/Image -->
                    <div class="aspect-video w-full rounded-xl overflow-hidden bg-slate-950 border border-slate-800/60 relative skeleton">
                        @if($mod->local_image_path || $mod->image_url)
                            <img src="{{ $mod->local_image_path ?: $mod->image_url }}" alt="{{ $mod->name }}" onload="this.parentElement.classList.remove('skeleton')" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-700 bg-slate-900/40">
                                <i class="fa-solid fa-cube text-4xl"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Metadata -->
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-amber-500 uppercase tracking-wider">{{ $mod->game?->name ?? 'Unknown Game' }}</span>
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
                        <button onclick="openQuickView('{{ $mod->slug }}')" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold rounded-xl transition-all shadow-md"><i class="fa-solid fa-eye"></i></button>
                        <a href="{{ route('mods.show', $mod->slug) }}" class="flex-1 py-2 bg-gradient-to-r from-amber-500 to-yellow-600 hover:from-amber-400 hover:to-yellow-500 text-slate-950 text-xs font-bold rounded-xl text-center transition-all shadow-md">{{ app()->getLocale() == 'ar' ? 'عرض التفاصيل' : 'View Details' }}</a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center text-slate-500">
                <i class="fa-solid fa-folder-open text-4xl mb-4"></i>
                <p>{{ app()->getLocale() == 'ar' ? 'لا توجد مودات رائجة في آخر 7 أيام.' : 'No trending mods found in the last 7 days.' }}</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pt-6">
        {{ $mods->appends(request()->query())->links() }}
    </div>
</div>
@endsection
