@extends('layouts.app')

@section('title', $mod->name)

@section('meta')
<meta name="description" content="{{ Str::limit(strip_tags($mod->description ?: 'A mod for '.$mod->game->name.' on LoadOrderHub'), 155) }}">
<meta property="og:title" content="{{ $mod->name }} — {{ $mod->game->name }} Mod">
<meta property="og:description" content="{{ Str::limit(strip_tags($mod->description ?: 'Download and learn about '.$mod->name.' for '.$mod->game->name), 200) }}">
<meta property="og:type" content="website">
@if($mod->local_image_path || $mod->image_url)<meta property="og:image" content="{{ $mod->local_image_path ?: $mod->image_url }}">@endif
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $mod->name }} — {{ $mod->game->name }}">
<link rel="canonical" href="{{ url()->current() }}">

    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "SoftwareApplication",
      "name": "{{ $mod->name }}",
      "applicationCategory": "Game Modification",
      "operatingSystem": "Windows",
      "description": "{{ addslashes(strip_tags($mod->description ?: 'A mod for '.$mod->game->name)) }}",
      @if($mod->local_image_path || $mod->image_url)"image": "{{ $mod->local_image_path ?: $mod->image_url }}",@endif
      @if($mod->version)"softwareVersion": "{{ $mod->version }}",@endif
      @if($mod->author)"author": { "@@type": "Person", "name": "{{ $mod->author }}" },@endif
      @if($mod->downloads_count)"downloadCount": {{ $mod->downloads_count }},@endif
      "offers": { "@@type": "Offer", "price": "0", "priceCurrency": "USD" },
      "aggregateRating": {
        "@@type": "AggregateRating",
        "ratingValue": "{{ $mod->comments->avg('rating_stars') ?: 5 }}",
        "reviewCount": "{{ max($mod->comments->count(), 1) }}"
      },
      "breadcrumb": {
        "@@type": "BreadcrumbList",
        "itemListElement": [
          { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}" },
          { "@@type": "ListItem", "position": 2, "name": "{{ $mod->game->name }}", "item": "{{ route('games.show', $mod->game->slug) }}" },
          { "@@type": "ListItem", "position": 3, "name": "{{ $mod->name }}", "item": "{{ url()->current() }}" }
        ]
      }
    }
    </script>

<script>
    // Reading Mode Toggle
    function toggleReadingMode() {
        const leftCol = document.getElementById('left-column');
        const rightCol = document.getElementById('right-column');
        
        if (rightCol.classList.contains('hidden')) {
            rightCol.classList.remove('hidden');
            leftCol.classList.remove('lg:col-span-4');
            leftCol.classList.add('lg:col-span-3');
            showToast("{{ app()->getLocale() == 'ar' ? 'تم إغلاق وضع القراءة' : 'Focus Mode disabled' }}");
        } else {
            rightCol.classList.add('hidden');
            leftCol.classList.remove('lg:col-span-3');
            leftCol.classList.add('lg:col-span-4');
            showToast("{{ app()->getLocale() == 'ar' ? 'تم تفعيل وضع القراءة المريح' : 'Focus Mode enabled' }}", "success");
        }
    }
