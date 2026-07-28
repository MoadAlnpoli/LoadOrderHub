<?php $__env->startSection('title', __('messages.home')); ?>

<?php $__env->startSection('meta'); ?>
    <meta name="description" content="LoadOrderHub is the ultimate destination for curated PC game mod packs, optimized load orders, and game modifications. Enhance your gaming experience with stability.">
    <link rel="canonical" href="<?php echo e(url()->current()); ?>">
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "LoadOrderHub",
      "url": "<?php echo e(url('/')); ?>",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "<?php echo e(url('/mods-explorer')); ?>?search={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "LoadOrderHub",
      "url": "<?php echo e(url('/')); ?>",
      "logo": "<?php echo e(asset('logo.png')); ?>"
    }
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section with Premium Cinematic Background -->
<div class="relative overflow-hidden rounded-3xl bg-slate-950 border border-slate-800 p-10 md:p-20 mb-12 text-center shadow-2xl group">
    <!-- Cinematic Background Image (Fallback or dynamic) -->
    <div class="absolute inset-0 bg-cover bg-center opacity-30 mix-blend-overlay transition-transform duration-[10s] ease-in-out group-hover:scale-105" 
         style="background-image: url('https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=2070&auto=format&fit=crop');">
    </div>
    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/80 to-transparent"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-transparent to-slate-950"></div>

    <div class="relative space-y-8 z-10">
        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-white/5 border border-white/10 text-slate-300 backdrop-blur-md">
            <i class="fa-solid fa-crown text-amber-400 mr-2 rtl:ml-2"></i>
            Premium Modding Experience
        </span>
        
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-black tracking-tighter leading-tight drop-shadow-xl">
            <span class="text-white">LoadOrder</span><span class="text-violet-500">Hub</span>
        </h1>
        
        <p class="max-w-2xl mx-auto text-sm md:text-lg text-slate-300 leading-relaxed font-medium text-shadow-sm">
            Discover the ultimate mod packs and load orders carefully extracted and tested from top community creators. 
            Enjoy a crash-free, highly optimized gaming experience.
        </p>
        
        <div class="pt-6 flex flex-wrap items-center justify-center gap-6 text-sm font-bold text-slate-400">
            <span class="flex items-center"><i class="fa-solid fa-shield-check text-emerald-500 mr-2 rtl:ml-2"></i> Stable Builds</span>
            <span class="flex items-center"><i class="fa-solid fa-bolt text-amber-500 mr-2 rtl:ml-2"></i> Auto Extracted</span>
            <span class="flex items-center"><i class="fa-solid fa-layer-group text-blue-500 mr-2 rtl:ml-2"></i> Curated Packs</span>
        </div>
    </div>
</div>

<!-- Global Stats Bar -->
<?php if(isset($globalStats)): ?>
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
    <div class="glass-card rounded-2xl p-6 text-center border border-slate-800 border-b-4 border-b-violet-500 hover:-translate-y-1 transition-transform">
        <i class="fa-solid fa-cube text-violet-400 text-2xl mb-2"></i>
        <div class="text-2xl font-black text-white"><?php echo e(number_format($globalStats['mods'])); ?></div>
        <div class="text-xs text-slate-500 font-bold uppercase tracking-wider mt-1"><?php echo e(app()->getLocale() == 'ar' ? 'مود متاح' : 'Available Mods'); ?></div>
    </div>
    <div class="glass-card rounded-2xl p-6 text-center border border-slate-800 border-b-4 border-b-emerald-500 hover:-translate-y-1 transition-transform">
        <i class="fa-solid fa-boxes-stacked text-emerald-400 text-2xl mb-2"></i>
        <div class="text-2xl font-black text-white"><?php echo e(number_format($globalStats['modpacks'])); ?></div>
        <div class="text-xs text-slate-500 font-bold uppercase tracking-wider mt-1"><?php echo e(app()->getLocale() == 'ar' ? 'تجميعة منشورة' : 'Curated Packs'); ?></div>
    </div>
    <div class="glass-card rounded-2xl p-6 text-center border border-slate-800 border-b-4 border-b-blue-500 hover:-translate-y-1 transition-transform">
        <i class="fa-solid fa-download text-blue-400 text-2xl mb-2"></i>
        <div class="text-2xl font-black text-white"><?php echo e(number_format($globalStats['downloads'])); ?></div>
        <div class="text-xs text-slate-500 font-bold uppercase tracking-wider mt-1"><?php echo e(app()->getLocale() == 'ar' ? 'تحميل' : 'Total Downloads'); ?></div>
    </div>
    <div class="glass-card rounded-2xl p-6 text-center border border-slate-800 border-b-4 border-b-amber-500 hover:-translate-y-1 transition-transform">
        <i class="fa-solid fa-users text-amber-400 text-2xl mb-2"></i>
        <div class="text-2xl font-black text-white"><?php echo e(number_format($globalStats['users'])); ?></div>
        <div class="text-xs text-slate-500 font-bold uppercase tracking-wider mt-1"><?php echo e(app()->getLocale() == 'ar' ? 'عضو نشط' : 'Active Members'); ?></div>
    </div>
