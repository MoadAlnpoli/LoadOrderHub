@extends('layouts.app')

@section('title', 'Manual Mod Entry - LoadOrderHub')

@section('content')
<div class="max-w-4xl mx-auto space-y-8" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <!-- Header -->
    <div class="border-b border-slate-800 pb-4 space-y-1 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-black bg-gradient-to-r from-white via-slate-200 to-violet-400 bg-clip-text text-transparent flex items-center gap-3">
                <i class="fa-solid fa-plus-circle text-violet-500 animate-pulse"></i>
                <span>{{ app()->getLocale() == 'ar' ? 'إضافة مود يدوياً' : 'Add Mod Manually' }}</span>
            </h1>
            <p class="text-xs text-slate-400 font-medium">
                {{ app()->getLocale() == 'ar' ? 'أضف موداً جديداً يدوياً إلى مكتبة المودات وقم بتوصيف تعارضاته.' : 'Manually add a new mod configuration to the library and define its conflicts.' }}
            </p>
        </div>
        <a href="{{ route('admin.dashboard', ['mods_page' => 1]) }}" class="px-4 py-2 bg-slate-900 border border-slate-800 hover:bg-slate-850 text-slate-400 hover:text-white text-xs font-bold rounded-xl transition-all">
            {{ app()->getLocale() == 'ar' ? 'رجوع للوحة التحكم' : 'Back to Dashboard' }}
        </a>
    </div>

    <!-- Main Creation Form -->
    <form action="{{ route('admin.mods.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        @if($errors->any())
            <div class="p-4 bg-red-950/40 border border-red-500/30 rounded-2xl text-xs text-red-400 space-y-1">
                <p class="font-bold">{{ app()->getLocale() == 'ar' ? 'يرجى تصحيح الأخطاء التالية:' : 'Please correct the following errors:' }}</p>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- General Info Card -->
        <div class="glass-card rounded-2xl border border-slate-800 p-6 space-y-6">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-slate-850 pb-2 flex items-center gap-2">
                <i class="fa-solid fa-info-circle text-violet-500"></i>
                {{ app()->getLocale() == 'ar' ? 'المعلومات العامة' : 'General Information' }}
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name EN -->
                <div class="space-y-1">
                    <label class="text-xs text-slate-400 font-bold block">{{ app()->getLocale() == 'ar' ? 'اسم المود (إنجليزي)' : 'Mod Name (English)' }}</label>
                    <input type="text" id="name_en" name="name_en" placeholder="e.g. Address Library for SKSE Plugins" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-700 focus:outline-none focus:border-violet-600" required>
                </div>

                <!-- Name AR -->
                <div class="space-y-1">
                    <label class="text-xs text-slate-400 font-bold block">{{ app()->getLocale() == 'ar' ? 'اسم المود (عربي)' : 'Mod Name (Arabic)' }}</label>
                    <input type="text" id="name_ar" name="name_ar" placeholder="مثال: مكتبة العناوين لإضافات SKSE" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-700 focus:outline-none focus:border-violet-600" required>
                </div>

                <!-- Game Selector -->
                <div class="space-y-1">
                    <label class="text-xs text-slate-400 font-bold block">{{ app()->getLocale() == 'ar' ? 'اللعبة المستهدفة' : 'Targeted Game' }}</label>
                    <select name="game_id" id="game_id" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-violet-600" required>
                        <option value="">{{ app()->getLocale() == 'ar' ? '-- اختر اللعبة --' : '-- Select Game --' }}</option>
                        @foreach($games as $g)
                            <option value="{{ $g->id }}">{{ $g->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Category Selector -->
                <div class="space-y-1">
                    <label class="text-xs text-slate-400 font-bold block">{{ app()->getLocale() == 'ar' ? 'التصنيف الرئيسي' : 'Main Category' }}</label>
                    <select name="category_id" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-violet-600" required>
                        <option value="">{{ app()->getLocale() == 'ar' ? '-- اختر التصنيف --' : '-- Select Category --' }}</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}">{{ app()->getLocale() == 'ar' ? $c->name_ar : $c->name_en }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Description EN -->
                <div class="space-y-1 md:col-span-2">
                    <label class="text-xs text-slate-400 font-bold block">{{ app()->getLocale() == 'ar' ? 'الوصف (إنجليزي)' : 'Description (English)' }}</label>
                    <textarea id="description_en" name="description_en" rows="3" placeholder="Describe the mod features and installation rules..." class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-750 focus:outline-none focus:border-violet-600"></textarea>
                </div>

                <!-- Description AR -->
                <div class="space-y-1 md:col-span-2">
                    <label class="text-xs text-slate-400 font-bold block">{{ app()->getLocale() == 'ar' ? 'الوصف (عربي)' : 'Description (Arabic)' }}</label>
                    <textarea id="description_ar" name="description_ar" rows="3" placeholder="اشرح مميزات المود وطريقة التثبيت..." class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-750 focus:outline-none focus:border-violet-600"></textarea>
                </div>
            </div>
        </div>

        <!-- Links and Cover Image Card -->
        <div class="glass-card rounded-2xl border border-slate-800 p-6 space-y-6">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-slate-850 pb-2 flex items-center gap-2">
                <i class="fa-solid fa-link text-violet-500"></i>
                {{ app()->getLocale() == 'ar' ? 'روابط التحميل والوسائط' : 'Download Links & Media' }}
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Steam workshop URL -->
                <div class="space-y-1">
                    <label class="text-xs text-slate-400 font-bold block">Steam Workshop URL (Optional)</label>
                    <input type="url" name="steam_url" placeholder="https://steamcommunity.com/sharedfiles/filedetails/?id=..." class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-700 focus:outline-none focus:border-violet-600">
                </div>

                <!-- Nexus Mods URL -->
                <div class="space-y-1">
                    <label class="text-xs text-slate-400 font-bold block">Nexus Mods URL (Optional)</label>
                    <input type="url" name="nexus_url" placeholder="https://www.nexusmods.com/skyrimspecialedition/mods/..." class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-700 focus:outline-none focus:border-violet-600">
                </div>

                <!-- Cover Image URL -->
                <div class="space-y-1">
                    <label class="text-xs text-slate-400 font-bold block">{{ app()->getLocale() == 'ar' ? 'رابط صورة الغلاف' : 'Cover Image URL' }}</label>
                    <input type="url" name="image_url" placeholder="https://.../image.jpg" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-700 focus:outline-none focus:border-violet-600">
                </div>

                <!-- Cover Image Upload -->
                <div class="space-y-1">
                    <label class="text-xs text-slate-400 font-bold block">{{ app()->getLocale() == 'ar' ? 'أو رفع صورة جديدة' : 'Or Upload Cover Image' }}</label>
                    <input type="file" name="image_file" accept="image/*" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2 text-xs text-slate-400 focus:outline-none focus:border-violet-600 file:bg-violet-600/20 file:border-0 file:text-violet-400 file:text-xs file:px-3 file:py-1 file:rounded-lg file:mr-3 rtl:file:ml-3 file:font-bold file:cursor-pointer hover:file:bg-violet-600/30">
                </div>
            </div>
        </div>

        <!-- Incompatibilities / Conflicts Section -->
        <div class="glass-card rounded-2xl border border-slate-800 p-6 space-y-6">
            <div class="flex items-center justify-between border-b border-slate-850 pb-2">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-amber-500"></i>
                    {{ app()->getLocale() == 'ar' ? 'تعارضات المودات' : 'Mod Conflicts' }}
                </h3>
                <button type="button" id="btn-ai-suggest" onclick="suggestAIBasedConflicts()" class="px-3 py-1.5 bg-amber-500/10 border border-amber-500/30 hover:bg-amber-500/20 text-amber-400 text-[10px] font-bold rounded-lg transition-all flex items-center gap-1 shadow shadow-amber-500/5">
                    <i class="fa-solid fa-robot"></i>
                    <span>{{ app()->getLocale() == 'ar' ? 'اقتراح التعارضات بالذكاء الاصطناعي' : 'Suggest Conflicts via AI' }}</span>
                </button>
            </div>

            <p class="text-[10px] text-slate-500 leading-relaxed">
                {{ app()->getLocale() == 'ar' ? 'ابحث عن المودات الأخرى التي تتعارض مع هذا المود، وحدد سبب التعارض باللغتين العربية والانجليزية.' : 'Search for other mods that conflict with this mod, and specify the incompatibility reasons in both English and Arabic.' }}
            </p>

            <div class="space-y-4">
                <!-- Autocomplete input -->
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 rtl:left-auto rtl:right-0 pl-3 rtl:pr-3 flex items-center pointer-events-none text-slate-500">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input 
                        type="text" 
                        id="conflict-search-input" 
                        oninput="searchExistingMods()" 
                        placeholder="{{ app()->getLocale() == 'ar' ? 'ابحث عن مود لتعريفه كمتعارض...' : 'Search mod to register conflict...' }}" 
                        class="w-full bg-slate-950/60 border border-slate-800/80 rounded-xl pl-9 pr-4 rtl:pr-9 rtl:pl-4 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-violet-600">
                    
                    <!-- Suggestions list -->
                    <div id="conflict-suggestions" class="absolute w-full mt-2 bg-slate-950 border border-slate-800 rounded-xl shadow-xl z-50 max-h-48 overflow-y-auto hidden"></div>
                </div>

                <!-- Conflicts List Container -->
                <div id="conflicts-container" class="space-y-3">
                    <!-- Added conflicts will show here dynamically -->
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.dashboard', ['mods_page' => 1]) }}" class="px-6 py-3 bg-slate-900 border border-slate-800 hover:bg-slate-850 text-slate-450 hover:text-white text-xs font-bold rounded-xl transition-all">
                {{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}
            </a>
            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-violet-600 to-blue-500 hover:from-violet-500 hover:to-blue-400 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-violet-500/10">
                {{ app()->getLocale() == 'ar' ? 'حفظ وإضافة المود' : 'Save & Publish Mod' }}
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    let selectedConflicts = new Set();

    function searchExistingMods() {
        const query = document.getElementById('conflict-search-input').value.trim();
        const gameId = document.getElementById('game_id').value;
        const suggestionsBox = document.getElementById('conflict-suggestions');

        if (!gameId) {
            alert("{{ app()->getLocale() == 'ar' ? 'يرجى تحديد اللعبة أولاً' : 'Please select the targeted game first' }}");
            document.getElementById('conflict-search-input').value = '';
            return;
        }

        if (query.length < 2) {
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
                        if (selectedConflicts.has(mod.id)) return;
                        suggestionsBox.insertAdjacentHTML('beforeend', `
                            <div onclick="addModConflictRow(${mod.id}, '${mod.name.replace(/'/g, "\\'")}')" class="p-3 text-xs text-slate-350 hover:bg-slate-900 cursor-pointer flex items-center gap-3 border-b border-slate-900/60 last:border-0">
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

    function addModConflictRow(id, name, reasonEn = '', reasonAr = '') {
        document.getElementById('conflict-suggestions').classList.add('hidden');
        document.getElementById('conflict-search-input').value = '';

        if (selectedConflicts.has(id)) return;
        selectedConflicts.add(id);

        const container = document.getElementById('conflicts-container');
        const rowHtml = `
            <div class="grid grid-cols-12 gap-3 items-start bg-slate-950/40 p-4 rounded-xl border border-slate-900 mod-conflict-row" id="conflict-row-${id}">
                <input type="hidden" name="conflicts[]" value="${id}">
                <div class="col-span-12 sm:col-span-3 flex items-center gap-2 pt-1">
                    <i class="fa-solid fa-triangle-exclamation text-amber-500 text-xs shrink-0 animate-pulse"></i>
                    <span class="font-bold text-white text-xs">${name}</span>
                </div>
                <div class="col-span-12 sm:col-span-4 space-y-1">
                    <label class="text-[9px] text-slate-500 font-bold block uppercase">{{ app()->getLocale() == 'ar' ? 'سبب التعارض (إنجليزي)' : 'Reason (English)' }}</label>
                    <input type="text" name="conflict_reasons_en[${id}]" value="${reasonEn}" placeholder="e.g. Mod overwrites same script files." class="w-full bg-slate-950 border border-slate-850 rounded-lg px-2.5 py-1.5 text-xs text-white placeholder-slate-750 focus:outline-none focus:border-violet-600" required>
                </div>
                <div class="col-span-12 sm:col-span-4 space-y-1">
                    <label class="text-[9px] text-slate-500 font-bold block uppercase">{{ app()->getLocale() == 'ar' ? 'سبب التعارض (عربي)' : 'Reason (Arabic)' }}</label>
                    <input type="text" name="conflict_reasons_ar[${id}]" value="${reasonAr}" placeholder="مثال: المود يستبدل نفس ملفات السكريبت." class="w-full bg-slate-950 border border-slate-850 rounded-lg px-2.5 py-1.5 text-xs text-white placeholder-slate-750 focus:outline-none focus:border-violet-600" required>
                </div>
                <div class="col-span-12 sm:col-span-1 text-center pt-4 sm:pt-2">
                    <button type="button" onclick="removeModConflictRow(${id})" class="px-2.5 py-2 bg-red-600/10 border border-red-500/25 hover:bg-red-600/20 text-red-400 rounded-lg transition-all text-xs"><i class="fa-solid fa-trash-can"></i></button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', rowHtml);
    }

    function removeModConflictRow(id) {
        document.getElementById(`conflict-row-${id}`).remove();
        selectedConflicts.delete(id);
    }

    function suggestAIBasedConflicts() {
        const gameId = document.getElementById('game_id').value;
        const nameEn = document.getElementById('name_en').value;
        const descriptionEn = document.getElementById('description_en').value;
        const btn = document.getElementById('btn-ai-suggest');

        if (!gameId || !nameEn) {
            alert("{{ app()->getLocale() == 'ar' ? 'يرجى إدخال اسم المود وتحديد اللعبة أولاً.' : 'Please enter the mod name and select the targeted game first.' }}");
            return;
        }

        const origHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin"></i> {{ app()->getLocale() == 'ar' ? 'جاري التفكير...' : 'Analyzing...' }}`;

        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/admin/mods/suggest-conflicts', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                game_id: gameId,
                name_en: nameEn,
                description_en: descriptionEn
            })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = origHtml;

            if (data.length === 0) {
                alert("{{ app()->getLocale() == 'ar' ? 'لم يجد الذكاء الاصطناعي أي تعارضات واضحة مع المودات الموجودة.' : 'AI did not detect any obvious conflicts with existing mods.' }}");
                return;
            }

            // Display suggestions to the admin for checkbox approval
            // Build a modal or a listing checkbox
            let suggestionHtml = `<div class="p-4 bg-slate-900 border border-slate-800 rounded-xl space-y-3 mt-4" id="ai-suggestion-box">
                <h4 class="text-xs font-bold text-amber-400"><i class="fa-solid fa-robot mr-1"></i> {{ app()->getLocale() == 'ar' ? 'اقتراحات التعارض المكتشفة بالذكاء الاصطناعي:' : 'AI Conflict Recommendations:' }}</h4>
                <div class="space-y-2">`;
            
            data.forEach((s, idx) => {
                const modId = s.mod_id;
                const name = s.mod_name || `Mod #${modId}`;
                const reasonEn = s.reason_en;
                const reasonAr = s.reason_ar;

                suggestionHtml += `
                    <div class="flex items-start gap-2 text-xs border-b border-slate-850 pb-2 last:border-0 last:pb-0">
                        <input type="checkbox" id="ai-conf-cb-${idx}" data-id="${modId}" data-name="${name.replace(/'/g, "\\'")}" data-reason-en="${reasonEn.replace(/"/g, '&quot;')}" data-reason-ar="${reasonAr.replace(/"/g, '&quot;')}" class="w-4 h-4 accent-amber-500 mt-0.5">
                        <div class="flex-1">
                            <span class="font-bold text-white block">${name}</span>
                            <span class="text-slate-400 block text-[10px]">${reasonEn} / ${reasonAr}</span>
                        </div>
                    </div>
                `;
            });

            suggestionHtml += `</div>
                <div class="flex justify-end gap-2 pt-2 border-t border-slate-800">
                    <button type="button" onclick="approveSelectedAISuggestions()" class="px-3 py-1 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded text-[10px]">{{ app()->getLocale() == 'ar' ? 'تطبيق الموصى به' : 'Apply Selected' }}</button>
                    <button type="button" onclick="document.getElementById('ai-suggestion-box').remove()" class="px-3 py-1 bg-slate-800 hover:bg-slate-700 text-slate-400 font-bold rounded text-[10px]">{{ app()->getLocale() == 'ar' ? 'تجاهل' : 'Ignore' }}</button>
                </div>
            </div>`;

            // Append suggestions to conflict cards card
            btn.closest('.glass-card').insertAdjacentHTML('beforeend', suggestionHtml);
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = origHtml;
            alert('Error: ' + err.message);
        });
    }

    function approveSelectedAISuggestions() {
        const checked = document.querySelectorAll('[id^="ai-conf-cb-"]:checked');
        if (checked.length === 0) {
            alert("{{ app()->getLocale() == 'ar' ? 'يرجى تحديد اقتراح واحد على الأقل.' : 'Please select at least one suggestion.' }}");
            return;
        }

        checked.forEach(cb => {
            const id = parseInt(cb.getAttribute('data-id'));
            const name = cb.getAttribute('data-name');
            const reasonEn = cb.getAttribute('data-reason-en');
            const reasonAr = cb.getAttribute('data-reason-ar');
            addModConflictRow(id, name, reasonEn, reasonAr);
        });

        document.getElementById('ai-suggestion-box').remove();
    }

    // Hide suggestions list when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#conflict-search-input')) {
            document.getElementById('conflict-suggestions').classList.add('hidden');
        }
    });
</script>
@endsection
