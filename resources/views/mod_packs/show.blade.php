@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? $modPack->title_ar : $modPack->title_en)

@section('meta')
    @php
        $title = app()->getLocale() == 'ar' ? $modPack->title_ar : $modPack->title_en;
        $description = app()->getLocale() == 'ar' ? $modPack->description_ar : $modPack->description_en;
        $ogImage = count($modPack->mods) > 0 && ($modPack->mods->first()->local_image_path || $modPack->mods->first()->image_url) ? ($modPack->mods->first()->local_image_path ?: $modPack->mods->first()->image_url) : asset('images/og-image.png');
    @endphp
    <meta name="description" content="{{ Str::limit(strip_tags($description), 150) }}">
    <meta property="og:title" content="{{ $title }} - LoadOrderHub">
    <meta property="og:description" content="{{ Str::limit(strip_tags($description), 150) }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ route('modpacks.show', $modPack->id) }}">
    <link rel="canonical" href="{{ route('modpacks.show', $modPack->id) }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta name="twitter:card" content="summary_large_image">

    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "ItemList",
      "name": "{{ $title }}",
      "description": "{{ addslashes(strip_tags($description)) }}",
      "numberOfItems": {{ $modPack->mods->count() }},
      "itemListElement": [
        @foreach($modPack->mods as $index => $mpMod)
        {
          "@@type": "ListItem",
          "position": {{ $index + 1 }},
          "item": {
            "@@type": "SoftwareApplication",
            "name": "{{ $mpMod->name }}",
            "url": "{{ route('mods.show', $mpMod->slug ?: $mpMod->id) }}"
          }
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
      ]
    }
    </script>
@endsection

@section('content')
@php
    $title = app()->getLocale() == 'ar' ? $modPack->title_ar : $modPack->title_en;
    $description = app()->getLocale() == 'ar' ? $modPack->description_ar : $modPack->description_en;
    $isNegativeRating = $modPack->downvotes > 5 && $modPack->downvotes > $modPack->upvotes;
    
    // Average Star Rating calculation
    $ratedComments = $modPack->comments->whereNotNull('rating_stars');
    $ratingCount = $ratedComments->count();
    $avgRating = $ratingCount > 0 ? round($ratedComments->avg('rating_stars'), 1) : 0;

    // Detect internal conflicts within the mod list of this modpack safely
    $modIds = $modPack->mods->pluck('id')->toArray();
    $conflicts = collect();
    $conflictPairs = [];

    if (\Schema::hasTable('mod_conflicts')) {
        $conflicts = \DB::table('mod_conflicts')
            ->whereIn('mod_id', $modIds)
            ->whereIn('conflicts_with_mod_id', $modIds)
            ->get();

        $modsById = $modPack->mods->keyBy('id');
        foreach ($conflicts as $c) {
            // Prevent duplicate pairs A-B and B-A in display
            $key = min($c->mod_id, $c->conflicts_with_mod_id) . '-' . max($c->mod_id, $c->conflicts_with_mod_id);
            if (!isset($conflictPairs[$key])) {
                $mod1 = $modsById->get($c->mod_id);
                $mod2 = $modsById->get($c->conflicts_with_mod_id);
                if ($mod1 && $mod2) {
                    $conflictPairs[$key] = [
                        'mod1' => $mod1,
                        'mod2' => $mod2,
                        'reason' => $c->reason
                    ];
                }
            }
        }
    }

    // Detect mods in this mod pack that are flagged with issues safely
    $flaggedMods = collect();
    if (\Schema::hasColumn('mods', 'has_issues')) {
        $flaggedMods = $modPack->mods->where('has_issues', true);
    }
@endphp