</div>
<?php endif; ?>

<!-- Trending Games Section -->
<?php if($trendingGames && $trendingGames->count() > 0): ?>
<div class="space-y-6 mb-8">
    <div class="border-b border-slate-800 pb-4 space-y-1">
        <h2 class="text-xl font-bold tracking-wide flex items-center space-x-2 rtl:space-x-reverse">
            <i class="fa-solid fa-fire-flame-curved text-amber-500"></i>
            <span><?php echo e(__('messages.trending')); ?></span>
        </h2>
        <p class="text-xs text-slate-400"><?php echo e(__('messages.trending_desc')); ?></p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php $__currentLoopData = $trendingGames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $game): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('games.show', $game->slug)); ?>" class="group block relative rounded-2xl overflow-hidden border border-violet-500/20 hover:border-violet-500/50 bg-gradient-to-br from-violet-950/20 to-slate-900/40 p-4 transition-all duration-300 transform hover:-translate-y-1 shadow-lg shadow-violet-500/5 hover-scale-img">
                <div class="relative h-40 rounded-xl overflow-hidden bg-slate-950 mb-4 skeleton">
                    <img src="<?php echo e($game->thumbnail_url); ?>" 
                         alt="<?php echo e($game->name); ?>" 
                         onload="this.parentElement.classList.remove('skeleton')"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent pointer-events-none"></div>
                    
                    <!-- Trending Badge -->
                    <span class="absolute top-3 left-3 rtl:left-auto rtl:right-3 bg-amber-500/25 border border-amber-500/30 text-amber-300 text-[10px] px-2.5 py-0.5 rounded-full font-bold flex items-center space-x-1 rtl:space-x-reverse">
                        <i class="fa-solid fa-fire text-amber-400 animate-pulse"></i>
                        <span>Trending</span>
                    </span>
                </div>

                <div class="space-y-1">
                    <h3 class="text-base font-bold text-white group-hover:text-violet-400 transition-colors">
                        <?php echo e($game->name); ?>

                    </h3>
                    <div class="flex items-center justify-between text-xs text-slate-400 pt-2">
                        <span><?php echo e($game->versions()->count()); ?> Versions</span>
                        <span class="font-semibold text-violet-400"><i class="fa-regular fa-eye mr-1 rtl:ml-1"></i> <?php echo e(number_format($game->total_views)); ?> Views</span>
                    </div>
                </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>

