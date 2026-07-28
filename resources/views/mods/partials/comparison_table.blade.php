@if($mods->isEmpty())
    <div class="py-16 text-center text-slate-500 border border-dashed border-slate-800 rounded-2xl">
        <i class="fa-solid fa-code-compare text-4xl mb-4 text-slate-700"></i>
        <p class="text-sm font-semibold">{{ app()->getLocale() == 'ar' ? 'يرجى اختيار من 2 إلى 4 مودات للمقارنة.' : 'Please select 2 to 4 mods to compare.' }}</p>
    </div>
@else
    <div class="overflow-x-auto rounded-2xl border border-slate-800 shadow-xl bg-slate-900/10">
        <table class="w-full text-right text-xs border-collapse" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
            <thead>
                <tr class="bg-slate-950/60 border-b border-slate-800 text-white font-bold">
                    <th class="px-5 py-4 border-r border-slate-800/60 text-slate-450">{{ app()->getLocale() == 'ar' ? 'الخاصية' : 'Feature' }}</th>
                    @foreach($mods as $mod)
                        <th class="px-5 py-4 text-center border-r border-slate-800/60 last:border-r-0">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-950 border border-slate-800 shrink-0">
                                    <img src="{{ $mod->image_url ?: 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiM0NzU1NjkiIHN0cm9rZS13aWR0aD0iMiI+PHJlY3Qgd2lkdGg9IjE4IiBoZWlnaHQ9IjE4IiB4PSIzIiB5PSIzIiByeD0iMiIvPjxwYXRoIGQ9Im0yMSAxNi00LTQiLz48Y2lyY2xlIGN4PSIxNSIgY3k9IjkiIHI9IjIiLz48L3N2Zz4=' }}" class="w-full h-full object-cover">
                                </div>
                                <span class="text-sm font-black">{{ $mod->name }}</span>
                            </div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/50">
                <!-- Category -->
                <tr class="hover:bg-slate-800/10">
                    <td class="px-5 py-3.5 font-bold text-slate-450 border-r border-slate-800/60">{{ app()->getLocale() == 'ar' ? 'التصنيف' : 'Category' }}</td>
                    @foreach($mods as $mod)
                        <td class="px-5 py-3.5 text-center text-slate-300 border-r border-slate-800/60 last:border-r-0">
                            @if($mod->category)
                                <span class="px-2.5 py-1 rounded-md bg-violet-600/10 text-violet-400 font-bold border border-violet-500/15">
                                    {{ app()->getLocale() == 'ar' ? $mod->category->name_ar : $mod->category->name_en }}
                                </span>
                            @else
                                <span class="text-slate-600">-</span>
                            @endif
                        </td>
                    @endforeach
                </tr>

                <!-- Views / Downloads -->
                <tr class="hover:bg-slate-800/10">
                    <td class="px-5 py-3.5 font-bold text-slate-450 border-r border-slate-800/60">{{ app()->getLocale() == 'ar' ? 'المشاهدات / التنزيلات' : 'Views / Downloads' }}</td>
                    @foreach($mods as $mod)
                        <td class="px-5 py-3.5 text-center text-slate-300 font-mono border-r border-slate-800/60 last:border-r-0">
                            <i class="fa-regular fa-eye mr-1 text-slate-500"></i> {{ number_format($mod->views_count ?? 0) }}
                        </td>
                    @endforeach
                </tr>

                <!-- Last Updated -->
                <tr class="hover:bg-slate-800/10">
                    <td class="px-5 py-3.5 font-bold text-slate-450 border-r border-slate-800/60">{{ app()->getLocale() == 'ar' ? 'آخر تحديث' : 'Last Updated' }}</td>
                    @foreach($mods as $mod)
                        <td class="px-5 py-3.5 text-center text-slate-300 border-r border-slate-800/60 last:border-r-0">
                            {{ $mod->updated_at->diffForHumans() }}
                        </td>
                    @endforeach
                </tr>

                <!-- Description -->
                <tr class="hover:bg-slate-800/10">
                    <td class="px-5 py-4 font-bold text-slate-450 border-r border-slate-800/60 align-top">{{ app()->getLocale() == 'ar' ? 'الوصف' : 'Description' }}</td>
                    @foreach($mods as $mod)
                        <td class="px-5 py-4 text-slate-400 leading-relaxed border-r border-slate-800/60 last:border-r-0 max-w-xs text-[11px] align-top text-justify">
                            {{ Str::limit($mod->description, 200) ?: '-' }}
                        </td>
                    @endforeach
                </tr>

                <!-- Steam URL -->
                <tr class="hover:bg-slate-800/10">
                    <td class="px-5 py-3.5 font-bold text-slate-450 border-r border-slate-800/60">Steam Workshop</td>
                    @foreach($mods as $mod)
                        <td class="px-5 py-3.5 text-center border-r border-slate-800/60 last:border-r-0">
                            @if($mod->steam_url)
                                <a href="{{ $mod->steam_url }}" target="_blank" class="inline-flex items-center gap-1 text-blue-400 hover:underline font-semibold">
                                    <i class="fa-brands fa-steam"></i>
                                    <span>{{ app()->getLocale() == 'ar' ? 'رابط الورشة' : 'Workshop Link' }}</span>
                                </a>
                            @else
                                <span class="text-slate-650">-</span>
                            @endif
                        </td>
                    @endforeach
                </tr>

                <!-- Nexus URL -->
                <tr class="hover:bg-slate-800/10">
                    <td class="px-5 py-3.5 font-bold text-slate-450 border-r border-slate-800/60">Nexus Mods</td>
                    @foreach($mods as $mod)
                        <td class="px-5 py-3.5 text-center border-r border-slate-800/60 last:border-r-0">
                            @if($mod->nexus_url)
                                <a href="{{ $mod->nexus_url }}" target="_blank" class="inline-flex items-center gap-1 text-orange-400 hover:underline font-semibold">
                                    <i class="fa-solid fa-gamepad"></i>
                                    <span>{{ app()->getLocale() == 'ar' ? 'صفحة المود' : 'Mod Page' }}</span>
                                </a>
                            @else
                                <span class="text-slate-650">-</span>
                            @endif
                        </td>
                    @endforeach
                </tr>
                <!-- Performance Tags -->
                <tr class="hover:bg-slate-800/10">
                    <td class="px-5 py-3.5 font-bold text-slate-450 border-r border-slate-800/60">{{ app()->getLocale() == 'ar' ? 'تأثير الأداء (Performance)' : 'Performance Impact' }}</td>
                    @foreach($mods as $mod)
                        <td class="px-5 py-3.5 text-center text-slate-300 border-r border-slate-800/60 last:border-r-0">
                            @if(isset($mod->fps_impact) && $mod->fps_impact)
                                <span class="px-2 py-1 rounded-md bg-amber-500/10 text-amber-400 border border-amber-500/20 font-mono text-[10px]">{{ $mod->fps_impact }}</span>
                            @else
                                <span class="px-2 py-1 rounded-md bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-mono text-[10px]">Minimal / None</span>
                            @endif
                        </td>
                    @endforeach
                </tr>

                <!-- Before/After Visual Comparison -->
                <tr class="hover:bg-slate-800/10">
                    <td class="px-5 py-3.5 font-bold text-slate-450 border-r border-slate-800/60">{{ app()->getLocale() == 'ar' ? 'مقارنة بصرية (Before / After)' : 'Visual Comparison' }}</td>
                    @foreach($mods as $mod)
                        <td class="px-5 py-4 text-center border-r border-slate-800/60 last:border-r-0">
                            @if(isset($mod->before_image_url) && $mod->before_image_url && $mod->image_url)
                                <div class="relative w-full h-32 rounded-xl overflow-hidden group cursor-col-resize select-none border border-slate-800" onmousemove="updateSlider(event, this)" ontouchmove="updateSlider(event, this)">
                                    <!-- Before Image (Base Game) -->
                                    <img src="{{ $mod->before_image_url }}" class="absolute top-0 left-0 w-full h-full object-cover" alt="Before">
                                    <!-- After Image (Modded) -->
                                    <div class="absolute top-0 left-0 w-1/2 h-full overflow-hidden border-r-2 border-white shadow-[0_0_10px_rgba(0,0,0,0.5)] z-10 slider-after">
                                        <img src="{{ $mod->image_url }}" class="absolute top-0 left-0 w-full max-w-none h-full object-cover slider-after-img" alt="After">
                                    </div>
                                    <div class="absolute bottom-2 left-2 px-1.5 py-0.5 bg-black/60 text-[9px] text-white rounded z-20">After</div>
                                    <div class="absolute bottom-2 right-2 px-1.5 py-0.5 bg-black/60 text-[9px] text-white rounded">Before</div>
                                </div>
                            @else
                                <div class="h-32 flex items-center justify-center bg-slate-900/50 rounded-xl border border-dashed border-slate-800 text-[10px] text-slate-500">
                                    {{ app()->getLocale() == 'ar' ? 'لا توجد صور للمقارنة' : 'No comparison images' }}
                                </div>
                            @endif
                        </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Conflicts Row Warning below the table -->
    @if(!empty($conflicts))
        <div class="p-4 bg-red-950/40 border border-red-500/30 rounded-2xl text-xs text-red-400 space-y-2 flex items-start gap-3 mt-6">
            <i class="fa-solid fa-triangle-exclamation text-lg text-red-500 shrink-0 mt-0.5 animate-pulse"></i>
            <div>
                <p class="font-bold text-sm mb-1">{{ app()->getLocale() == 'ar' ? '⚠️ تحذير: تم كشف تعارضات بين المودات المختارة!' : '⚠️ Warning: Conflicts detected between selected mods!' }}</p>
                <div class="space-y-1.5 mt-2">
                    @foreach($conflicts as $c)
                        @php
                            $m1 = $mods->firstWhere('id', $c['mod_id']);
                            $m2 = $mods->firstWhere('id', $c['conflicts_with_mod_id']);
                        @endphp
                        @if($m1 && $m2)
                            <div class="border-l-2 border-red-500/50 pl-3 rtl:border-l-0 rtl:border-r-2 rtl:pr-3 py-1">
                                <span class="font-bold text-white">{{ $m1->name }}</span>
                                <span class="text-slate-400 mx-1">↔</span>
                                <span class="font-bold text-white">{{ $m2->name }}</span>
                                <p class="text-[10px] text-slate-400 mt-0.5">
                                    {{ app()->getLocale() == 'ar' ? ($c['reason_ar'] ?: $c['reason_en']) : ($c['reason_en'] ?: $c['reason_ar']) }}
                                </p>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endif
