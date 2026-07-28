<div class="glass-card rounded-2xl border border-slate-800 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-800/80 bg-slate-900/40 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <h3 class="font-bold text-white tracking-wide">
            <i class="fa-solid fa-list text-violet-500 mr-2 rtl:ml-2"></i>
            {{ $game->name }} Mods Library
        </h3>
        <div class="flex items-center gap-3">
            <span class="text-xs text-slate-500 font-semibold">{{ $mods->total() }} Mods Found</span>
            @if(auth()->check() && auth()->user()->is_admin)
                <form action="{{ route('admin.mods.fix-unknown-versions') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-1 rounded-lg bg-orange-600/20 border border-orange-500/30 hover:bg-orange-600/40 text-orange-400 text-xs font-bold transition flex items-center gap-1.5">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        <span>إصلاح الإصدارات غير المعروفة</span>
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left rtl:text-right text-sm">
            <thead class="bg-slate-950/60 text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-800">
                <tr>
                    <th class="px-6 py-3 w-16 text-center">#</th>
                    <th class="px-6 py-3">Mod Name</th>
                    <th class="px-6 py-3">Compatible Versions</th>
                    <th class="px-6 py-3 text-center">Comments</th>
                    <th class="px-6 py-3 text-right rtl:text-left">Get Mod</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60">
                @forelse($mods as $index => $mod)
                    <tr class="border-b border-slate-800/50 hover:bg-slate-800/30 transition-colors group">
                        <td class="px-6 py-4 font-mono font-bold text-center text-violet-400">
                            {{ ($mods->currentPage() - 1) * $mods->perPage() + $index + 1 }}
                        </td>
                        <td class="px-6 py-4 text-white font-semibold">
                            <a href="{{ route('mods.show', $mod->slug ?: $mod->id) }}" class="hover:text-violet-400 transition-colors flex items-center space-x-3 rtl:space-x-reverse">
                                <div class="w-10 h-10 rounded-lg overflow-hidden bg-slate-900 border border-slate-800 flex-shrink-0 flex items-center justify-center transition-transform group-hover:scale-105">
                                    @if($mod->local_image_path || $mod->image_url)
                                        <img src="{{ $mod->local_image_path ?: $mod->image_url }}" alt="" class="w-full h-full object-cover" loading="lazy">
                                    @elseif($mod->steam_url)
                                        <i class="fa-brands fa-steam text-violet-500 text-lg"></i>
                                    @else
                                        <i class="fa-solid fa-cube text-blue-400 text-md"></i>
                                    @endif
                                </div>
                                <div class="space-y-0.5">
                                    <span class="block font-bold text-white group-hover:text-violet-400 transition-colors">
                                        {{ $mod->name }}
                                        @if($mod->is_verified)
                                            <i class="fa-solid fa-circle-check text-blue-500 ml-1" title="Verified Mod"></i>
                                        @endif
                                    </span>
                                    @if($mod->author)
                                        <span class="text-[10px] text-slate-500 font-normal block">by {{ $mod->author }}</span>
                                    @endif
                                    
                                    @if($mod->fps_impact !== null)
                                        @php
                                            $fpsImpact = (int) $mod->fps_impact;
                                            $fpsColor = $fpsImpact <= 2 ? 'text-emerald-400' : ($fpsImpact <= 10 ? 'text-amber-400' : 'text-red-400');
                                            $fpsIcon = $fpsImpact <= 2 ? 'fa-bolt' : ($fpsImpact <= 10 ? 'fa-weight-hanging' : 'fa-anchor');
                                        @endphp
                                        <span class="text-[9px] font-bold {{ $fpsColor }} block mt-1" title="{{ $fpsImpact }} FPS Drop">
                                            <i class="fa-solid {{ $fpsIcon }}"></i> -{{ $fpsImpact }} FPS
                                        </span>
                                    @endif
                                </div>
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @forelse($mod->gameVersions as $version)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-950 border border-slate-800 text-emerald-400 font-mono">
                                        {{ $version->version }}
                                    </span>
                                @empty
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-900 border border-slate-800 text-slate-500">
                                        All Versions
                                    </span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center text-xs text-slate-400">
                            <span class="inline-flex items-center space-x-1 rtl:space-x-reverse bg-slate-950/40 px-2 py-1 rounded border border-slate-800/50">
                                <i class="fa-regular fa-comment text-violet-400 text-[10px]"></i>
                                <span class="font-bold text-slate-300">{{ $mod->comments_count }}</span>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right rtl:text-left">
                            <div class="flex items-center justify-end space-x-3 rtl:space-x-reverse flex-wrap gap-2">
                                @if(auth()->check() && auth()->user()->is_admin)
                                    <form action="{{ route('admin.mods.sync-nexus', $mod->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" title="جلب المعلومات والصورة تلقائياً من Nexus Mods" class="text-xs text-orange-400 hover:text-orange-300 font-bold mr-1 flex items-center gap-1">
                                            <i class="fa-solid fa-rotate"></i>
                                            <span>Nexus Sync</span>
                                        </button>
                                    </form>
                                    <button type="button" onclick="document.getElementById('edit-mod-modal-{{ $mod->id }}').classList.remove('hidden'); document.getElementById('edit-mod-modal-{{ $mod->id }}').classList.add('flex');" class="text-xs text-blue-400 hover:text-blue-300 font-bold mr-2">
                                        Edit
                                    </button>
                                @endif
                                
                                @auth
                                    <button onclick="reportMod({{ $mod->id }})" title="Report Issue" class="inline-flex items-center text-xs text-red-400 hover:text-red-350 font-bold ml-2">
                                        <i class="fa-solid fa-flag text-[10px]"></i>
                                    </button>
                                @endauth
                                
                                @if($mod->nexus_url)
                                    <a href="{{ route('link.redirect', ['url' => base64_encode($mod->nexus_url), 'mod' => $mod->id]) }}" target="_blank" class="inline-flex items-center text-xs text-orange-400 hover:text-orange-350 hover:underline font-bold">
                                        <i class="fa-solid fa-download mr-1 rtl:ml-1 text-[10px]"></i>
                                        <span>Nexus</span>
                                    </a>
                                @endif
                                @if($mod->steam_url)
                                    <a href="{{ route('link.redirect', ['url' => base64_encode($mod->steam_url), 'mod' => $mod->id]) }}" target="_blank" class="inline-flex items-center text-xs text-blue-400 hover:text-blue-350 hover:underline font-bold">
                                        <i class="fa-brands fa-steam mr-1 rtl:ml-1 text-[10px]"></i>
                                        <span>Steam</span>
                                    </a>
                                @endif
                                @if($mod->download_url && $mod->download_url !== $mod->nexus_url && $mod->download_url !== $mod->steam_url)
                                    <a href="{{ route('link.redirect', ['url' => base64_encode($mod->download_url), 'mod' => $mod->id]) }}" target="_blank" class="inline-flex items-center text-xs text-emerald-400 hover:text-emerald-350 hover:underline font-bold">
                                        <i class="fa-solid fa-link mr-1 rtl:ml-1 text-[10px]"></i>
                                        <span>Direct</span>
                                    </a>
                                @endif
                                @if(!$mod->nexus_url && !$mod->steam_url && !$mod->download_url)
                                    <span class="text-xs text-slate-650">-</span>
                                @endif
                            </div>

                            @if(auth()->check() && auth()->user()->is_admin)
                                <!-- Mod Edit Modal -->
                                <div id="edit-mod-modal-{{ $mod->id }}" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
                                    <div class="glass-card p-6 md:p-8 rounded-3xl border border-slate-850 max-w-lg w-full space-y-4 text-right">
                                        <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                                            <h4 class="font-bold text-white text-sm">تعديل بيانات المود</h4>
                                            <button type="button" onclick="document.getElementById('edit-mod-modal-{{ $mod->id }}').classList.add('hidden'); document.getElementById('edit-mod-modal-{{ $mod->id }}').classList.remove('flex');" class="text-slate-500 hover:text-white text-lg font-bold">&times;</button>
                                        </div>
                                        <form action="{{ route('admin.mods.update', $mod->id) }}" method="POST" class="space-y-4 text-xs">
                                            @csrf @method('PUT')
                                            <div class="space-y-1">
                                                <label class="text-slate-400 font-bold block text-right">اسم المود</label>
                                                <input type="text" name="name" value="{{ $mod->name }}" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white text-xs focus:outline-none focus:border-violet-600 text-left" required>
                                            </div>
                                            <div class="space-y-1">
                                                <label class="text-slate-400 font-bold block text-right">ترتيب التحميل (Load Order)</label>
                                                <input type="number" name="load_order" value="{{ $mod->load_order }}" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white text-xs focus:outline-none focus:border-violet-600 text-left" required>
                                            </div>
                                            <div class="space-y-1">
                                                <label class="text-slate-400 font-bold block text-right">رابط Steam Workshop</label>
                                                <input type="text" name="steam_url" value="{{ $mod->steam_url }}" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white text-xs focus:outline-none focus:border-violet-600 text-left">
                                            </div>
                                            <div class="space-y-1">
                                                <label class="text-slate-400 font-bold block text-right">رابط Nexus Mods</label>
                                                <input type="text" name="nexus_url" value="{{ $mod->nexus_url }}" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white text-xs focus:outline-none focus:border-violet-600 text-left">
                                            </div>
                                            <div class="space-y-1">
                                                <label class="text-slate-400 font-bold block text-right">رابط تحميل مباشر آخر</label>
                                                <input type="text" name="download_url" value="{{ $mod->download_url }}" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white text-xs focus:outline-none focus:border-violet-600 text-left">
                                            </div>
                                            <div class="flex justify-end space-x-2 space-x-reverse pt-3 border-t border-slate-800">
                                                <button type="submit" class="px-5 py-2 bg-violet-600 hover:bg-violet-500 rounded-xl text-white font-bold transition-all">حفظ التغييرات</button>
                                                <button type="button" onclick="document.getElementById('edit-mod-modal-{{ $mod->id }}').classList.add('hidden'); document.getElementById('edit-mod-modal-{{ $mod->id }}').classList.remove('flex');" class="px-5 py-2 bg-slate-800 hover:bg-slate-700 rounded-xl text-slate-400 font-bold transition-all">إلغاء</button>
                                            </div>
                                        </form>
                                        
                                        <!-- Dependencies Form -->
                                        <form action="{{ route('admin.mods.dependencies.add') }}" method="POST" class="mt-4 pt-4 border-t border-slate-800 text-xs">
                                            @csrf
                                            <input type="hidden" name="mod_id" value="{{ $mod->id }}">
                                            <div class="space-y-1">
                                                <label class="text-slate-400 font-bold block text-right">إضافة متطلب (Dependency) برقم الـ ID</label>
                                                <div class="flex gap-2" dir="ltr">
                                                    <input type="number" name="requires_mod_id" placeholder="Mod ID" class="w-24 bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white text-xs focus:outline-none focus:border-blue-600 text-center" required>
                                                    <button type="submit" class="px-4 py-2 bg-blue-600/20 text-blue-400 hover:bg-blue-600 hover:text-white border border-blue-500/30 rounded-xl font-bold transition-all">Add Dependency</button>
                                                </div>
                                                @if($mod->dependencies && $mod->dependencies->count() > 0)
                                                    <div class="mt-2 text-slate-500">
                                                        Dependencies: 
                                                        @foreach($mod->dependencies as $dep)
                                                            <span class="inline-block px-2 py-1 bg-slate-800 rounded text-[10px] text-slate-300 mr-1">{{ $dep->name }} (ID:{{ $dep->id }})</span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </form>
                                        
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            <i class="fa-regular fa-folder-open text-2xl mb-2 text-slate-700 block"></i>
                            No mods found matching your search or filters.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($mods->hasPages())
    <div class="mt-4 pagination">
        {{ $mods->appends(request()->query())->links() }}
    </div>
@endif
