<?php $__env->startSection('title', $game->name); ?>

<?php $__env->startSection('meta'); ?>
    <meta name="description" content="<?php echo e(Str::limit(strip_tags($game->description), 150)); ?>">
    <meta property="og:title" content="<?php echo e($game->name); ?> - LoadOrderHub">
    <meta property="og:description" content="<?php echo e(Str::limit(strip_tags($game->description), 150)); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e(route('games.show', $game->slug)); ?>">
    <?php if($game->thumbnail_url): ?>
        <meta property="og:image" content="<?php echo e($game->thumbnail_url); ?>">
    <?php endif; ?>
    <meta name="twitter:card" content="summary_large_image">

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "VideoGame",
      "name": "<?php echo e($game->name); ?>",
      "description": "<?php echo e(addslashes(strip_tags($game->description))); ?>",
      <?php if($game->thumbnail_url): ?>
      "image": "<?php echo e($game->thumbnail_url); ?>",
      <?php endif; ?>
      "url": "<?php echo e(route('games.show', $game->slug)); ?>"
    }
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Breadcrumbs -->
    <nav class="flex text-xs text-slate-500 space-x-2 rtl:space-x-reverse">
        <a href="<?php echo e(route('home')); ?>" class="hover:text-slate-300"><?php echo e(__('messages.home')); ?></a>
        <span>/</span>
        <span class="text-slate-300"><?php echo e($game->name); ?></span>
    </nav>

    <!-- Game Detail Header -->
    <div class="relative rounded-3xl overflow-hidden bg-slate-900 border border-slate-800 p-6 md:p-8 flex flex-col md:flex-row items-center md:items-start gap-6">
        <div class="w-full md:w-48 h-48 rounded-2xl overflow-hidden bg-slate-950 flex-shrink-0">
            <img src="<?php echo e($game->thumbnail_url); ?>" alt="<?php echo e($game->name); ?>" class="w-full h-full object-cover">
        </div>
        <div class="space-y-4 text-center md:text-left rtl:md:text-right flex-grow">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h1 class="text-2xl md:text-4xl font-extrabold text-white"><?php echo e($game->name); ?></h1>
                <a href="<?php echo e(route('games.mods', $game->slug)); ?>" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-gradient-to-tr from-violet-600 to-blue-500 hover:from-violet-500 hover:to-blue-400 text-white font-semibold text-xs tracking-wide transition-all shadow-md shadow-violet-500/10 hover:shadow-violet-500/20">
                    <i class="fa-solid fa-list mr-2 rtl:ml-2"></i>
                    View Mods Library
                </a>
            </div>
            <p class="text-sm text-slate-400 max-w-3xl leading-relaxed"><?php echo e($game->description); ?></p>
        </div>
    </div>

    <!-- Filter Control & Listings Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Filter Control -->
        <div class="lg:col-span-1 space-y-6">
            <div class="glass-card p-6 rounded-2xl border border-slate-800 space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">
                    <i class="fa-solid fa-filter text-violet-500 mr-1.5 rtl:ml-1.5"></i>
                    <?php echo e(__('messages.filter_version')); ?>

                </h3>
                
                <!-- Version Selection Dropdown -->
                <div class="relative">
                    <select id="version-filter-select" class="w-full block bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-violet-600 appearance-none cursor-pointer">
                        <?php $__currentLoopData = $versions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($v->id); ?>" <?php echo e($selectedVersionId == $v->id ? 'selected' : ''); ?>>
                                <?php echo e(__('messages.game')); ?> <?php echo e($v->version); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <!-- Custom Arrow Icon -->
                    <div class="absolute inset-y-0 right-0 rtl:left-0 rtl:right-auto flex items-center px-4 pointer-events-none text-slate-500">
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>
            
            <!-- Sticky Sidebar Ad Slot Placeholder -->
            <div class="glass-card p-4 rounded-2xl border border-slate-800 text-center space-y-3 sticky top-24">
                <div class="text-[10px] text-slate-600 font-bold uppercase tracking-widest"><?php echo e(__('messages.ad_space')); ?></div>
                <div class="h-64 rounded-xl border border-dashed border-slate-800 bg-slate-950/40 flex items-center justify-center text-xs text-slate-500 p-4">
                    <!-- Google AdSense Container -->
                    <div>
                        <i class="fa-solid fa-rectangle-ad text-3xl mb-2 text-slate-700"></i>
                        <p>AdSense Display Ad (Sidebar)<br><span class="text-[10px] text-slate-600">300x250 sticky banner</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mod Packs Container -->
        <div class="lg:col-span-3 space-y-6">
            <h2 class="text-xl font-bold tracking-wide text-white flex items-center space-x-2 rtl:space-x-reverse border-b border-slate-800 pb-3">
                <i class="fa-solid fa-cubes text-violet-500"></i>
                <span><?php echo e(__('messages.game_packs')); ?></span>
            </h2>

            <div id="mod-packs-container" class="transition-opacity duration-200">
                <?php echo $__env->make('games.partials.mod_packs_list', ['modPacks' => $modPacks], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\lravle\taskmn\resources\views/games/show.blade.php ENDPATH**/ ?>