<!-- Latest Packs Section -->
<?php if(isset($latestPacks) && $latestPacks->count() > 0): ?>
<div class="space-y-6 mb-8 mt-12">
    <div class="border-b border-slate-800 pb-4 space-y-1">
        <h2 class="text-xl font-bold tracking-wide flex items-center space-x-2 rtl:space-x-reverse">
            <i class="fa-solid fa-clock text-emerald-500"></i>
            <span><?php echo e(app()->getLocale() == 'ar' ? 'أحدث التجميعات المضافة' : 'Newly Added Packs'); ?></span>
        </h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php $__currentLoopData = $latestPacks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pack): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('modpacks.show', $pack->id)); ?>" class="group block relative rounded-2xl overflow-hidden border border-slate-800 hover:border-emerald-500/50 bg-slate-900/40 p-4 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-500/10">
                <div class="space-y-2">
                    <h3 class="font-bold text-white group-hover:text-emerald-400 transition-colors line-clamp-1">
                        <?php echo e(app()->getLocale() == 'ar' ? $pack->title_ar : $pack->title_en); ?>

                    </h3>
                    <div class="text-xs text-slate-400 line-clamp-2">
                        <?php echo e(strip_tags(app()->getLocale() == 'ar' ? $pack->description_ar : $pack->description_en)); ?>

                    </div>
                    <div class="flex items-center justify-between text-[10px] text-slate-500 pt-2 border-t border-slate-800/50 mt-2">
                        <span class="flex items-center gap-1">
                            <i class="fa-solid fa-gamepad text-slate-400"></i>
                            <?php echo e($pack->gameVersions->first()->game->name ?? 'Unknown'); ?>

                        </span>
                        <span><?php echo e($pack->created_at->diffForHumans()); ?></span>
                    </div>
                </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>

<!-- Sponsored AdSense Banner Ad (Leaderboard) -->
<div class="w-full text-center space-y-2 py-4 mb-8">
    <?php $headerAd = isset($globalAds) ? $globalAds->where('name', 'header')->first() : null; ?>
    <?php if($headerAd): ?>
        <div class="mx-auto w-full flex items-center justify-center">
            <?php echo $headerAd->code; ?>

        </div>
    <?php else: ?>
        <span class="text-[9px] text-slate-600 font-bold uppercase tracking-widest"><?php echo e(__('messages.ad_space')); ?></span>
        <div class="mx-auto max-w-4xl h-24 rounded-2xl border border-dashed border-slate-800 bg-slate-950/30 flex items-center justify-center text-xs text-slate-500">
            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                <i class="fa-solid fa-rectangle-ad text-3xl text-slate-700"></i>
                <span class="text-slate-400 font-medium">Google AdSense Leaderboard Banner<br><span class="text-[10px] text-slate-600">728x90 responsive advertisement banner</span></span>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- All Games Grid Section -->
