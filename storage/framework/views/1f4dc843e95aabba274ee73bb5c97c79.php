<?php $__env->startSection('title', __('messages.game_mods', ['game' => $game->name] ?? ['game' => $game->name])); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Breadcrumbs -->
    <nav class="flex text-xs text-slate-500 space-x-2 rtl:space-x-reverse">
        <a href="<?php echo e(route('home')); ?>" class="hover:text-slate-300"><?php echo e(__('messages.home')); ?></a>
        <span>/</span>
        <a href="<?php echo e(route('games.show', $game->slug)); ?>" class="hover:text-slate-300"><?php echo e($game->name); ?></a>
        <span>/</span>
        <span class="text-slate-300">Mods</span>
    </nav>

    <!-- Game Detail Header -->
    <div class="relative rounded-3xl overflow-hidden bg-slate-900 border border-slate-800 p-6 md:p-8 flex flex-col md:flex-row items-center md:items-start gap-6">
        <div class="w-24 h-24 md:w-36 md:h-36 rounded-2xl overflow-hidden bg-slate-950 flex-shrink-0">
            <img src="<?php echo e($game->thumbnail_url); ?>" alt="<?php echo e($game->name); ?>" class="w-full h-full object-cover">
        </div>
        <div class="space-y-3 text-center md:text-left rtl:md:text-right flex-grow">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h1 class="text-2xl md:text-3xl font-extrabold text-white"><?php echo e($game->name); ?> - Mods Library</h1>
                <a href="<?php echo e(route('games.show', $game->slug)); ?>" class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-slate-800 border border-slate-700 hover:border-violet-600/40 text-slate-300 hover:text-white font-semibold text-xs tracking-wide transition-all shadow-md">
                    <i class="fa-solid fa-arrow-left mr-2 rtl:ml-2 rtl:rotate-180"></i>
                    Back to Mod Packs
                </a>
            </div>
            <p class="text-sm text-slate-400 max-w-3xl leading-relaxed"><?php echo e($game->description); ?></p>
            <div class="inline-flex items-center space-x-2 rtl:space-x-reverse text-xs text-slate-500">
                <i class="fa-solid fa-circle-info text-violet-500"></i>
                <span>Explore all mods curated for this game across various versions and load orders.</span>
            </div>
        </div>
    </div>

    <!-- Filter Control & Listings Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Filter Control -->
        <div class="lg:col-span-1 space-y-6">
            <div class="glass-card p-6 rounded-2xl border border-slate-800 space-y-5">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">
                    <i class="fa-solid fa-filter text-violet-500 mr-1.5 rtl:ml-1.5"></i>
                    Filters
                </h3>
                
                <!-- Search Box -->
                <div class="space-y-1">
                    <label for="search-input" class="text-xs text-slate-500 font-semibold">Search Mod Name</label>
                    <div class="relative">
                        <input type="text" id="search-input" value="<?php echo e($search); ?>" placeholder="e.g. Harmony" class="w-full bg-slate-950 border border-slate-800 rounded-xl pl-9 pr-4 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-violet-600">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-search text-xs"></i>
                        </div>
                    </div>
                </div>

                <!-- Version Selection Dropdown -->
                <div class="space-y-1">
                    <label for="version-filter-select" class="text-xs text-slate-500 font-semibold">Filter by Game Version</label>
                    <div class="relative">
                        <select id="version-filter-select" class="w-full block bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-violet-600 appearance-none cursor-pointer">
                            <option value="">All Versions</option>
                            <?php $__currentLoopData = $versions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($v->id); ?>" <?php echo e($versionId == $v->id ? 'selected' : ''); ?>>
                                    Game <?php echo e($v->version); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <div class="absolute inset-y-0 right-0 rtl:left-0 rtl:right-auto flex items-center px-4 pointer-events-none text-slate-500">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mods Table Container -->
        <div class="lg:col-span-3 space-y-6">
            <div id="mods-list-container" class="transition-opacity duration-200">
                <?php echo $__env->make('mods.partials.mods_list_table', ['mods' => $mods, 'game' => $game], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search-input');
        const versionSelect = document.getElementById('version-filter-select');
        const container = document.getElementById('mods-list-container');

        function fetchFilteredMods() {
            const search = searchInput.value;
            const versionId = versionSelect.value;
            const url = new URL(window.location.href);
            
            if (search) url.searchParams.set('search', search);
            else url.searchParams.delete('search');
            
            if (versionId) url.searchParams.set('version_id', versionId);
            else url.searchParams.delete('version_id');

            // Reset page param when changing filters
            url.searchParams.delete('page');

            window.history.pushState({}, '', url);
            container.style.opacity = '0.4';

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
        }

        // Live search with simple debounce
        let timeout = null;
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                clearTimeout(timeout);
                timeout = setTimeout(fetchFilteredMods, 300);
            });
        }

        if (versionSelect) {
            versionSelect.addEventListener('change', fetchFilteredMods);
        }

        // Handle pagination link clicks asynchronously
        document.addEventListener('click', function(event) {
            const paginationLink = event.target.closest('#mods-list-container .pagination a');
            if (paginationLink) {
                event.preventDefault();
                const url = new URL(paginationLink.href);
                
                container.style.opacity = '0.4';
                
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    container.innerHTML = data.html;
                    container.style.opacity = '1';
                    // Scroll to container top smoothly
                    window.scrollTo({
                        top: container.getBoundingClientRect().top + window.scrollY - 100,
                        behavior: 'smooth'
                    });
                })
                .catch(error => {
                    console.error('Pagination click error:', error);
                    container.style.opacity = '1';
                });
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\lravle\taskmn\resources\views/mods/index.blade.php ENDPATH**/ ?>