</script>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Breadcrumbs -->
    <nav class="flex text-xs text-slate-500 space-x-2 rtl:space-x-reverse">
        <a href="{{ route('home') }}" class="hover:text-slate-300">{{ __('messages.home') }}</a>
        <span>/</span>
        <a href="{{ route('games.show', $mod->game->slug) }}" class="hover:text-slate-300">
            {{ $mod->game->name }}
        </a>
        <span>/</span>
        <a href="{{ route('games.mods', $mod->game->slug) }}" class="hover:text-slate-300">Mods</a>
        <span>/</span>
        <span class="text-slate-300">{{ $mod->name }}</span>
    </nav>

    <!-- Mod Image Header Cover -->
    @if($mod->before_image_url && $mod->after_image_url)
        <!-- Before/After Slider -->
        <div class="relative rounded-2xl overflow-hidden aspect-[21/9] bg-slate-950 shadow-2xl border border-slate-800" id="before-after-slider">
            <div class="absolute inset-0 z-10 w-full h-full">
                <img src="{{ $mod->after_image_url }}" alt="After {{ $mod->name }}" class="w-full h-full object-cover">
            </div>
            <div class="absolute inset-0 z-20 w-1/2 h-full overflow-hidden border-r-2 border-white shadow-[1px_0_10px_rgba(0,0,0,0.5)] transition-all duration-75" id="slider-before-img">
                <img src="{{ $mod->before_image_url }}" alt="Before {{ $mod->name }}" class="w-[200%] h-full object-cover max-w-none origin-left" style="width: 200vw; /* This will be fixed by JS */">
            </div>
            <div class="absolute inset-0 z-30 flex items-center justify-center pointer-events-none">
                <div class="absolute w-8 h-8 bg-white/20 backdrop-blur border border-white rounded-full flex items-center justify-center shadow-lg transition-all duration-75" id="slider-handle">
                    <i class="fa-solid fa-arrows-left-right text-white text-xs"></i>
                </div>
            </div>
            <input type="range" min="0" max="100" value="50" class="absolute inset-0 z-40 w-full h-full opacity-0 cursor-ew-resize" id="slider-range">
            
            <div class="absolute bottom-4 left-4 z-40 bg-black/60 px-2 py-1 rounded text-white text-xs font-bold pointer-events-none">Before</div>
            <div class="absolute bottom-4 right-4 z-40 bg-black/60 px-2 py-1 rounded text-white text-xs font-bold pointer-events-none">After</div>
            <div class="absolute inset-0 z-30 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent pointer-events-none"></div>
        </div>
    @elseif($mod->image_url)
        <div class="relative rounded-2xl overflow-hidden aspect-[21/9] bg-slate-950 shadow-2xl border border-slate-800">
            <img src="{{ $mod->image_url }}" alt="{{ $mod->name }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>
        </div>
    @endif

    <!-- Main Title & Action Buttons -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-800 pb-6">
        <div class="space-y-2">
            <div class="flex flex-wrap gap-1.5 items-center">
                <span class="px-2.5 py-1 rounded bg-violet-600/10 border border-violet-500/20 text-xs font-bold text-violet-400">
                    {{ $mod->game->name }}
                </span>
                @foreach($mod->gameVersions as $version)
                    <span class="px-2.5 py-1 rounded bg-slate-900 border border-slate-800 text-xs font-mono font-bold text-slate-400">
                        {{ $version->version }}
                    </span>
                @endforeach
            </div>
            <div class="flex items-center gap-4">
                <h1 class="text-2xl md:text-4xl font-extrabold text-white">{{ $mod->name }}</h1>
                @if($mod->is_verified)
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-sm font-bold shadow-sm" title="تم التحقق من أمان هذا المود">
                        <i class="fa-solid fa-circle-check mr-1.5 rtl:ml-1.5"></i>
                        Verified
                    </span>
                @endif
            </div>
        </div>

        <!-- Download Actions -->
        <div class="flex flex-wrap items-center gap-3">
            <button onclick="toggleReadingMode()" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-800 border border-slate-700 hover:bg-slate-700 text-slate-300 font-semibold transition-all shadow-md" title="{{ app()->getLocale() == 'ar' ? 'وضع القراءة المريح' : 'Focus Reading Mode' }}">
                <i class="fa-solid fa-book-open text-xs"></i>
                <span class="text-xs hidden md:inline">{{ app()->getLocale() == 'ar' ? 'وضع القراءة' : 'Focus Mode' }}</span>
            </button>
            <button onclick="navigator.clipboard.writeText('{{ url()->current() }}').then(() => showToast('{{ app()->getLocale() == 'ar' ? 'تم نسخ الرابط بنجاح!' : 'Link copied to clipboard!' }}', 'success'))" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-800 border border-slate-700 hover:bg-slate-700 text-white font-semibold transition-all shadow-md">
                <i class="fa-solid fa-share-nodes text-xs"></i>
                <span class="text-xs">{{ app()->getLocale() == 'ar' ? 'مشاركة' : 'Share' }}</span>
            </button>
            <a href="https://ko-fi.com/loadorderhub" target="_blank" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-pink-600/20 border border-pink-500/30 hover:bg-pink-600/40 text-pink-400 font-semibold transition-all shadow-md" title="{{ app()->getLocale() == 'ar' ? 'ادعم صانع المود' : 'Tip Jar' }}">
                <i class="fa-solid fa-mug-hot text-xs"></i>
                <span class="text-xs">{{ app()->getLocale() == 'ar' ? 'ادعم المبدع' : 'Tip Jar' }}</span>
            </a>
            @auth
                <button onclick="reportMod({{ $mod->id }})" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-red-600/20 border border-red-500/30 hover:bg-red-600/40 text-red-400 font-semibold transition-all shadow-md">
                    <i class="fa-solid fa-flag text-xs"></i>
                    <span class="text-xs">Report Issue</span>
                </button>
            @endauth
            @if($mod->nexus_url)
                <a href="{{ route('link.redirect', ['url' => base64_encode($mod->nexus_url), 'mod' => $mod->id]) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-orange-600/20 border border-orange-500/30 hover:bg-orange-600/40 text-orange-400 font-semibold transition-all shadow-md">
                    <i class="fa-solid fa-download text-xs"></i>
                    <span class="text-xs">Download on Nexus Mods</span>
                </a>
            @endif
            @if($mod->steam_url)
                <a href="{{ route('link.redirect', ['url' => base64_encode($mod->steam_url), 'mod' => $mod->id]) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600/20 border border-blue-500/30 hover:bg-blue-600/40 text-blue-400 font-semibold transition-all shadow-md">
                    <i class="fa-brands fa-steam text-xs"></i>
                    <span class="text-xs">Subscribe on Steam Workshop</span>
                </a>
            @endif
            @if($mod->download_url && $mod->download_url !== $mod->nexus_url && $mod->download_url !== $mod->steam_url)
                <a href="{{ route('link.redirect', ['url' => base64_encode($mod->download_url), 'mod' => $mod->id]) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600/20 border border-emerald-500/30 hover:bg-emerald-600/40 text-emerald-400 font-semibold transition-all shadow-md">
                    <i class="fa-solid fa-link text-xs"></i>
                    <span class="text-xs">Direct Download</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Sponsored AdSense Banner Ad (Leaderboard) -->
    <div class="w-full text-center space-y-2 py-4">
        <span class="text-[9px] text-slate-600 font-bold uppercase tracking-widest">{{ __('messages.ad_space') }}</span>
        <div onclick="trackAdClick('mods_leaderboard_banner')" class="mx-auto max-w-4xl h-24 rounded-2xl border border-dashed border-slate-800 bg-slate-950/30 flex items-center justify-center text-xs text-slate-500 cursor-pointer hover:bg-slate-900/50 transition-colors">
            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                <i class="fa-solid fa-rectangle-ad text-3xl text-slate-700"></i>
                <span class="text-slate-400 font-medium">Google AdSense Leaderboard Banner<br><span class="text-[10px] text-slate-600">728x90 responsive advertisement banner</span></span>
            </div>
        </div>
    </div>

    <!-- Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8" id="main-layout-grid">
        
        <!-- Left Column: Details, ModPacks, and Comments -->
        <div class="lg:col-span-3 space-y-8 transition-all duration-500" id="left-column">
            
            @php
                $hasIssues = \Schema::hasColumn('mods', 'has_issues') && $mod->has_issues;
                $conflictsList = collect();
                if (\Schema::hasTable('mod_conflicts')) {
                    $conflictsList = $mod->conflicts;
                }
            @endphp

            @if($hasIssues)
                <div class="p-4 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-400 text-sm flex items-start gap-3 shadow-md">
                    <i class="fa-solid fa-triangle-exclamation text-lg mt-0.5 shrink-0"></i>
                    <div>
                        <strong class="font-bold">Warning: This mod has known issues or has been flagged!</strong>
                        @if($mod->issues_note)
                            <p class="text-xs text-slate-300 mt-1">{{ $mod->issues_note }}</p>
                        @else
                            <p class="text-xs text-slate-300 mt-1">Please use caution when adding this mod, as it may cause instability or conflicts.</p>
                        @endif
                    </div>
                </div>
            @endif

            @if($conflictsList->count() > 0)
                <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 text-sm flex items-start gap-3 shadow-md">
                    <i class="fa-solid fa-circle-xmark text-lg mt-0.5 shrink-0"></i>
                    <div>
                        <strong class="font-bold">Incompatible with the following mods:</strong>
                        <ul class="list-disc list-inside mt-1 text-xs text-slate-300 space-y-1">
                            @foreach($conflictsList as $conflict)
                                <li>
                                    <a href="{{ route('mods.show', $conflict->slug) }}" class="underline hover:text-red-300 font-bold">{{ $conflict->name }}</a>
                                    @if($conflict->pivot->reason)
                                        - <span class="italic text-slate-400">({{ $conflict->pivot->reason }})</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Description -->
            <div class="glass-card p-6 rounded-2xl border border-slate-800 space-y-2">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Description</h3>
                <div class="text-sm text-slate-300 leading-relaxed overflow-hidden transition-all duration-300 relative" id="mod-description-container" style="max-height: 120px;">
                    <div id="mod-description-text">
                        {{ $mod->description ?: 'This mod is curated as part of one or more mod load orders. It integrates seamlessly into the compatible game versions shown above.' }}
                    </div>
                    <div id="mod-description-gradient" class="absolute bottom-0 left-0 w-full h-12 bg-gradient-to-t from-slate-900 to-transparent"></div>
                </div>
                <button id="mod-read-more-btn" onclick="toggleReadMore()" class="text-xs text-violet-400 hover:text-violet-300 font-bold hidden mt-2">
                    Read More <i class="fa-solid fa-chevron-down ml-1"></i>
                </button>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const textEl = document.getElementById('mod-description-text');
                    const btn = document.getElementById('mod-read-more-btn');
                    if(textEl.scrollHeight > 120) {
                        btn.classList.remove('hidden');
                    } else {
                        const gradient = document.getElementById('mod-description-gradient');
                        if(gradient) gradient.classList.add('hidden');
                    }
                });
                function toggleReadMore() {
                    const container = document.getElementById('mod-description-container');
                    const btn = document.getElementById('mod-read-more-btn');
                    const gradient = document.getElementById('mod-description-gradient');
                    if(container.style.maxHeight === '120px') {
                        container.style.maxHeight = '2000px'; // large enough to show content
                        if(gradient) gradient.classList.add('hidden');
                        btn.innerHTML = 'Read Less <i class="fa-solid fa-chevron-up ml-1"></i>';
                    } else {
                        container.style.maxHeight = '120px';
                        if(gradient) gradient.classList.remove('hidden');
                        btn.innerHTML = 'Read More <i class="fa-solid fa-chevron-down ml-1"></i>';
                    }
                }
            </script>

            <!-- Sponsored Native Ad -->
            <div class="w-full text-center space-y-2 py-2">
                <span class="text-[9px] text-slate-650 font-bold uppercase tracking-widest block">{{ __('messages.ad_space') }}</span>
                <div class="h-20 rounded-2xl border border-dashed border-slate-800 bg-slate-950/30 flex items-center justify-center text-xs text-slate-500">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-rectangle-ad text-2xl text-slate-700"></i>
                        <span class="text-slate-400 font-medium">In-Content Native Banner (468x60)</span>
                    </div>
                </div>
            </div>

            <!-- Mod Packs Using This Mod -->
            <div class="glass-card rounded-2xl border border-slate-800 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-800/80 bg-slate-900/40">
                    <h3 class="font-bold text-white tracking-wide">
                        <i class="fa-solid fa-cubes text-violet-500 mr-2 rtl:ml-2"></i>
                        Mod Packs Featuring This Mod
                    </h3>
                </div>

                <div class="divide-y divide-slate-800/60">
                    @forelse($relatedModPacks as $pack)
                        <div class="p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 hover:bg-slate-850/10 transition-colors">
                            <div class="space-y-1">
                                <h4 class="font-bold text-white text-sm">
                                    <a href="{{ route('modpacks.show', $pack->id) }}" class="hover:text-violet-400 transition-colors">
                                        {{ app()->getLocale() == 'ar' ? $pack->title_ar : $pack->title_en }}
                                    </a>
                                </h4>
                                <p class="text-xs text-slate-400 line-clamp-1">
                                    {{ app()->getLocale() == 'ar' ? $pack->description_ar : $pack->description_en }}
                                </p>
                                <div class="flex items-center space-x-2 rtl:space-x-reverse text-[10px] text-slate-500">
                                    <span>By {{ $pack->creator->name }}</span>
                                    <span>•</span>
                                    <span>{{ $pack->views_count }} views</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                @foreach($pack->gameVersions as $v)
                                    <span class="px-2 py-0.5 rounded text-[10px] bg-slate-950 border border-slate-800 text-slate-400 font-mono">
                                        {{ $v->version }}
                                    </span>
                                @endforeach
                                <a href="{{ route('modpacks.show', $pack->id) }}" class="ml-2 px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-white text-[10px] font-bold transition-all">
                                    View Load Order
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-slate-650 text-xs">
                            No active mod packs listed featuring this mod yet.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Related Mods -->
            @if(isset($recommendedMods) && $recommendedMods->count() > 0)
            <div class="glass-card rounded-2xl border border-slate-800 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-800/80 bg-slate-900/40">
                    <h3 class="font-bold text-white tracking-wide">
                        <i class="fa-solid fa-link text-blue-500 mr-2 rtl:ml-2"></i>
                        {{ app()->getLocale() == 'ar' ? 'مودات ذات صلة قد تعجبك' : 'Related Mods You May Like' }}
                    </h3>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 p-4 bg-slate-950/20">
                    @foreach($recommendedMods as $recMod)
                    <a href="{{ route('mods.show', $recMod->slug) }}" class="group block space-y-2">
                        <div class="aspect-square rounded-xl overflow-hidden bg-slate-950 border border-slate-800 relative">
                            @if($recMod->local_image_path || $recMod->image_url)
                                <img src="{{ $recMod->local_image_path ?: $recMod->image_url }}" alt="{{ $recMod->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-700 bg-slate-900/40">
                                    <i class="fa-solid fa-cube text-2xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="text-xs font-bold text-slate-300 group-hover:text-blue-400 transition-colors line-clamp-2 text-center">{{ $recMod->name }}</div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Comments Section -->
            <div class="glass-card p-6 rounded-2xl border border-slate-800 space-y-6">
                <h3 class="text-lg font-bold text-white border-b border-slate-800 pb-3 flex items-center space-x-2 rtl:space-x-reverse">
                    <i class="fa-regular fa-comments text-violet-500"></i>
                    <span>Discussion (<span id="comments-count">{{ $mod->comments->count() }}</span>)</span>
                </h3>

                <!-- Post Comment Form -->
                @auth
                    <form id="comment-form" onsubmit="submitComment(event)" class="space-y-4">
                        @csrf
                        <input type="hidden" name="parent_id" id="form-parent-id" value="">
                        
                        <div id="reply-indicator" class="hidden flex items-center justify-between px-4 py-2 rounded-xl bg-violet-950/15 border border-violet-500/20 text-xs text-violet-400">
                            <span>Replying to comment...</span>
                            <button type="button" onclick="cancelReply()" class="text-slate-400 hover:text-white">
                                <i class="fa-solid fa-xmark text-sm"></i>
                            </button>
                        </div>

                        <div class="relative">
                            <textarea 
                                name="content" 
                                id="comment-content" 
                                rows="3" 
                                placeholder="Share your experience or ask questions about this mod..." 
                                class="w-full bg-slate-950 border border-slate-850 rounded-xl p-4 text-sm text-slate-200 placeholder-slate-600 focus:outline-none focus:border-violet-600"
                                required></textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-violet-600 to-blue-500 hover:from-violet-500 hover:to-blue-400 text-white font-semibold text-xs tracking-wide shadow-lg shadow-violet-500/10 hover:shadow-violet-500/20 transition-all">
                                Post Comment
                            </button>
                        </div>
                    </form>
                @else
                    <div class="p-4 rounded-xl bg-slate-950/40 border border-slate-850 text-center text-xs text-slate-500">
                        Please <a href="{{ route('login') }}" class="text-violet-400 hover:underline font-bold">Log in</a> to write a comment.
                    </div>
                @endauth

                <!-- Comments Tree Render -->
                <div id="comments-list" class="space-y-6 pt-4">
                    @forelse($mod->comments->where('parent_id', null) as $comment)
                        <div class="space-y-4" id="comment-node-{{ $comment->id }}">
                            <!-- Main Parent Comment Card -->
                            <div class="p-4 rounded-xl bg-slate-900/40 border border-slate-850">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                        <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-xs font-bold text-violet-400">
                                            {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-slate-200">{{ $comment->user->name }}</div>
                                            <div class="text-[10px] text-slate-500">{{ $comment->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Reply trigger -->
                                    @auth
                                        <button onclick="setReplyTo({{ $comment->id }}, '{{ $comment->user->name }}')" class="text-xs text-violet-400 hover:text-violet-300 font-semibold flex items-center space-x-1 rtl:space-x-reverse">
                                            <i class="fa-solid fa-reply"></i>
                                            <span>Reply</span>
                                        </button>
                                    @endauth
                                </div>
                                <p class="text-xs md:text-sm text-slate-300 leading-relaxed pl-10 rtl:pl-0 rtl:pr-10">{{ $comment->content }}</p>
                            </div>

                            <!-- Nested Replies Rendering -->
                            <div class="pl-8 md:pl-12 rtl:pl-0 rtl:pr-8 rtl:md:pr-12 space-y-4 border-l border-slate-850/80 rtl:border-l-0 rtl:border-r rtl:border-slate-850/80" id="replies-container-{{ $comment->id }}">
                                @foreach($comment->replies as $reply)
                                    <div class="p-4 rounded-xl bg-slate-950/30 border border-slate-900">
                                        <div class="flex items-center space-x-2 rtl:space-x-reverse mb-2">
                                            <div class="w-6 h-6 rounded-full bg-slate-900 flex items-center justify-center text-[10px] font-bold text-blue-400">
                                                {{ strtoupper(substr($reply->user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="text-xs font-bold text-slate-200">{{ $reply->user->name }}</div>
                                                <div class="text-[9px] text-slate-500">{{ $reply->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                        <p class="text-xs text-slate-300 leading-relaxed pl-8 rtl:pl-0 rtl:pr-8">{{ $reply->content }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-slate-650 text-xs" id="no-comments-placeholder">
                            <i class="fa-regular fa-comment-dots text-xl mb-2 text-slate-700 block"></i>
                            Be the first to say something about this mod!
                        </div>
                    @endforelse
                </div>

            </div>
        </div>

        <!-- Right Column: Sidebar metadata -->
        <div class="lg:col-span-1 space-y-6 transition-all duration-500" id="right-column">
            
            <!-- Quick Actions Panel -->
            <div class="glass-card p-6 rounded-2xl border border-slate-800 space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Mod Information</h3>
                
                <div class="space-y-0 text-xs divide-y divide-slate-800/60">
                    <div class="flex justify-between py-2.5">
                        <span class="text-slate-500">Game</span>
                        <span class="text-slate-200 font-bold">{{ $mod->game->name }}</span>
                    </div>
                    @if($mod->version)
                    <div class="flex justify-between py-2.5">
                        <span class="text-slate-500">{{ app()->getLocale()=='ar' ? 'الإصدار' : 'Version' }}</span>
                        <span class="text-emerald-400 font-mono font-bold">v{{ $mod->version }}</span>
                    </div>
                    @endif
                    @if($mod->author)
                    <div class="flex justify-between py-2.5">
                        <span class="text-slate-500">{{ app()->getLocale()=='ar' ? 'المؤلف' : 'Author' }}</span>
                        <span class="text-slate-200 font-bold">{{ $mod->author }}</span>
                    </div>
                    @endif
                    @if($mod->downloads_count)
                    <div class="flex justify-between py-2.5">
                        <span class="text-slate-500">{{ app()->getLocale()=='ar' ? 'التحميلات' : 'Downloads' }}</span>
                        <span class="text-violet-400 font-bold">{{ number_format($mod->downloads_count) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between py-2.5">
                        <span class="text-slate-500">{{ app()->getLocale()=='ar' ? 'المشاهدات' : 'Views' }}</span>
                        <span class="text-slate-200 font-bold">{{ number_format($mod->views_count ?? 0) }}</span>
                    </div>
                    @if($mod->fps_impact)
                    <div class="flex justify-between py-2.5">
                        <span class="text-slate-500">FPS Impact</span>
                        <span class="px-2 py-0.5 rounded bg-amber-500/10 text-amber-400 border border-amber-500/20 font-mono text-[10px]">{{ $mod->fps_impact }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between py-2.5">
                        <span class="text-slate-500">{{ app()->getLocale()=='ar' ? 'المصدر' : 'Source' }}</span>
                        <span class="text-slate-200 font-bold flex items-center gap-1">
                            <i class="{{ $mod->source_icon }} text-violet-400"></i>
                            {{ $mod->source_label }}
                        </span>
                    </div>
                    <div class="flex justify-between py-2.5">
                        <span class="text-slate-500">{{ app()->getLocale()=='ar' ? 'في تجميعات' : 'In Packs' }}</span>
                        <span class="text-slate-200 font-bold">{{ $relatedModPacks->count() }}</span>
                    </div>
                    @if($mod->gameVersions->count())
                    <div class="py-2.5">
                        <span class="text-slate-500 block mb-1.5">{{ app()->getLocale()=='ar' ? 'الإصدارات المتوافقة' : 'Compatible Versions' }}</span>
                        <div class="flex flex-wrap gap-1">
                            @foreach($mod->gameVersions as $gv)
                                <span class="px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-mono text-[10px]">{{ $gv->version }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if($mod->tags)
                    <div class="py-2.5">
                        <span class="text-slate-500 block mb-1.5">{{ app()->getLocale()=='ar' ? 'التصنيفات' : 'Tags' }}</span>
                        <div class="flex flex-wrap gap-1">
                            @foreach($mod->tags as $tag)
                                <span class="px-2 py-0.5 rounded-full bg-slate-800 text-slate-400 text-[10px] border border-slate-700">#{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Dependencies --}}
            @if($mod->dependencies->count())
            <div class="glass-card p-5 rounded-2xl border border-slate-800 space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 flex items-center gap-2">
                    <i class="fa-solid fa-link text-blue-400"></i>
                    {{ app()->getLocale()=='ar' ? 'يتطلب هذه المودات' : 'Required Mods' }}
                </h3>
                <div class="space-y-2">
                    @foreach($mod->dependencies as $dep)
                        <a href="{{ route('mods.show', $dep->slug) }}" class="flex items-center gap-2 p-2 rounded-lg hover:bg-slate-800/50 transition group">
                            <div class="w-8 h-8 rounded bg-slate-900 border border-slate-800 overflow-hidden flex items-center justify-center shrink-0">
                                @if($dep->local_image_path || $dep->image_url)
                                    <img src="{{ $dep->local_image_path ?: $dep->image_url }}" alt="" class="w-full h-full object-cover" loading="lazy">
                                @else
                                    <i class="fa-solid fa-cube text-slate-600 text-xs"></i>
                                @endif
                            </div>
                            <span class="text-xs font-bold text-slate-300 group-hover:text-blue-400 transition truncate">{{ $dep->name }}</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[9px] text-slate-600 shrink-0 ml-auto"></i>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Sidebar Ad Slot -->
            <div class="glass-card p-4 rounded-2xl border border-slate-800 text-center space-y-3 sticky top-24">
                <span class="text-[9px] text-slate-650 font-bold uppercase tracking-widest block">{{ __('messages.ad_space') }}</span>
                <div class="h-64 rounded-xl border border-slate-850 bg-slate-950/40 flex items-center justify-center text-xs text-slate-500 p-4">
                    <div>
                        <i class="fa-solid fa-rectangle-ad text-3xl mb-2 text-slate-700"></i>
                        <p>Sidebar Banner Ad<br><span class="text-[10px] text-slate-600">300x250 responsive slot</span></p>
                    </div>
                </div>
            </div>

            <!-- Recommended Mods Card -->
            <div class="glass-card p-6 rounded-2xl border border-slate-800 space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">
                    <i class="fa-solid fa-wand-magic-sparkles text-violet-500 mr-1.5"></i>
                    Recommended Mods
                </h3>
                
                <div class="space-y-3.5">
                    @forelse($recommendedMods as $recMod)
                        <a href="{{ route('mods.show', $recMod->slug) }}" class="flex items-center gap-3 hover:bg-slate-900/40 p-2 -mx-2 rounded-xl transition-colors group">
                            <div class="w-12 h-12 rounded-lg bg-slate-950 border border-slate-850 overflow-hidden shrink-0 flex items-center justify-center">
                                @if($recMod->local_image_path || $recMod->image_url)
                                    <img src="{{ $recMod->local_image_path ?: $recMod->image_url }}" alt="" class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-cube text-slate-700 text-lg"></i>
                                @endif
                            </div>
                            <div class="space-y-0.5 min-w-0">
                                <h4 class="text-xs font-bold text-white group-hover:text-violet-400 transition-colors truncate">{{ $recMod->name }}</h4>
                                <span class="text-[9px] text-slate-500 font-bold block">{{ number_format($recMod->total_views) }} views</span>
                            </div>
                        </a>
                    @empty
                        <p class="text-[11px] text-slate-600">No matching recommendations.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Set replying comments targets
    function setReplyTo(commentId, authorName) {
        document.getElementById('form-parent-id').value = commentId;
        const indicator = document.getElementById('reply-indicator');
        indicator.querySelector('span').innerText = `Replying to ${authorName}...`;
        indicator.classList.remove('hidden');
        document.getElementById('comment-content').focus();
    }

    function cancelReply() {
        document.getElementById('form-parent-id').value = '';
        document.getElementById('reply-indicator').classList.add('hidden');
    }

    // Submit Comments & Nested Replies using AJAX
    function submitComment(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const url = "{{ route('mods.comments.store', $mod->id) }}";
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const comment = data.comment;
                
                // Remove placeholder if present
                const placeholder = document.getElementById('no-comments-placeholder');
                if (placeholder) placeholder.remove();

                // Increment comment counter
                const currentCount = parseInt(document.getElementById('comments-count').innerText);
                document.getElementById('comments-count').innerText = currentCount + 1;

                if (comment.parent_id) {
                    // It is a reply, insert in the appropriate replies container
                    const repliesContainer = document.getElementById(`replies-container-${comment.parent_id}`);
                    const replyHtml = `
                        <div class="p-4 rounded-xl bg-slate-950/30 border border-slate-900">
                            <div class="flex items-center space-x-2 rtl:space-x-reverse mb-2">
                                <div class="w-6 h-6 rounded-full bg-slate-900 flex items-center justify-center text-[10px] font-bold text-blue-400">
                                    ${comment.user_name.substring(0, 2).toUpperCase()}
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-slate-200">${comment.user_name}</div>
                                    <div class="text-[9px] text-slate-500">${comment.created_at}</div>
                                </div>
                            </div>
                            <p class="text-xs text-slate-300 leading-relaxed pl-8 rtl:pl-0 rtl:pr-8">${comment.content}</p>
                        </div>
                    `;
                    repliesContainer.insertAdjacentHTML('beforeend', replyHtml);
                } else {
                    // Main parent comment, insert in comments list
                    const commentsList = document.getElementById('comments-list');
                    const commentHtml = `
                        <div class="space-y-4" id="comment-node-${comment.id}">
                            <div class="p-4 rounded-xl bg-slate-900/40 border border-slate-850">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                        <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-xs font-bold text-violet-400">
                                            ${comment.user_name.substring(0, 2).toUpperCase()}
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-slate-200">${comment.user_name}</div>
                                            <div class="text-[10px] text-slate-500">${comment.created_at}</div>
                                        </div>
                                    </div>
                                    <button onclick="setReplyTo(${comment.id}, '${comment.user_name}')" class="text-xs text-violet-400 hover:text-violet-300 font-semibold flex items-center space-x-1 rtl:space-x-reverse">
                                        <i class="fa-solid fa-reply"></i>
                                        <span>Reply</span>
                                    </button>
                                </div>
                                <p class="text-xs md:text-sm text-slate-300 leading-relaxed pl-10 rtl:pl-0 rtl:pr-10">${comment.content}</p>
                            </div>
                            <div class="pl-8 md:pl-12 rtl:pl-0 rtl:pr-8 rtl:md:pr-12 space-y-4 border-l border-slate-850/80 rtl:border-l-0 rtl:border-r rtl:border-slate-850/80" id="replies-container-${comment.id}"></div>
                        </div>
                    `;
                    commentsList.insertAdjacentHTML('afterbegin', commentHtml);
                }

                // Reset form fields
                form.reset();
                cancelReply();
            }
        })
        .catch(err => console.error('Comment posting failed:', err));
    }

    // Before/After Slider Logic
    const sliderRange = document.getElementById('slider-range');
    const sliderBeforeImg = document.getElementById('slider-before-img');
    const sliderHandle = document.getElementById('slider-handle');
    
    if (sliderRange && sliderBeforeImg && sliderHandle) {
        // Fix image width to match container exactly
        const updateImageWidth = () => {
            const containerWidth = sliderRange.parentElement.offsetWidth;
            sliderBeforeImg.querySelector('img').style.width = containerWidth + 'px';
        };
        
        window.addEventListener('resize', updateImageWidth);
        updateImageWidth();

        sliderRange.addEventListener('input', function(e) {
            const val = e.target.value;
            sliderBeforeImg.style.width = val + '%';
            sliderHandle.style.left = val + '%';
        });
        
        // Initialize position
        sliderHandle.style.left = '50%';
        sliderHandle.style.transform = 'translateX(-50%)';
    }
</script>
@endsection
