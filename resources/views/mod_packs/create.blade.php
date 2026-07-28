@extends('layouts.app')

@section('title', 'Create Mod Pack - LoadOrderHub')

@section('content')
<div class="max-w-7xl mx-auto space-y-8" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <!-- Header -->
    <div class="border-b border-slate-800 pb-4 space-y-1">
        <h1 class="text-3xl font-black bg-gradient-to-r from-white via-slate-200 to-violet-400 bg-clip-text text-transparent flex items-center gap-3">
            <i class="fa-solid fa-cubes-stacked text-violet-500 animate-pulse"></i>
            <span>{{ app()->getLocale() == 'ar' ? 'إنشاء تجميعة مودات جديدة' : 'Create New Mod Pack' }}</span>
        </h1>
        <p class="text-xs text-slate-400 font-medium">
            {{ app()->getLocale() == 'ar' ? 'اختر اللعبة ثم انتقِ المودات من المكتبة لتركيب وضبط ترتيب التحميل (Load Order) الخاص بك.' : 'Select a game and handpick mods from the library to build and organize your load order.' }}
        </p>
    </div>

    <!-- Step 1: Select Game Dropdown -->
    <div class="glass-card rounded-2xl border border-slate-800 p-6 space-y-3">
        <div class="flex items-center gap-3 text-violet-400">
            <i class="fa-solid fa-gamepad text-lg"></i>
            <h3 class="text-sm font-bold text-white uppercase tracking-wider">{{ app()->getLocale() == 'ar' ? 'الخطوة الأولى: تحديد اللعبة' : 'Step 1: Select Targeted Game' }}</h3>
        </div>
        <div class="space-y-1">
            <label class="text-xs text-slate-400 font-bold block">{{ app()->getLocale() == 'ar' ? 'اختر اللعبة المستهدفة لبدء البناء' : 'Choose game to start building' }}</label>
            <select id="game-selector" onchange="initializeBuilder(this.value)" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-slate-200 focus:outline-none focus:border-violet-600" required>
                <option value="">{{ app()->getLocale() == 'ar' ? '-- اختر اللعبة --' : '-- Select Game --' }}</option>
                @foreach($games as $g)
                    <option value="{{ $g->id }}">{{ $g->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Step 2-4 Builder Panel (Hidden initially until game is chosen) -->
    <div id="pack-builder-section" class="hidden grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left Panel: Available Mods Grid (7 Columns) -->
        <div class="lg:col-span-7 space-y-6">
            <div class="glass-card rounded-2xl border border-slate-800 p-6 space-y-5">
                <div class="flex items-center justify-between border-b border-slate-850 pb-2">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-cube text-violet-500"></i>
                        {{ app()->getLocale() == 'ar' ? 'الخطوة الثانية: اختيار المودات من المكتبة' : 'Step 2: Add Mods from Library' }}
                    </h3>
                </div>

                <!-- Search & Category Filters -->
                <div class="space-y-4">
                    <div class="relative">
                        <span class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0 pr-3' : 'left-0 pl-3' }} flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input 
                            type="text" 
                            id="library-mod-search" 
                            oninput="filterLibraryMods()" 
                            placeholder="{{ app()->getLocale() == 'ar' ? 'ابحث باسم المود...' : 'Search mod names...' }}" 
                            class="w-full bg-slate-950 border border-slate-800 rounded-xl {{ app()->getLocale() == 'ar' ? 'pr-9 pl-4' : 'pl-9 pr-4' }} py-2.5 text-xs text-slate-200 focus:outline-none focus:border-violet-600">
                    </div>

                    <!-- Category Tab Filter -->
                    <div class="flex flex-wrap gap-1.5" id="category-tabs">
                        <button type="button" onclick="selectCategoryTab('all')" data-cat-id="all" class="cat-tab px-3 py-1.5 rounded-lg border border-violet-500 text-violet-400 font-bold bg-violet-500/10 text-[10px] transition-all">
                            {{ app()->getLocale() == 'ar' ? 'كل المودات' : 'All Mods' }}
                        </button>
                        @foreach($categories as $cat)
                            <button type="button" onclick="selectCategoryTab('{{ $cat->id }}')" data-cat-id="{{ $cat->id }}" class="cat-tab px-3 py-1.5 rounded-lg border border-slate-800 text-slate-400 hover:text-white text-[10px] transition-all">
                                {{ app()->getLocale() == 'ar' ? $cat->name_ar : $cat->name_en }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Grid of Available Mods -->
                <div id="available-mods-spinner" class="py-12 text-center hidden">
                    <i class="fa-solid fa-circle-notch fa-spin text-3xl text-violet-500"></i>
                    <p class="text-xs text-slate-500 mt-2">{{ app()->getLocale() == 'ar' ? 'جاري جلب المودات...' : 'Loading library...' }}</p>
                </div>
                
                <div id="available-mods-grid" class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[70vh] overflow-y-auto pr-2">
                    <!-- Loaded dynamically via AJAX -->
                </div>
            </div>
        </div>

        <!-- Right Panel: Pack Info & Selected Mod List (5 Columns) -->
        <div class="lg:col-span-5 space-y-6">
            <form action="{{ route('modpacks.store') }}" method="POST" id="modpack-creation-form" class="space-y-6">
                @csrf
                
                <!-- Main Metadata Details -->
                <div class="glass-card rounded-2xl border border-slate-800 p-6 space-y-4">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-slate-850 pb-2 flex items-center gap-2">
                        <i class="fa-solid fa-info-circle text-violet-500"></i>
                        {{ app()->getLocale() == 'ar' ? 'تفاصيل التجميعة' : 'Mod Pack Information' }}
                    </h3>

                    <!-- Hidden targeted game selector value -->
                    <input type="hidden" name="game_id" id="hidden-game-id">

                    <div class="space-y-3 text-xs">
                        <div class="space-y-1">
                            <label class="text-slate-400 font-bold block">{{ app()->getLocale() == 'ar' ? 'عنوان التجميعة (EN)' : 'Title (EN)' }}</label>
                            <input type="text" name="title_en" placeholder="e.g. Skyrim AE Ultimate Graphics 2026" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white placeholder-slate-700 focus:outline-none focus:border-violet-600" required>
                        </div>

                        <div class="space-y-1">
                            <label class="text-slate-400 font-bold block">{{ app()->getLocale() == 'ar' ? 'عنوان التجميعة (AR)' : 'Title (AR)' }}</label>
                            <input type="text" name="title_ar" placeholder="مثال: تجميعة سكايرم للرسوميات الفائقة" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white placeholder-slate-700 focus:outline-none focus:border-violet-600" required>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="text-slate-400 font-bold block">{{ app()->getLocale() == 'ar' ? 'نسخة اللعبة' : 'Game Version' }}</label>
                                <select id="version-selector" name="game_version_id" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-slate-200 focus:outline-none focus:border-violet-600" required>
                                    <!-- Loaded dynamically -->
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="text-slate-400 font-bold block">{{ app()->getLocale() == 'ar' ? 'تصنيف التجميعة' : 'Category' }}</label>
                                <select name="category_id" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-slate-200 focus:outline-none focus:border-violet-600" required>
                                    <option value="">{{ app()->getLocale() == 'ar' ? '-- اختر --' : '-- Select --' }}</option>
                                    @foreach($categories as $c)
                                        <option value="{{ $c->id }}">{{ app()->getLocale() == 'ar' ? $c->name_ar : $c->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-slate-400 font-bold block">{{ app()->getLocale() == 'ar' ? 'معرف فيديو يوتيوب (اختياري)' : 'YouTube Video ID (Optional)' }}</label>
                            <input type="text" name="youtube_video_id" placeholder="e.g. dQw4w9WgXcQ" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white placeholder-slate-700 focus:outline-none focus:border-violet-600">
                        </div>

                        <div class="space-y-1">
                            <label class="text-slate-400 font-bold block">{{ app()->getLocale() == 'ar' ? 'الوصف (EN)' : 'Description (EN)' }}</label>
                            <textarea name="description_en" rows="2" placeholder="Describe the mod list target goals..." class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white placeholder-slate-700 focus:outline-none focus:border-violet-600" required></textarea>
                        </div>

                        <div class="space-y-1">
                            <label class="text-slate-400 font-bold block">{{ app()->getLocale() == 'ar' ? 'الوصف (AR)' : 'Description (AR)' }}</label>
                            <textarea name="description_ar" rows="2" placeholder="اكتب وصف وميزات هذه التجميعة بالتفصيل..." class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white placeholder-slate-700 focus:outline-none focus:border-violet-600" required></textarea>
                        </div>

                        <div class="pt-2">
                            <label class="flex items-center space-x-3 rtl:space-x-reverse cursor-pointer group">
                                <div class="relative flex items-center justify-center">
                                    <input type="checkbox" name="is_private" value="1" class="peer sr-only">
                                    <div class="w-5 h-5 rounded border-2 border-slate-700 bg-slate-900 peer-checked:bg-violet-500 peer-checked:border-violet-500 transition-all flex items-center justify-center group-hover:border-violet-500">
                                        <i class="fa-solid fa-check text-white text-[10px] opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                    </div>
                                </div>
                                <span class="text-slate-300 font-bold text-sm select-none">{{ app()->getLocale() == 'ar' ? 'جعل التجميعة خاصة (لا تظهر للعامة)' : 'Make this ModPack Private' }}</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Live Compatibility Score Badge -->
                <div id="compat-score-container" class="glass-card rounded-2xl border border-slate-800 p-4 flex items-center justify-between bg-slate-950/20">
                    <span class="text-xs font-bold text-slate-350 flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved text-violet-500 text-sm"></i>
                        <span>{{ app()->getLocale() == 'ar' ? 'مؤشر التوافقية الإجمالي:' : 'Pack Compatibility Index:' }}</span>
                    </span>
                    <div class="flex items-center gap-2">
                        <span id="compat-score-label" class="text-xs text-slate-400 font-semibold font-mono">100% ({{ app()->getLocale() == 'ar' ? 'متوافق بالكامل' : 'Fully Compatible' }})</span>
                        <span id="compat-score-badge" class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    </div>
                </div>

                <!-- Selected List Container (Drag & Drop sorting) -->
                <div class="glass-card rounded-2xl border border-slate-800 p-6 space-y-4">
                    <div class="border-b border-slate-850 pb-2 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-list-ol text-violet-500"></i>
                            {{ app()->getLocale() == 'ar' ? 'المودات المختارة وترتيب التحميل' : 'Selected Mods & Load Order' }}
                        </h3>
                        <span id="selected-mods-counter" class="px-2 py-0.5 rounded-full bg-slate-900 border border-slate-800 text-[10px] text-slate-400 font-black">0</span>
                    </div>

                    <div id="selected-mods-empty-state" class="py-10 text-center text-slate-600 text-xs">
                        <i class="fa-regular fa-square-plus text-2xl mb-2 text-slate-700 block"></i>
                        {{ app()->getLocale() == 'ar' ? 'لم تقم باختيار أي مودات بعد. انقر على "إضافة +" من القائمة الجانبية.' : 'No mods added yet. Click "Add +" from the available list.' }}
                    </div>

                    <!-- Selected Mods list row cards (Sortable drag/drop handles) -->
                    <div id="selected-mods-list" class="space-y-2">
                        <!-- Loaded dynamically via JavaScript interactions -->
                    </div>

                    <!-- Hidden inputs submitted in post request -->
                    <div id="selected-mods-hidden-inputs"></div>
                </div>

                <!-- Form Submit Actions -->
                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('home') }}" class="px-6 py-2.5 bg-slate-900 border border-slate-800 hover:bg-slate-850 text-slate-400 hover:text-white text-xs font-bold rounded-xl transition-all">
                        {{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}
                    </a>
                    <button type="submit" id="submit-pack-btn" disabled class="px-8 py-2.5 bg-gradient-to-r from-violet-600 to-blue-500 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-violet-500/10 cursor-not-allowed opacity-50">
                        {{ app()->getLocale() == 'ar' ? 'حفظ التجميعة' : 'Publish Mod Pack' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    const gamesData = @json($games);
    let selectedMods = [];
    let libraryMods = [];
    let activeCategoryId = 'all';
    let registeredConflicts = [];

    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('selected-mods-list');
        new Sortable(container, {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function() {
                reindexSelectedMods();
            }
        });

        // Warn before saving with active conflicts
        document.getElementById('modpack-creation-form').addEventListener('submit', function(e) {
            if (registeredConflicts.length > 0) {
                e.preventDefault();
                const confirmMsg = "{{ app()->getLocale() == 'ar' ? '⚠️ تنبيه: تحتوي هذه التجميعة على تعارضات معروفة قد تؤدي لعدم استقرار اللعبة. هل تريد المتابعة وحفظ التجميعة على أي حال؟' : '⚠️ Warning: This modpack contains known conflicts that could cause instability. Do you want to continue anyway?' }}";
                if (confirm(confirmMsg)) {
                    this.submit();
                }
            }
        });
    });

    // Step 1: Select Game & load associated versions + mods list
    function initializeBuilder(gameId) {
        const builderSection = document.getElementById('pack-builder-section');
        const hiddenGameId = document.getElementById('hidden-game-id');
        const versionSelector = document.getElementById('version-selector');

        if (!gameId) {
            builderSection.classList.add('hidden');
            hiddenGameId.value = '';
            return;
        }

        hiddenGameId.value = gameId;
        builderSection.classList.remove('hidden');

        // Populate game versions
        versionSelector.innerHTML = '<option value="">{{ app()->getLocale() == 'ar' ? "-- اختر الإصدار --" : "-- Select Version --" }}</option>';
        const selectedGame = gamesData.find(g => g.id == gameId);
        if (selectedGame && selectedGame.versions) {
            selectedGame.versions.forEach(v => {
                versionSelector.insertAdjacentHTML('beforeend', `
                    <option value="${v.id}">Game version ${v.version}</option>
                `);
            });
        }

        // Reset selected state
        selectedMods = [];
        libraryMods = [];
        renderSelectedMods();
        fetchLibraryMods(gameId);
    }

    // Step 2: Fetch mods via AJAX
    function fetchLibraryMods(gameId) {
        const grid = document.getElementById('available-mods-grid');
        const spinner = document.getElementById('available-mods-spinner');

        grid.innerHTML = '';
        spinner.classList.remove('hidden');

        fetch(`/admin/mods/search-by-game?game_id=${gameId}&category_id=${activeCategoryId}`)
            .then(res => res.json())
            .then(data => {
                spinner.classList.add('hidden');
                libraryMods = data || [];
                filterLibraryMods();
            })
            .catch(err => {
                spinner.classList.add('hidden');
                console.error(err);
            });
    }

    function selectCategoryTab(catId) {
        activeCategoryId = catId;
        
        // Toggle tabs visual active state
        document.querySelectorAll('.cat-tab').forEach(tab => {
            if (tab.getAttribute('data-cat-id') == catId) {
                tab.className = "cat-tab px-3 py-1.5 rounded-lg border border-violet-500 text-violet-400 font-bold bg-violet-500/10 text-[10px] transition-all";
            } else {
                tab.className = "cat-tab px-3 py-1.5 rounded-lg border border-slate-800 text-slate-400 hover:text-white text-[10px] transition-all";
            }
        });

        const gameId = document.getElementById('game-selector').value;
        if (gameId) {
            fetchLibraryMods(gameId);
        }
    }

    function filterLibraryMods() {
        const searchInput = document.getElementById('library-mod-search').value.toLowerCase().trim();
        const grid = document.getElementById('available-mods-grid');
        grid.innerHTML = '';

        const filtered = libraryMods.filter(m => m.name.toLowerCase().includes(searchInput));

        if (filtered.length === 0) {
            grid.innerHTML = `<div class="col-span-full py-8 text-center text-slate-600 text-xs">لا توجد مودات مطابقة في هذه الفئة.</div>`;
            return;
        }

        filtered.forEach(mod => {
            const isAdded = selectedMods.some(sm => sm.id === mod.id);
            const btnText = isAdded ? '{{ app()->getLocale() == "ar" ? "إزالة −" : "Remove −" }}' : '{{ app()->getLocale() == "ar" ? "إضافة +" : "Add +" }}';
            const btnClass = isAdded 
                ? 'px-3 py-1.5 bg-red-600/15 border border-red-500/30 text-red-400 hover:bg-red-600/35 hover:text-white font-bold rounded-lg text-[10px] transition-colors shrink-0'
                : 'px-3 py-1.5 bg-violet-600/10 border border-violet-500/20 text-violet-400 hover:bg-violet-600 hover:text-white font-bold rounded-lg text-[10px] transition-colors shrink-0';
            const actionClick = isAdded ? `removeModFromSelected(${mod.id})` : `addModToSelected(${mod.id})`;
            const img = mod.image_url ? mod.image_url : 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiM0NzU1NjkiIHN0cm9rZS13aWR0aD0iMiI+PHJlY3Qgd2lkdGg9IjE4IiBoZWlnaHQ9IjE4IiB4PSIzIiB5PSIzIiByeD0iMiIvPjxwYXRoIGQ9Im0yMSAxNi00LTQiLz48Y2lyY2xlIGN4PSIxNSIgY3k9IjkiIHI9IjIiLz48L3N2Zz4=';
            const catName = mod.category ? (document.documentElement.lang == 'ar' ? mod.category.name_ar : mod.category.name_en) : '';

            grid.insertAdjacentHTML('beforeend', `
                <div class="glass-card p-3 rounded-xl border border-slate-850 flex items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-3 min-w-0">
                        <img src="${img}" class="w-10 h-10 object-cover rounded-lg bg-slate-900 border border-slate-800 shrink-0">
                        <div class="min-w-0">
                            <div class="font-bold text-white truncate text-xs" title="${mod.name}">${mod.name}</div>
                            <span class="text-[9px] px-1.5 py-0.5 rounded bg-slate-950 text-slate-500 mt-1 inline-block">${catName}</span>
                        </div>
                    </div>
                    <button type="button" onclick="${actionClick}" class="${btnClass}">${btnText}</button>
                </div>
            `);
        });
    }

    // Add mod to selection
    function addModToSelected(modId) {
        const mod = libraryMods.find(m => m.id === modId);
        if (!mod) return;

        if (!selectedMods.some(sm => sm.id === modId)) {
            selectedMods.push({
                id: mod.id,
                name: mod.name,
                image_url: mod.image_url,
                category_name: mod.category ? (document.documentElement.lang == 'ar' ? mod.category.name_ar : mod.category.name_en) : ''
            });
            renderSelectedMods();
            filterLibraryMods();
            verifyConflicts();
        }
    }

    // Remove mod from selection
    function removeModFromSelected(modId) {
        selectedMods = selectedMods.filter(sm => sm.id !== modId);
        renderSelectedMods();
        filterLibraryMods();
        verifyConflicts();
    }

    // Render list
    function renderSelectedMods() {
        const listContainer = document.getElementById('selected-mods-list');
        const emptyState = document.getElementById('selected-mods-empty-state');
        const counter = document.getElementById('selected-mods-counter');
        const submitBtn = document.getElementById('submit-pack-btn');

        listContainer.innerHTML = '';
        counter.innerText = selectedMods.length;

        if (selectedMods.length === 0) {
            emptyState.classList.remove('hidden');
            submitBtn.disabled = true;
            submitBtn.className = "px-8 py-2.5 bg-gradient-to-r from-violet-600 to-blue-500 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-violet-500/10 cursor-not-allowed opacity-50";
            updateCompatibilityScore(100);
            updateHiddenInputs();
            return;
        }

        emptyState.classList.add('hidden');
        submitBtn.disabled = false;
        submitBtn.className = "px-8 py-2.5 bg-gradient-to-r from-violet-600 to-blue-500 hover:from-violet-500 hover:to-blue-400 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-violet-500/10";

        selectedMods.forEach((mod, idx) => {
            const img = mod.image_url ? mod.image_url : 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiM0NzU1NjkiIHN0cm9rZS13aWR0aD0iMiI+PHJlY3Qgd2lkdGg9IjE4IiBoZWlnaHQ9IjE4IiB4PSIzIiB5PSIzIiByeD0iMiIvPjxwYXRoIGQ9Im0yMSAxNi00LTQiLz48Y2lyY2xlIGN4PSIxNSIgY3k9IjkiIHI9IjIiLz48L3N2Zz4=';
            listContainer.insertAdjacentHTML('beforeend', `
                <div class="glass-card p-3 rounded-xl border border-slate-800/80 flex flex-col gap-2 mod-selected-card cursor-grab active:cursor-grabbing transition-transform" data-mod-id="${mod.id}" id="selected-card-${mod.id}" draggable="true">
                    <div class="flex items-center justify-between gap-3 text-xs">
                        <div class="flex items-center gap-3 min-w-0">
                            <i class="fa-solid fa-grip-vertical text-slate-700 hover:text-slate-400 cursor-grab drag-handle"></i>
                            <span class="w-6 h-6 rounded-lg bg-slate-900 border border-slate-850 flex items-center justify-center font-bold text-[10px] text-violet-400 shrink-0 font-mono mod-index-label">${idx + 1}</span>
                            <img src="${img}" class="w-8 h-8 object-cover rounded bg-slate-900 border border-slate-800 shrink-0">
                            <div class="min-w-0">
                                <div class="font-bold text-white truncate" title="${mod.name}">${mod.name}</div>
                                <span class="text-[9px] text-slate-500 font-semibold">${mod.category_name}</span>
                            </div>
                        </div>
                        <button type="button" onclick="removeModFromSelected(${mod.id})" class="text-xs text-red-500 hover:text-red-400 px-2 py-1"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <div id="conflict-warning-${mod.id}" class="hidden"></div>
                </div>
            `);
        });

        updateHiddenInputs();
    }

    function reindexSelectedMods() {
        const listCards = document.querySelectorAll('.mod-selected-card');
        const newSelected = [];

        listCards.forEach((card, idx) => {
            const modId = parseInt(card.getAttribute('data-mod-id'));
            const mod = selectedMods.find(sm => sm.id === modId);
            if (mod) {
                newSelected.push(mod);
                card.querySelector('.mod-index-label').innerText = idx + 1;
            }
        });

        selectedMods = newSelected;
        updateHiddenInputs();
        verifyConflicts();
    }

    // Drag and Drop Logic
    let draggedItem = null;

    document.addEventListener('dragstart', function(e) {
        if (e.target.classList && e.target.classList.contains('mod-selected-card')) {
            draggedItem = e.target;
            e.target.style.opacity = '0.4';
            e.dataTransfer.effectAllowed = 'move';
        }
    });

    document.addEventListener('dragend', function(e) {
        if (e.target.classList && e.target.classList.contains('mod-selected-card')) {
            e.target.style.opacity = '1';
            draggedItem = null;
            reindexSelectedMods();
        }
    });

    document.addEventListener('dragover', function(e) {
        e.preventDefault();
        const targetCard = e.target.closest('.mod-selected-card');
        if (targetCard && targetCard !== draggedItem && draggedItem) {
            const list = document.getElementById('selected-mods-list');
            const targetRect = targetCard.getBoundingClientRect();
            const midpoint = targetRect.top + targetRect.height / 2;
            
            if (e.clientY < midpoint) {
                list.insertBefore(draggedItem, targetCard);
            } else {
                list.insertBefore(draggedItem, targetCard.nextSibling);
            }
        }
    });

    function updateHiddenInputs() {
        const wrapper = document.getElementById('selected-mods-hidden-inputs');
        wrapper.innerHTML = '';

        selectedMods.forEach((mod, idx) => {
            wrapper.insertAdjacentHTML('beforeend', `
                <input type="hidden" name="mods[${idx}][id]" value="${mod.id}">
                <input type="hidden" name="mods[${idx}][name]" value="${mod.name}">
                <input type="hidden" name="mods[${idx}][load_order]" value="${idx + 1}">
            `);
        });
    }

    // Verify conflicts via AJAX
    function verifyConflicts() {
        const modIds = selectedMods.map(sm => sm.id);

        // Reset visual alert states
        document.querySelectorAll('.mod-selected-card').forEach(card => {
            card.classList.remove('border-red-500/80', 'ring-1', 'ring-red-500/40');
        });
        document.querySelectorAll('[id^="conflict-warning-"]').forEach(warning => {
            warning.innerHTML = '';
            warning.classList.add('hidden');
        });

        if (modIds.length < 2) {
            updateCompatibilityScore(100);
            registeredConflicts = [];
            return;
        }

        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/admin/mods/check-conflicts', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ mod_ids: modIds })
        })
        .then(res => res.json())
        .then(data => {
            registeredConflicts = data.conflicts || [];
            updateCompatibilityScore(data.score ?? 100);

            if (registeredConflicts.length > 0) {
                registeredConflicts.forEach(c => {
                    highlightSelectedConflict(c.mod_id, c.conflicts_with_mod_id, c.reason_en, c.reason_ar);
                    highlightSelectedConflict(c.conflicts_with_mod_id, c.mod_id, c.reason_en, c.reason_ar);
                });
            }
        });
    }

    function highlightSelectedConflict(targetId, otherId, reasonEn, reasonAr) {
        const card = document.getElementById(`selected-card-${targetId}`);
        const warning = document.getElementById(`conflict-warning-${targetId}`);

        if (card && warning) {
            card.classList.add('border-red-500/80', 'ring-1', 'ring-red-500/40');
            const reason = "{{ app()->getLocale() }}" === 'ar' ? reasonAr : reasonEn;
            warning.innerHTML = `
                <div class="text-[10px] text-red-400 font-semibold p-2 bg-red-950/20 border border-red-500/20 rounded-lg flex items-center gap-1.5 mt-1.5">
                    <i class="fa-solid fa-circle-exclamation text-red-500 animate-pulse"></i>
                    <span>${reason}</span>
                </div>
            `;
            warning.classList.remove('hidden');
        }
    }

    function updateCompatibilityScore(score) {
        const label = document.getElementById('compat-score-label');
        const badge = document.getElementById('compat-score-badge');

        badge.className = 'w-2.5 h-2.5 rounded-full animate-pulse';

        let text = `${score}% `;
        if (score === 100) {
            text += "{{ app()->getLocale() == 'ar' ? '(متوافق بالكامل)' : '(Fully Compatible)' }}";
            badge.classList.add('bg-emerald-500');
        } else if (score >= 70) {
            text += "{{ app()->getLocale() == 'ar' ? '(توافقية ممتازة)' : '(High Compatibility)' }}";
            badge.classList.add('bg-green-500');
        } else if (score >= 40) {
            text += "{{ app()->getLocale() == 'ar' ? '(توافقية متوسطة - انتبه)' : '(Medium Compatibility - Caution)' }}";
            badge.classList.add('bg-amber-500');
        } else {
            text += "{{ app()->getLocale() == 'ar' ? '(غير مستقرة - تعارضات كثيرة)' : '(Unstable - High Conflicts)' }}";
            badge.classList.add('bg-red-500');
        }

        label.innerText = text;
    }
</script>
@endsection
