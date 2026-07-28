@extends('layouts.app')

@section('title', __('messages.home') . ' - Compare Mods')

@section('content')
<div class="max-w-6xl mx-auto space-y-8" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <!-- Header -->
    <div class="border-b border-slate-800 pb-4 space-y-1">
        <h1 class="text-3xl font-black bg-gradient-to-r from-white via-slate-200 to-violet-400 bg-clip-text text-transparent flex items-center gap-3">
            <i class="fa-solid fa-code-compare text-violet-500 animate-pulse"></i>
            <span>{{ app()->getLocale() == 'ar' ? 'مقارنة المودات' : 'Mod Comparison Board' }}</span>
        </h1>
        <p class="text-xs text-slate-400 font-medium">
            {{ app()->getLocale() == 'ar' ? 'قارن بين 2 إلى 4 مودات من نفس اللعبة جنباً إلى جنب للتحقق من التوافق والتعارضات.' : 'Compare 2 to 4 mods of the same game side-by-side to verify compatibility and specifications.' }}
        </p>
    </div>

    <!-- Filters and Selection Board -->
    <div class="glass-card rounded-2xl border border-slate-800 p-6 space-y-5">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Game Dropdown -->
            <div class="space-y-1">
                <label class="text-xs text-slate-400 font-bold block">{{ app()->getLocale() == 'ar' ? 'اللعبة المستهدفة' : 'Targeted Game' }}</label>
                <select id="compare-game-select" onchange="onGameChange()" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-violet-600">
                    <option value="">{{ app()->getLocale() == 'ar' ? '-- اختر اللعبة للبدء --' : '-- Select Game to Start --' }}</option>
                    @foreach($games as $game)
                        <option value="{{ $game->id }}" {{ $selectedGameId == $game->id ? 'selected' : '' }}>{{ $game->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Mod Search Autocomplete -->
            <div class="space-y-1 md:col-span-2 relative">
                <label class="text-xs text-slate-400 font-bold block">{{ app()->getLocale() == 'ar' ? 'ابحث عن المودات لإضافتها للمقارنة (بحد أقصى 4)' : 'Search Mods to Compare (Max 4)' }}</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 rtl:left-auto rtl:right-0 pl-3 rtl:pr-3 flex items-center pointer-events-none text-slate-500">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input 
                        type="text" 
                        id="mod-search-input" 
                        oninput="searchMods()" 
                        placeholder="{{ app()->getLocale() == 'ar' ? 'ابحث عن مود بالاسم...' : 'Search mod by name...' }}" 
                        disabled
                        class="w-full bg-slate-950/60 border border-slate-800/80 rounded-xl pl-9 pr-4 rtl:pr-9 rtl:pl-4 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-violet-600 disabled:opacity-50 disabled:cursor-not-allowed">
                </div>

                <!-- Suggestions Dropdown -->
                <div id="suggestions-box" class="absolute w-full mt-2 bg-slate-950 border border-slate-800 rounded-xl shadow-xl z-50 max-h-48 overflow-y-auto hidden"></div>
            </div>
        </div>

        <!-- Selected Mods Badges Container -->
        <div class="space-y-2">
            <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ app()->getLocale() == 'ar' ? 'المودات المختارة حالياً للمقارنة:' : 'Selected Mods:' }}</h4>
            <div id="selected-mods-container" class="flex flex-wrap gap-2 min-h-8 items-center">
                <p class="text-xs text-slate-600 font-medium" id="no-mods-msg">
                    {{ app()->getLocale() == 'ar' ? 'لم تقم باختيار أي مود للمقارنة بعد.' : 'No mods selected yet.' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Spinner Loading State -->
    <div id="compare-spinner" class="hidden py-16 text-center text-xs text-slate-500 space-y-2">
        <i class="fa-solid fa-circle-notch fa-spin text-2xl text-violet-500"></i>
        <p class="animate-pulse">{{ app()->getLocale() == 'ar' ? 'جاري تحميل جدول المقارنة الفوري...' : 'Loading comparison table...' }}</p>
    </div>

    <!-- Comparison Table Grid -->
    <div id="comparison-table-container">
        @include('mods.partials.comparison_table', ['mods' => collect(), 'conflicts' => []])
    </div>
</div>
@endsection

@section('scripts')
<script>
    let selectedModIds = [];

    document.addEventListener('DOMContentLoaded', function() {
        const gameSelect = document.getElementById('compare-game-select');
        if (gameSelect.value) {
            onGameChange();
        }
    });

    function onGameChange() {
        const gameSelect = document.getElementById('compare-game-select');
        const searchInput = document.getElementById('mod-search-input');
        const suggestionsBox = document.getElementById('suggestions-box');
        const container = document.getElementById('selected-mods-container');

        // Clear previous selections
        selectedModIds = [];
        container.innerHTML = `<p class="text-xs text-slate-650 font-medium" id="no-mods-msg">{{ app()->getLocale() == 'ar' ? 'لم تقم باختيار أي مود للمقارنة بعد.' : 'No mods selected yet.' }}</p>`;
        suggestionsBox.classList.add('hidden');
        document.getElementById('comparison-table-container').innerHTML = `@include('mods.partials.comparison_table', ['mods' => collect(), 'conflicts' => []])`;

        if (gameSelect.value) {
            searchInput.removeAttribute('disabled');
            searchInput.focus();
        } else {
            searchInput.setAttribute('disabled', 'true');
        }
    }

    function searchMods() {
        const query = document.getElementById('mod-search-input').value.trim();
        const gameId = document.getElementById('compare-game-select').value;
        const suggestionsBox = document.getElementById('suggestions-box');

        if (query.length < 1) {
            suggestionsBox.classList.add('hidden');
            return;
        }

        fetch(`/admin/mods/search-by-game?game_id=${gameId}&q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                suggestionsBox.innerHTML = '';
                if (data.length === 0) {
                    suggestionsBox.insertAdjacentHTML('beforeend', `<div class="p-3 text-xs text-slate-650 text-center">{{ app()->getLocale() == 'ar' ? 'لا توجد نتائج مطابقة' : 'No matches found' }}</div>`);
                } else {
                    data.forEach(mod => {
                        if (selectedModIds.includes(mod.id)) return;
                        suggestionsBox.insertAdjacentHTML('beforeend', `
                            <div onclick="addModToCompare(${mod.id}, '${mod.name.replace(/'/g, "\\'")}')" class="p-3 text-xs text-slate-350 hover:bg-slate-900 cursor-pointer flex items-center gap-3 border-b border-slate-900/60 last:border-0">
                                <div class="w-6 h-6 bg-slate-900 border border-slate-800 rounded overflow-hidden shrink-0">
                                    <img src="${mod.image_url || 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiM0NzU1NjkiIHN0cm9rZS13aWR0aD0iMiI+PHJlY3Qgd2lkdGg9IjE4IiBoZWlnaHQ9IjE4IiB4PSIzIiB5PSIzIiByeD0iMiIvPjxwYXRoIGQ9Im0yMSAxNi00LTQiLz48Y2lyY2xlIGN4PSIxNSIgY3k9IjkiIHI9IjIiLz48L3N2Zz4='}" class="w-full h-full object-cover">
                                </div>
                                <span class="font-semibold text-white">${mod.name}</span>
                            </div>
                        `);
                    });
                }
                suggestionsBox.classList.remove('hidden');
            });
    }

    function addModToCompare(id, name) {
        document.getElementById('suggestions-box').classList.add('hidden');
        document.getElementById('mod-search-input').value = '';

        if (selectedModIds.length >= 4) {
            alert("{{ app()->getLocale() == 'ar' ? 'يمكنك مقارنة بحد أقصى 4 مودات فقط!' : 'You can compare a maximum of 4 mods!' }}");
            return;
        }

        if (selectedModIds.includes(id)) return;
        selectedModIds.push(id);

        const container = document.getElementById('selected-mods-container');
        const noMsg = document.getElementById('no-mods-msg');
        if (noMsg) noMsg.remove();

        const badgeHtml = `
            <span id="compare-badge-${id}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-violet-600/10 border border-violet-500/25 text-violet-400">
                <span>${name}</span>
                <button type="button" onclick="removeModFromCompare(${id})" class="text-violet-400 hover:text-white font-bold">&times;</button>
            </span>
        `;
        container.insertAdjacentHTML('beforeend', badgeHtml);
        loadComparisonTable();
    }

    function removeModFromCompare(id) {
        document.getElementById(`compare-badge-${id}`).remove();
        selectedModIds = selectedModIds.filter(modId => modId !== id);

        if (selectedModIds.length === 0) {
            const container = document.getElementById('selected-mods-container');
            container.innerHTML = `<p class="text-xs text-slate-650 font-medium" id="no-mods-msg">{{ app()->getLocale() == 'ar' ? 'لم تقم باختيار أي مود للمقارنة بعد.' : 'No mods selected yet.' }}</p>`;
        }
        loadComparisonTable();
    }

    function loadComparisonTable() {
        const gameId = document.getElementById('compare-game-select').value;
        const spinner = document.getElementById('compare-spinner');
        const container = document.getElementById('comparison-table-container');

        if (selectedModIds.length === 0) {
            container.innerHTML = `@include('mods.partials.comparison_table', ['mods' => collect(), 'conflicts' => []])`;
            return;
        }

        spinner.classList.remove('hidden');
        container.innerHTML = '';

        const params = new URLSearchParams();
        params.append('game_id', gameId);
        selectedModIds.forEach(id => params.append('mod_ids[]', id));

        fetch(`/mods/compare?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            spinner.classList.add('hidden');
            container.innerHTML = data.html;
        })
        .catch(err => {
            spinner.classList.add('hidden');
            container.innerHTML = `<div class="p-4 bg-red-950/40 border border-red-500/30 text-red-400 text-xs rounded-xl text-center">Error loading table: ${err.message}</div>`;
        });
    }

    // Hide suggestions list when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#mod-search-input')) {
            document.getElementById('suggestions-box').classList.add('hidden');
        }
    });

    // Before/After Slider logic
    function updateSlider(event, container) {
        const rect = container.getBoundingClientRect();
        let x = event.clientX || (event.touches && event.touches[0].clientX);
        if (x === undefined) return;
        x = x - rect.left;
        let percentage = (x / rect.width) * 100;
        if (percentage < 0) percentage = 0;
        if (percentage > 100) percentage = 100;
        
        const afterDiv = container.querySelector('.slider-after');
        if (afterDiv) {
            afterDiv.style.width = percentage + '%';
        }
    }
</script>
@endsection
