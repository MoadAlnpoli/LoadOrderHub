<?php $__empty_1 = true; $__currentLoopData = $modPacks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pack): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <?php
        // Warning flag if a pack has high negative ratings
        $isNegativeRating = $pack->downvotes > 5 && $pack->downvotes > $pack->upvotes;
        $title = app()->getLocale() == 'ar' ? $pack->title_ar : $pack->title_en;
        $description = app()->getLocale() == 'ar' ? $pack->description_ar : $pack->description_en;
    ?>

    <div class="glass-card rounded-2xl overflow-hidden border <?php echo e($isNegativeRating ? 'border-red-600/40 hover:border-red-500 shadow-lg shadow-red-500/5' : 'border-slate-800 hover:border-violet-600/40'); ?> transition-all duration-300 mb-6 flex flex-col md:flex-row hover-scale-img">
        
        <!-- Video Image Overlay/Thumbnail -->
        <div class="relative w-full md:w-64 h-40 md:h-auto overflow-hidden bg-slate-950 flex-shrink-0">
            <img src="<?php echo e($pack->local_thumbnail_path ? asset($pack->local_thumbnail_path) : ($pack->youtube_thumbnail_url ?? 'https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg')); ?>" 
                 alt="<?php echo e($title); ?>" 
                 class="w-full h-full object-cover">
            
            <!-- Video Play Overlay Badge -->
            <div class="absolute inset-0 bg-slate-950/30 flex items-center justify-center">
                <div class="w-12 h-12 rounded-full bg-red-600 hover:bg-red-500 text-white flex items-center justify-center shadow-lg transition-transform hover:scale-110">
                    <i class="fa-solid fa-play ml-0.5 rtl:mr-0.5"></i>
                </div>
            </div>
        </div>

        <!-- Info & Controls -->
        <div class="p-6 flex-grow flex flex-col justify-between space-y-4">
            
            <div class="space-y-2">
                <!-- Version & Publisher statistics -->
                <div class="flex items-center space-x-3 rtl:space-x-reverse text-xs text-slate-400">
                    <span class="px-2 py-0.5 rounded bg-slate-900 font-bold border border-slate-800 text-slate-300">
                        <?php echo e($pack->gameVersion->version); ?>

                    </span>
                    <span>&bull;</span>
                    <span><?php echo e($pack->mods_count); ?> Mods</span>
                    <span>&bull;</span>
                    <span><i class="fa-regular fa-eye mr-1 rtl:ml-1"></i> <?php echo e($pack->views_count); ?></span>
                </div>

                <!-- Title & Clickbait SEO -->
                <h3 class="text-lg font-bold text-white leading-snug">
                    <a href="<?php echo e(route('modpacks.show', $pack->id)); ?>" class="hover:text-violet-400 transition-colors">
                        <?php echo e($title); ?>

                    </a>
                </h3>

                <!-- Low rating warning -->
                <?php if($isNegativeRating): ?>
                    <div class="flex items-center space-x-2 rtl:space-x-reverse px-3 py-2 rounded-lg bg-red-500/10 border border-red-500/20 text-xs text-red-400 font-semibold">
                        <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                        <span><?php echo e(__('messages.warning_low_rating')); ?></span>
                    </div>
                <?php endif; ?>

                <p class="text-xs text-slate-400 line-clamp-2 leading-relaxed">
                    <?php echo e($description); ?>

                </p>
            </div>

            <!-- Footer details with ratings indicators and author -->
            <div class="flex items-center justify-between pt-4 border-t border-slate-800/60">
                <div class="text-[11px] text-slate-500">
                    <?php echo e(__('messages.by')); ?>: <span class="text-slate-400 font-semibold"><?php echo e($pack->creator->name ?? 'Auto Bot'); ?></span>
                </div>

                <!-- Ratings visual summary -->
                <div class="flex items-center space-x-3 rtl:space-x-reverse text-xs text-slate-400">
                    <span class="flex items-center text-emerald-400">
                        <i class="fa-regular fa-thumbs-up mr-1.5 rtl:ml-1.5"></i> <?php echo e($pack->upvotes); ?>

                    </span>
                    <span class="flex items-center text-red-400">
                        <i class="fa-regular fa-thumbs-down mr-1.5 rtl:ml-1.5"></i> <?php echo e($pack->downvotes); ?>

                    </span>
                </div>
            </div>

        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="py-12 text-center text-slate-500 glass-card rounded-2xl border border-slate-800 border-dashed">
        <i class="fa-solid fa-hourglass-empty text-3xl mb-3 text-slate-700"></i>
        <p><?php echo e(__('messages.no_packs')); ?></p>
    </div>
<?php endif; ?>
<?php /**PATH C:\lravle\taskmn\resources\views/games/partials/mod_packs_list.blade.php ENDPATH**/ ?>