<div class="space-y-6">
    <div class="border-b border-slate-800 pb-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1">
            <h2 class="text-xl font-bold tracking-wide flex items-center space-x-2 rtl:space-x-reverse">
                <i class="fa-solid fa-gamepad text-violet-500"></i>
                <span><?php echo e(__('messages.all_games')); ?></span>
            </h2>
            <p class="text-xs text-slate-400"><?php echo e(__('messages.all_games_desc')); ?></p>
        </div>

        <!-- Frontend Search & Category Filter Bar -->
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto items-center">
            <div class="relative w-full sm:w-64">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input 
                    type="text" 
                    id="search-games-input" 
                    oninput="filterGames()" 
                    placeholder="<?php echo e(__('messages.search_placeholder')); ?>" 
                    class="w-full bg-slate-950/60 border border-slate-800/80 rounded-xl pl-9 pr-4 py-2 text-xs text-slate-200 focus:outline-none focus:border-violet-600">
            </div>

            <div class="flex items-center space-x-2 space-x-reverse w-full sm:w-auto">
                <span class="text-xs text-slate-500 font-bold whitespace-nowrap"><?php echo e(__('messages.category')); ?>:</span>
                <select id="filter-category-select" onchange="filterGames()" class="w-full sm:w-36 bg-slate-950/60 border border-slate-800/80 rounded-xl px-2.5 py-2 text-xs text-slate-200 focus:outline-none focus:border-violet-600">
                    <option value="all"><?php echo e(__('messages.all_categories')); ?></option>
                    <option value="rpg">RPG</option>
                    <option value="strategy">Strategy</option>
                    <option value="survival">Survival</option>
                    <option value="sandbox">Sandbox</option>
                </select>
            </div>
        </div>
    </div>

    <div class="mb-4 hidden" id="pinned-games-section">
        <h3 class="text-sm font-bold text-slate-300 mb-3 flex items-center gap-2">
            <i class="fa-solid fa-thumbtack text-violet-400"></i>
            <?php echo e(app()->getLocale() == 'ar' ? 'الألعاب المثبتة' : 'Pinned Games'); ?>

        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="pinned-games-grid"></div>
        <div class="w-full h-px bg-slate-800/60 my-6"></div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="games-grid-container">
        <!-- Live fallback message for no results -->
        <div class="col-span-full py-16 text-center text-slate-500 hidden" id="no-games-fallback">
            <i class="fa-solid fa-folder-open text-4xl mb-4 text-slate-600"></i>
            <p>Sorry, no games match your search criteria.</p>
        </div>

        <?php $__empty_1 = true; $__currentLoopData = $games; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $game): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <a href="<?php echo e(route('games.show', $game->slug)); ?>" 
               class="game-card-item group block glass-card rounded-2xl overflow-hidden border border-slate-800/80 hover:border-violet-600/40 transition-all duration-300 transform hover:-translate-y-1 hover-scale-img"
               data-name="<?php echo e(strtolower($game->name)); ?>"
               data-category="<?php echo e(str_contains(strtolower($game->slug), 'skyrim') || str_contains(strtolower($game->slug), 'cyberpunk') || str_contains(strtolower($game->slug), 'witcher') || str_contains(strtolower($game->slug), 'fallout') ? 'rpg' : (str_contains(strtolower($game->slug), 'hearts-of-iron') || str_contains(strtolower($game->slug), 'bannerlord') ? 'strategy' : (str_contains(strtolower($game->slug), 'minecraft') ? 'sandbox' : 'survival'))); ?>">
                <!-- Thumbnail -->
                <div class="relative h-48 overflow-hidden bg-slate-950">
                    <img src="<?php echo e($game->thumbnail_url); ?>" 
                         alt="<?php echo e($game->name); ?>" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#080c14] via-transparent to-transparent"></div>
                    
                    <!-- Version Badge -->
                    <span class="absolute top-4 right-4 bg-slate-950/80 border border-slate-800 text-slate-300 text-xs px-2.5 py-1 rounded-md font-semibold">
                        <?php echo e($game->versions_count); ?> <?php echo e(trans_choice('Version|Versions', $game->versions_count)); ?>

                    </span>

                    <!-- Pin Button -->
                    <button type="button" onclick="togglePinGame(event, <?php echo e($game->id); ?>)" class="absolute top-4 left-4 z-10 w-8 h-8 rounded bg-slate-900/80 hover:bg-violet-600 border border-slate-800 text-slate-400 hover:text-white flex items-center justify-center transition-colors pin-btn" id="pin-btn-<?php echo e($game->id); ?>">
                        <i class="fa-solid fa-thumbtack"></i>
                    </button>
                </div>

                <!-- Info -->
                <div class="p-6">
                    <h3 class="text-lg font-bold text-white group-hover:text-violet-400 transition-colors mb-2">
                        <?php echo e($game->name); ?>

                    </h3>
                    <p class="text-xs text-slate-400 line-clamp-2 leading-relaxed">
                        <?php echo e($game->description); ?>

                    </p>
                    
                    <!-- Bottom Action Link -->
                    <div class="flex items-center justify-end mt-4 pt-4 border-t border-slate-800/60 text-xs text-violet-400 font-semibold group-hover:underline">
                        <span>Explore Packs</span>
                        <i class="fa-solid fa-arrow-right ml-1 rtl:mr-1 rtl:rotate-180"></i>
                    </div>
                </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full py-16 text-center text-slate-500">
                <i class="fa-solid fa-folder-open text-4xl mb-4"></i>
                <p>No games seeded yet. Please seed database or run scraping command.</p>
            </div>
        <?php endif; ?>

        <!-- In-Grid Native Ad Cards Placement -->
        <div class="game-ad-card glass-card rounded-2xl border border-dashed border-slate-800 p-6 flex flex-col justify-between items-center text-center space-y-4 min-h-[300px] animate-float">
            <span class="text-[9px] text-slate-600 font-bold uppercase tracking-widest"><?php echo e(__('messages.ad_space')); ?></span>
            <div class="space-y-2">
                <i class="fa-solid fa-rectangle-ad text-4xl text-slate-700"></i>
                <h4 class="text-sm font-bold text-slate-300">Sponsored Ad Space</h4>
                <p class="text-xs text-slate-500 leading-relaxed">Native Ad Container matching card dimensions for maximum organic CTR.</p>
            </div>
            <div class="w-full h-10 rounded-lg bg-slate-950/60 flex items-center justify-center text-[10px] text-slate-600 font-mono">
                Responsive In-Feed Ad
            </div>
        </div>
    </div>

    <!-- Newsletter Subscription Section -->
    <div class="mt-16 w-full rounded-3xl bg-gradient-to-tr from-slate-900 to-slate-950 border border-slate-800 p-8 md:p-12 relative overflow-hidden animate-float-slow">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-violet-600/10 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-64 h-64 rounded-full bg-blue-600/10 blur-3xl"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="text-center md:text-left rtl:md:text-right max-w-lg">
                <h3 class="text-2xl md:text-3xl font-extrabold text-white mb-3">
                    <?php echo e(app()->getLocale() == 'ar' ? 'لا تفوت أفضل المودات!' : 'Never Miss Top Mods!'); ?>

                </h3>
                <p class="text-sm text-slate-400 leading-relaxed">
                    <?php echo e(app()->getLocale() == 'ar' ? 'اشترك في نشرتنا البريدية المجانية لنرسل لك أفضل تجميعات المودات الأسبوعية وأهم التحديثات مباشرة إلى بريدك الإلكتروني.' : 'Subscribe to our free weekly newsletter to get the best mod packs and major updates delivered straight to your inbox.'); ?>

                </p>
            </div>
            
            <div class="w-full md:w-auto flex-grow max-w-md">
                <form action="<?php echo e(route('newsletter.subscribe')); ?>" method="POST" class="flex flex-col sm:flex-row gap-3">
                    <?php echo csrf_field(); ?>
                    <input type="email" name="email" required placeholder="<?php echo e(app()->getLocale() == 'ar' ? 'أدخل بريدك الإلكتروني...' : 'Enter your email address...'); ?>" class="w-full flex-grow bg-slate-950/80 border border-slate-800 rounded-xl px-5 py-3 text-sm text-white focus:outline-none focus:border-violet-600 placeholder-slate-600 transition-colors">
                    <button type="submit" class="whitespace-nowrap px-6 py-3 rounded-xl bg-violet-600 hover:bg-violet-500 text-white font-bold text-sm shadow-md transition-all shadow-violet-500/20">
                        <i class="fa-solid fa-paper-plane mr-2 rtl:ml-2"></i>
                        <?php echo e(app()->getLocale() == 'ar' ? 'اشترك الآن' : 'Subscribe'); ?>

                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
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
        
        // Hide ad card if search results are empty or filtering
        const adCard = document.querySelector('.game-ad-card');
        if (adCard) {
            adCard.style.display = (query !== '' || category !== 'all') ? 'none' : 'flex';
        }
    }

    // Pinning Logic
    document.addEventListener('DOMContentLoaded', () => {
        loadPinnedGames();
    });

    function togglePinGame(event, gameId) {
        event.preventDefault(); // Prevent navigating to the game page
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
        
        // Reset state: move everything back to main grid
        const allCards = document.querySelectorAll('.game-card-item');
        allCards.forEach(card => {
            const btn = card.querySelector('.pin-btn');
            if (btn) {
                btn.classList.remove('bg-violet-600', 'text-white', 'border-violet-500');
                btn.classList.add('bg-slate-900/80', 'text-slate-400', 'border-slate-800');
            }
            if (card.parentElement.id === 'pinned-games-grid') {
                mainGrid.appendChild(card);
            }
        });

        if (pinned.length > 0) {
            pinnedSection.classList.remove('hidden');
            allCards.forEach(card => {
                const pinBtn = card.querySelector('.pin-btn');
                const gameId = parseInt(pinBtn.id.split('-').pop());
                
                if (pinned.includes(gameId)) {
                    pinBtn.classList.remove('bg-slate-900/80', 'text-slate-400', 'border-slate-800');
                    pinBtn.classList.add('bg-violet-600', 'text-white', 'border-violet-500');
                    pinnedGrid.appendChild(card);
                }
            });
        } else {
            pinnedSection.classList.add('hidden');
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\lravle\taskmn\resources\views/games/index.blade.php ENDPATH**/ ?>