<div class="space-y-6">
    <!-- Breadcrumbs -->
    <nav class="flex text-xs text-slate-500 space-x-2 rtl:space-x-reverse">
        <a href="{{ route('home') }}" class="hover:text-slate-300">{{ __('messages.home') }}</a>
        <span>/</span>
        <a href="{{ route('games.show', $modPack->gameVersion->game->slug) }}" class="hover:text-slate-300">
            {{ $modPack->gameVersion->game->name }}
        </a>
        <span>/</span>
        <span class="text-slate-300 line-clamp-1">{{ $title }}</span>
    </nav>

    <!-- Main Title & Rating Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-800 pb-6">
        <div class="space-y-2">
            <span class="px-2.5 py-1 rounded bg-violet-600/10 border border-violet-500/20 text-xs font-bold text-violet-400">
                {{ __('messages.game') }} {{ $modPack->gameVersion->version }}
            </span>
            <h1 class="text-xl md:text-3xl font-extrabold text-white leading-snug">
                {{ $title }}
                @if($modPack->is_private)
                    <span class="inline-block align-middle px-2 py-1 ml-2 rtl:mr-2 rtl:ml-0 text-xs font-bold rounded-lg bg-slate-800 text-slate-400 border border-slate-700">
                        <i class="fa-solid fa-lock"></i> {{ app()->getLocale() == 'ar' ? 'خاص' : 'Private' }}
                    </span>
                @endif
            </h1>
            @if($ratingCount > 0)
                <div class="flex items-center space-x-2 text-xs text-amber-500 mt-1">
                    <div class="flex items-center gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa-{{ $i <= round($avgRating) ? 'solid' : 'regular' }} fa-star"></i>
                        @endfor
                    </div>
                    <span class="font-bold text-slate-300">{{ $avgRating }} / 5</span>
                    <span class="text-slate-550">({{ $ratingCount }} reviews)</span>
                </div>
            @endif
        </div>

        <!-- Voting Actions Component -->
        <div class="flex items-center space-x-2 rtl:space-x-reverse">
            <!-- Upvote Button -->
            <button id="upvote-btn" onclick="submitVote(true)" class="flex items-center space-x-2 rtl:space-x-reverse px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-850 hover:bg-slate-850 hover:border-emerald-500/50 text-slate-300 hover:text-emerald-400 font-semibold transition-all">
                <i class="fa-regular fa-thumbs-up"></i>
                <span class="text-xs">{{ __('messages.upvote') }}</span>
                <span id="upvotes-count" class="px-2 py-0.5 rounded bg-slate-950 font-bold text-slate-300">{{ $modPack->upvotes }}</span>
            </button>
            
            <!-- Downvote Button -->
            <button id="downvote-btn" onclick="submitVote(false)" class="flex items-center space-x-2 rtl:space-x-reverse px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-850 hover:bg-slate-850 hover:border-red-500/50 text-slate-300 hover:text-red-400 font-semibold transition-all">
                <i class="fa-regular fa-thumbs-down"></i>
                <span class="text-xs">{{ __('messages.downvote') }}</span>
                <span id="downvotes-count" class="px-2 py-0.5 rounded bg-slate-950 font-bold text-slate-300">{{ $modPack->downvotes }}</span>
            </button>

            <!-- Save/Bookmark Button -->
            @auth
                @php
                    $isSaved = in_array($modPack->id, auth()->user()->profile?->saved_packs ?? []);
                @endphp
                <button id="save-btn" onclick="toggleSave()" class="flex items-center justify-center w-11 h-11 rounded-xl bg-slate-900 border {{ $isSaved ? 'border-violet-500 text-violet-400 font-bold' : 'border-slate-850 text-slate-400 hover:text-white' }} transition-all" title="{{ app()->getLocale() == 'ar' ? 'حفظ للتفضيلات' : 'Save Bookmark' }}">
                    <i class="{{ $isSaved ? 'fa-solid' : 'fa-regular' }} fa-bookmark"></i>
                </button>
            @endauth

            <!-- Share Button -->
            <button onclick="copyShareLink(this)" class="flex items-center justify-center w-11 h-11 rounded-xl bg-slate-900 border border-slate-850 text-slate-400 hover:text-blue-400 hover:border-blue-500/50 transition-all group" title="{{ app()->getLocale() == 'ar' ? 'نسخ رابط المشاركة' : 'Copy Share Link' }}">
                <i class="fa-solid fa-share-nodes"></i>
            </button>

            <!-- Reading Mode Button -->
            <button onclick="toggleReadingMode()" class="flex items-center justify-center w-11 h-11 rounded-xl bg-slate-900 border border-slate-850 text-slate-400 hover:text-white hover:border-slate-700 transition-all shadow-md" title="{{ app()->getLocale() == 'ar' ? 'وضع القراءة المريح' : 'Focus Reading Mode' }}">
                <i class="fa-solid fa-book-open text-sm"></i>
            </button>
        </div>
    </div>

    <!-- Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Left Column: Media, Mod Order, and Comments -->
        <div class="lg:col-span-3 space-y-8 transition-all duration-500" id="left-column">
            
            <!-- Warning block for poor rating status -->
            @if($isNegativeRating)
                <div class="flex items-start space-x-3 rtl:space-x-reverse p-4 rounded-2xl bg-red-500/10 border border-red-500/20 text-red-400">
                    <i class="fa-solid fa-circle-exclamation text-2xl flex-shrink-0 mt-0.5"></i>
                    <div class="space-y-1">
                        <h4 class="font-bold text-sm">Critical Warning</h4>
                        <p class="text-xs leading-relaxed">{{ __('messages.warning_low_rating') }}</p>
                    </div>
                </div>
            @endif

            <!-- Warning for missing dependencies -->
            @if(count($modPack->missing_dependencies) > 0)
                <div class="flex items-start space-x-3 rtl:space-x-reverse p-4 rounded-2xl bg-orange-500/10 border border-orange-500/25 text-orange-400">
                    <i class="fa-solid fa-link-slash text-xl flex-shrink-0 mt-0.5"></i>
                    <div class="space-y-1 text-xs">
                        <h4 class="font-bold text-sm">{{ app()->getLocale() == 'ar' ? 'تنبيه مودات مفقودة' : 'Missing Dependencies Warning' }}</h4>
                        <p class="leading-relaxed">{{ app()->getLocale() == 'ar' ? 'بعض المودات تتطلب مودات أساسية غير موجودة في هذه التجميعة لكي تعمل:' : 'Some mods require missing base mods to function properly:' }}</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            @foreach($modPack->missing_dependencies as $missing)
                                <li>
                                    <span class="font-bold text-white">{{ $missing['required_by']->name }}</span> 
                                    <span class="text-slate-400 font-semibold">{{ app()->getLocale() == 'ar' ? 'يحتاج إلى' : 'requires' }}</span> 
                                    <a href="{{ route('mods.show', $missing['missing_mod']->slug) }}" class="underline font-bold text-white hover:text-orange-300">{{ $missing['missing_mod']->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Warning for conflicting mods in the mod pack -->
            @if(count($conflictPairs) > 0)
                <div class="flex items-start space-x-3 rtl:space-x-reverse p-4 rounded-2xl bg-red-500/10 border border-red-500/25 text-red-400">
                    <i class="fa-solid fa-triangle-exclamation text-xl flex-shrink-0 mt-0.5"></i>
                    <div class="space-y-1 text-xs">
                        <h4 class="font-bold text-sm">{{ app()->getLocale() == 'ar' ? 'تنبيه تعارض المودات' : 'Mod Incompatibility Warning' }}</h4>
                        <p class="leading-relaxed">{{ app()->getLocale() == 'ar' ? 'تم الكشف عن وجود مودات غير متوافقة في هذه التجميعة قد تسبب مشاكل أو توقف للعبة:' : 'This modpack contains conflicting mods that may cause crashes or glitches when loaded together:' }}</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            @foreach($conflictPairs as $pair)
                                <li>
                                    <span class="font-bold text-white">{{ $pair['mod1']->name }}</span> 
                                    <span class="text-slate-400 font-semibold">{{ app()->getLocale() == 'ar' ? 'يتعارض مع' : 'conflicts with' }}</span> 
                                    <span class="font-bold text-white">{{ $pair['mod2']->name }}</span>
                                    @if($pair['reason'])
                                        - <span class="italic text-slate-400">({{ $pair['reason'] }})</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Warning for mods with known issues in the mod pack -->
            @if($flaggedMods->count() > 0)
                <div class="flex items-start space-x-3 rtl:space-x-reverse p-4 rounded-2xl bg-amber-500/10 border border-amber-500/25 text-amber-400">
                    <i class="fa-solid fa-triangle-exclamation text-xl flex-shrink-0 mt-0.5"></i>
                    <div class="space-y-1 text-xs">
                        <h4 class="font-bold text-sm">{{ app()->getLocale() == 'ar' ? 'تنبيه مودات بها مشاكل' : 'Problematic Mods Warning' }}</h4>
                        <p class="leading-relaxed">{{ app()->getLocale() == 'ar' ? 'تحتوي التجميعة على مودات تم الإبلاغ عن وجود مشاكل بها:' : 'The following mods in this pack have known issues or reports:' }}</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            @foreach($flaggedMods as $fMod)
                                <li>
                                    <a href="{{ route('mods.show', $fMod->slug) }}" class="underline font-bold text-white hover:text-amber-300">{{ $fMod->name }}</a>
                                    @if($fMod->issues_note)
                                        - <span class="text-slate-300">({{ $fMod->issues_note }})</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- YouTube Video Responsive Player with Preview Thumbnail -->
            @if($modPack->youtube_video_id)
                <div class="relative rounded-2xl overflow-hidden aspect-video bg-slate-950 shadow-2xl border border-slate-800 group mb-8" id="yt-player-container">
                    <img src="{{ $modPack->local_thumbnail_path ?? $modPack->youtube_thumbnail_url ?? 'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?w=1200&auto=format&fit=crop&q=80' }}" 
                         alt="Video Preview" 
                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-slate-950/40 flex items-center justify-center group-hover:bg-slate-950/20 transition-all cursor-pointer" onclick="loadYoutubeIframe()">
                        <div class="w-16 h-16 rounded-full bg-violet-600 hover:bg-violet-500 flex items-center justify-center text-white shadow-xl shadow-violet-600/30 transform hover:scale-110 transition-all">
                            <i class="fa-solid fa-play text-xl ml-1 rtl:mr-1"></i>
                        </div>
                    </div>
                </div>

                <script>
                    function loadYoutubeIframe() {
                        const container = document.getElementById('yt-player-container');
                        container.innerHTML = `
                            <iframe 
                                src="https://www.youtube.com/embed/{{ $modPack->youtube_video_id }}?autoplay=1" 
                                title="YouTube video player" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                allowfullscreen 
                                class="absolute inset-0 w-full h-full">
                            </iframe>
                        `;
                    }
                </script>
            @endif

            <!-- Description -->
            <div class="glass-card p-6 rounded-2xl border border-slate-800 space-y-2">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Description</h3>
                <p class="text-sm text-slate-300 leading-relaxed">{{ $description }}</p>
            </div>

            <!-- Mod Load Order Table -->
            <div class="glass-card rounded-2xl border border-slate-800 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-800/80 bg-slate-900/40 flex items-center justify-between">
                    <h3 class="font-bold text-white tracking-wide">
                        <i class="fa-solid fa-list-ol text-violet-500 mr-2 rtl:ml-2"></i>
                        {{ __('messages.copy_order') }}
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left rtl:text-right text-sm">
                        <thead class="bg-slate-950/60 text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-800">
                            <tr>
                                <th class="px-6 py-3 w-16 text-center">#</th>
                                <th class="px-6 py-3">Mod Name</th>
                                <th class="px-6 py-3 text-right rtl:text-left">{{ app()->getLocale() == 'ar' ? 'رابط التحميل' : 'Download Link' }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @foreach($modPack->mods as $index => $mod)
                                <tr class="hover:bg-slate-850/30 transition-colors">
                                    <td class="px-6 py-4 font-mono font-bold text-center text-violet-400">{{ $mod->load_order }}</td>
                                    <td class="px-6 py-4 text-white font-semibold">
                                        <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                            <div class="w-10 h-10 rounded-lg overflow-hidden bg-slate-900 border border-slate-800 flex-shrink-0 flex items-center justify-center">
                                                @if($mod->local_image_path || $mod->image_url)
                                                    <img src="{{ $mod->local_image_path ?: $mod->image_url }}" alt="{{ $mod->name }}" class="w-full h-full object-cover" loading="lazy">
                                                @elseif($mod->steam_url)
                                                    <i class="fa-brands fa-steam text-violet-500 text-lg"></i>
                                                @else
                                                    <i class="fa-solid fa-cube text-blue-400 text-md"></i>
                                                @endif
                                            </div>
                                            <div class="space-y-1">
                                                <div class="flex flex-wrap items-center gap-1.5">
                                                    @if($mod->slug)
                                                        <a href="{{ route('mods.show', $mod->slug) }}" class="hover:text-violet-400 transition-colors block font-bold text-white">
                                                            {{ $mod->name }}
                                                        </a>
                                                    @else
                                                        <span class="block font-bold text-white">{{ $mod->name }}</span>
                                                    @endif

                                                    @if(\Schema::hasColumn('mods', 'has_issues') && $mod->has_issues)
                                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-amber-500/15 border border-amber-500/40 text-amber-400 rounded text-[9px] font-bold" title="{{ $mod->issues_note }}">
                                                            <i class="fa-solid fa-triangle-exclamation"></i> {{ app()->getLocale() == 'ar' ? 'مشكلة' : 'Issue' }}
                                                        </span>
                                                    @endif

                                                    @php
                                                        $isConflicting = false;
                                                        if (\Schema::hasTable('mod_conflicts')) {
                                                            $isConflicting = $conflicts->contains(function($c) use ($mod) {
                                                                return $c->mod_id == $mod->id || $c->conflicts_with_mod_id == $mod->id;
                                                            });
                                                        }
                                                    @endphp
                                                    @if($isConflicting)
                                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-red-500/15 border border-red-500/40 text-red-400 rounded text-[9px] font-bold">
                                                            <i class="fa-solid fa-circle-xmark"></i> {{ app()->getLocale() == 'ar' ? 'تعارض' : 'Conflict' }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="flex flex-wrap gap-1 items-center">
                                                    @foreach($mod->gameVersions as $v)
                                                        <span class="px-1.5 py-0.5 rounded text-[8px] bg-slate-950 border border-slate-850 text-slate-500 font-mono">
                                                            {{ $v->version }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right rtl:text-left">
                                        @php
                                            $url = $mod->best_url;
                                            $label = $mod->source_label;
                                            $icon = $mod->source_icon;
                                        @endphp
                                        @if($url)
                                            <a href="{{ route('link.redirect', ['url' => base64_encode($url), 'mod' => $mod->id]) }}" target="_blank" class="inline-flex items-center text-xs text-violet-400 hover:text-violet-300 hover:underline space-x-1.5 rtl:space-x-reverse font-medium">
                                                <i class="{{ $icon }} text-[11px] mr-1 rtl:ml-1"></i>
                                                <span>{{ $label }}</span>
                                            </a>
                                        @else
                                            <span class="text-xs text-slate-650">-</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Native Advertisement component injected in the middle of the mod table -->
                                @if($index === 2)
                                    <tr class="bg-violet-950/5 border-y border-dashed border-violet-800/30">
                                        <td colspan="3" class="px-6 py-6 text-center">
                                            <x-ad-slot type="in_content" />
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="glass-card p-6 rounded-2xl border border-slate-800 space-y-6">
                <h3 class="text-lg font-bold text-white border-b border-slate-800 pb-3 flex items-center space-x-2 rtl:space-x-reverse">
                    <i class="fa-regular fa-comments text-violet-500"></i>
                    <span>{{ __('messages.comments') }} (<span id="comments-count">{{ $modPack->comments->count() }}</span>)</span>
                </h3>

                <!-- Post Comment Form -->
                <form id="comment-form" onsubmit="submitComment(event)" class="space-y-4">
                    @csrf
                    <input type="hidden" name="parent_id" id="form-parent-id" value="">
                    
                    <div id="reply-indicator" class="hidden flex items-center justify-between px-4 py-2 rounded-xl bg-violet-950/15 border border-violet-500/20 text-xs text-violet-400">
                        <span>Replying to comment...</span>
                        <button type="button" onclick="cancelReply()" class="text-slate-400 hover:text-white">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>

                    <!-- Rating Stars Selector (1 to 5) -->
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs text-slate-400 font-semibold">Rate this Pack (Optional):</span>
                        <div class="flex items-center gap-1 text-slate-600" id="star-rating-selector">
                            <input type="hidden" name="rating_stars" id="selected-stars" value="">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" onclick="setRatingStars({{ $i }})" class="hover:scale-110 transition-transform focus:outline-none" data-val="{{ $i }}">
                                    <i class="fa-regular fa-star text-base hover:text-amber-400 star-item"></i>
                                </button>
                            @endfor
                        </div>
                    </div>

                    <div class="relative">
                        <textarea 
                            name="content" 
                            id="comment-content" 
                            rows="3" 
                            placeholder="{{ __('messages.write_comment') }}" 
                            class="w-full bg-slate-950 border border-slate-850 rounded-xl p-4 text-sm text-slate-200 placeholder-slate-600 focus:outline-none focus:border-violet-600"
                            required></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-violet-600 to-blue-500 hover:from-violet-500 hover:to-blue-400 text-white font-semibold text-xs tracking-wide shadow-lg shadow-violet-500/10 hover:shadow-violet-500/20 transition-all">
                            {{ __('messages.post_comment') }}
                        </button>
                    </div>
                </form>

                <!-- Comments Tree Render -->
                <div id="comments-list" class="space-y-6 pt-4">
                    @forelse($modPack->comments as $comment)
                        <div class="space-y-4" id="comment-node-{{ $comment->id }}">
                            <!-- Main Parent Comment Card -->
                            <div class="p-4 rounded-xl bg-slate-900/40 border border-slate-850">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                        <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-xs font-bold text-violet-400">
                                            {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <div class="text-xs font-bold text-slate-200">{{ $comment->user->name }}</div>
                                                @if($comment->rating_stars)
                                                    <div class="flex items-center text-[10px] text-amber-500 gap-0.5">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="fa-{{ $i <= $comment->rating_stars ? 'solid' : 'regular' }} fa-star"></i>
                                                        @endfor
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="text-[10px] text-slate-500">{{ $comment->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Reply trigger -->
                                    <button onclick="setReplyTo({{ $comment->id }}, '{{ $comment->user->name }}')" class="text-xs text-violet-400 hover:text-violet-300 font-semibold flex items-center space-x-1 rtl:space-x-reverse">
                                        <i class="fa-solid fa-reply"></i>
                                        <span>{{ __('messages.reply') }}</span>
                                    </button>
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
                        <div class="text-center py-6 text-slate-600 text-xs" id="no-comments-placeholder">
                            <i class="fa-regular fa-comment-dots text-xl mb-2 text-slate-700 block"></i>
                            Be the first to say something!
                        </div>
                    @endforelse
                </div>

            </div>
        </div>

        <!-- Right Column: Sticky Sidebar metadata and actions -->
        <div class="lg:col-span-1 space-y-6 transition-all duration-500" id="right-column">
            
            <!-- Quick Actions Panel -->
            <div class="glass-card p-6 rounded-2xl border border-slate-800 space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Game Information</h3>
                
                <div class="space-y-3 text-xs">
                    <div class="flex justify-between py-2 border-b border-slate-850">
                        <span class="text-slate-500">{{ __('messages.game') }}</span>
                        <span class="text-slate-200 font-bold">{{ $modPack->gameVersion->game->name }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-850">
                        <span class="text-slate-500">Game Version</span>
                        <span class="text-slate-200 font-mono font-bold">{{ $modPack->gameVersion->version }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-850">
                        <span class="text-slate-500">Views</span>
                        <span class="text-slate-200 font-bold"><i class="fa-regular fa-eye text-violet-400 mr-1"></i>{{ $modPack->views_count }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-850">
                        <span class="text-slate-500">Mods Count</span>
                        <span class="text-slate-200 font-bold">{{ $modPack->mods->count() }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-850">
                        <span class="text-slate-500">{{ app()->getLocale() == 'ar' ? 'الحجم التقريبي' : 'Approx. Size' }}</span>
                        <span class="text-slate-200 font-bold font-mono">{{ $modPack->total_size_gb }}</span>
                    </div>
                </div>

                <!-- Mod Pack Download/Copy Actions -->
                <div class="space-y-2 pt-2">
                    <!-- Copy to Clipboard Button -->
                    <button onclick="copyToClipboard()" class="w-full flex items-center justify-center space-x-2 rtl:space-x-reverse px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 hover:border-violet-600/40 text-slate-300 hover:text-white font-semibold text-xs tracking-wide transition-all shadow-md">
                        <i class="fa-regular fa-copy text-violet-400"></i>
                        <span id="copy-text">{{ __('messages.copy_order') }}</span>
                    </button>

                    <!-- Export .txt File Button -->
                    <a href="{{ route('modpacks.export', $modPack->id) }}" onclick="return confirmExport(event, {{ count($conflictPairs) > 0 ? 'true' : 'false' }}, '{{ route('modpacks.export', $modPack->id) }}')" class="w-full flex items-center justify-center space-x-2 rtl:space-x-reverse px-4 py-3 rounded-xl bg-gradient-to-tr from-violet-600/80 to-blue-500/80 hover:from-violet-600 hover:to-blue-500 text-white font-semibold text-xs tracking-wide transition-all shadow-md">
                        <i class="fa-regular fa-file-lines"></i>
                        <span>{{ __('messages.export_txt') }}</span>
                    </a>

                    <!-- Export .json File Button -->
                    <a href="{{ route('modpacks.export-json', $modPack->id) }}" onclick="return confirmExport(event, {{ count($conflictPairs) > 0 ? 'true' : 'false' }}, '{{ route('modpacks.export-json', $modPack->id) }}')" class="w-full flex items-center justify-center space-x-2 rtl:space-x-reverse px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 hover:border-violet-600/40 text-slate-350 hover:text-white font-semibold text-xs tracking-wide transition-all shadow-md">
                        <i class="fa-solid fa-code"></i>
                        <span>{{ app()->getLocale() == 'ar' ? 'تصدير كملف JSON' : 'Export JSON file' }}</span>
                    </a>

                    <!-- Export Markdown File Button -->
                    <a href="{{ route('modpacks.export-markdown', $modPack->id) }}" onclick="return confirmExport(event, {{ count($conflictPairs) > 0 ? 'true' : 'false' }}, '{{ route('modpacks.export-markdown', $modPack->id) }}')" class="w-full flex items-center justify-center space-x-2 rtl:space-x-reverse px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 hover:border-violet-600/40 text-blue-400 hover:text-blue-300 font-semibold text-xs tracking-wide transition-all shadow-md">
                        <i class="fa-brands fa-markdown"></i>
                        <span>{{ app()->getLocale() == 'ar' ? 'تصدير كملف Markdown' : 'Export Markdown file' }}</span>
                    </a>

                    <!-- Export MO2 File Button -->
                    <a href="{{ route('modpacks.export-mo2', $modPack->id) }}" onclick="return confirmExport(event, {{ count($conflictPairs) > 0 ? 'true' : 'false' }}, '{{ route('modpacks.export-mo2', $modPack->id) }}')" class="w-full flex items-center justify-center space-x-2 rtl:space-x-reverse px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 hover:border-orange-500/40 text-orange-400 hover:text-orange-300 font-semibold text-xs tracking-wide transition-all shadow-md">
                        <i class="fa-solid fa-folder-tree"></i>
                        <span>{{ app()->getLocale() == 'ar' ? 'تصدير لـ Mod Organizer 2 (MO2)' : 'Export for MO2 (modlist.txt)' }}</span>
                    </a>
                </div>
            </div>

            <!-- Sticky Sidebar Ad Slot -->
            <div class="sticky top-24">
                <x-ad-slot type="sidebar" />
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function setRatingStars(val) {
        document.getElementById('selected-stars').value = val;
        const stars = document.querySelectorAll('#star-rating-selector .star-item');
        stars.forEach((star, idx) => {
            if (idx < val) {
                star.classList.remove('fa-regular');
                star.classList.add('fa-solid', 'text-amber-500');
            } else {
                star.classList.remove('fa-solid', 'text-amber-500');
                star.classList.add('fa-regular');
            }
        });
    }

    // Copy mods list names to clipboard
    function copyToClipboard() {
        const modNames = [
            @foreach($modPack->mods as $mod)
                "{{ $mod->name }}",
            @endforeach
        ];
        
        const clipboardText = modNames.join("\n");
        
        navigator.clipboard.writeText(clipboardText).then(() => {
            const btnText = document.getElementById('copy-text');
            const originalText = btnText.innerText;
            btnText.innerText = "{{ __('messages.copied') }}";
            btnText.parentElement.classList.add('border-emerald-500/50');
            setTimeout(() => {
                btnText.innerText = originalText;
                btnText.parentElement.classList.remove('border-emerald-500/50');
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy text: ', err);
        });
     }

     function confirmExport(event, hasConflicts, url) {
         if (hasConflicts) {
             event.preventDefault();
             const msg = "{{ app()->getLocale() == 'ar' ? '⚠️ تنبيه: تحتوي هذه التجميعة على تعارضات معروفة قد تسبب مشاكل باللعبة. هل ترغب بالتصدير على أي حال؟' : '⚠️ Warning: This modpack contains known conflicts that might cause game instability. Export anyway?' }}";
             if (confirm(msg)) {
                 window.location.href = url;
             }
             return false;
         }
         return true;
     }

    // Submit Upvote/Downvote dynamically using AJAX (Fetch API)
    function submitVote(isUpvote) {
        const url = "{{ route('modpacks.rate', $modPack->id) }}";
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ is_upvote: isUpvote })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update numbers in the view dynamically
                document.getElementById('upvotes-count').innerText = data.upvotes;
                document.getElementById('downvotes-count').innerText = data.downvotes;
                
                // Toggle active buttons border visual state
                const upvoteBtn = document.getElementById('upvote-btn');
                const downvoteBtn = document.getElementById('downvote-btn');

                if (isUpvote) {
                    upvoteBtn.classList.toggle('border-emerald-500');
                    downvoteBtn.classList.remove('border-red-500');
                } else {
                    downvoteBtn.classList.toggle('border-red-500');
                    upvoteBtn.classList.remove('border-emerald-500');
                }
            }
        })
        .catch(err => console.error('Rating submission failed:', err));
    }

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
        const url = "{{ route('comments.store', $modPack->id) }}";
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
                                            <div class="flex items-center gap-2">
                                                <div class="text-xs font-bold text-slate-200">${comment.user_name}</div>
                                                ${comment.rating_stars ? `
                                                    <div class="flex items-center text-[10px] text-amber-500 gap-0.5">
                                                        ${Array.from({length: 5}, (_, i) => `
                                                            <i class="fa-${i < comment.rating_stars ? 'solid' : 'regular'} fa-star"></i>
                                                        `).join('')}
                                                    </div>
                                                ` : ''}
                                            </div>
                                            <div class="text-[10px] text-slate-500">${comment.created_at}</div>
                                        </div>
                                    </div>
                                    <button onclick="setReplyTo(${comment.id}, '${comment.user_name}')" class="text-xs text-violet-400 hover:text-violet-300 font-semibold flex items-center space-x-1 rtl:space-x-reverse">
                                        <i class="fa-solid fa-reply"></i>
                                        <span>{{ __('messages.reply') }}</span>
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
                if (document.getElementById('selected-stars')) {
                    document.getElementById('selected-stars').value = '';
                    const stars = document.querySelectorAll('#star-rating-selector .star-item');
                    stars.forEach(s => {
                        s.classList.remove('fa-solid', 'text-amber-500');
                        s.classList.add('fa-regular');
                    });
                }
                cancelReply();
            }
        })
        .catch(err => console.error('Comment posting failed:', err));
    }

    // Toggle Save/Bookmark dynamically via AJAX
    function toggleSave() {
        const url = "{{ route('modpacks.save', $modPack->id) }}";
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const saveBtn = document.getElementById('save-btn');
                const icon = saveBtn.querySelector('i');
                
                if (data.saved) {
                    saveBtn.className = 'flex items-center justify-center w-11 h-11 rounded-xl bg-slate-900 border border-violet-500 text-violet-400 font-bold transition-all';
                    icon.className = 'fa-solid fa-bookmark text-sm';
                } else {
                    saveBtn.className = 'flex items-center justify-center w-11 h-11 rounded-xl bg-slate-900 border border-slate-850 text-slate-400 hover:text-white transition-all';
                    icon.className = 'fa-regular fa-bookmark text-sm';
                }
            }
        })
        })
        .catch(err => console.error('Save toggle failed:', err));
    }

    // Copy Share Link to Clipboard
    function copyShareLink(btn) {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-check text-emerald-400"></i>';
            btn.classList.add('border-emerald-500/50');
            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.classList.remove('border-emerald-500/50');
            }, 2000);
            if (typeof showToast === 'function') {
                showToast("{{ app()->getLocale() == 'ar' ? 'تم نسخ الرابط!' : 'Link copied to clipboard!' }}", 'success');
            }
        });
    }
</script>
@endsection
