@extends('layouts.app')

@section('title', 'لوحة التحكم - إدارة المنصة')

@section('content')
<div class="min-h-[85vh] flex flex-col lg:flex-row gap-6" dir="rtl">

    {{-- ===== RIGHT SIDEBAR ===== --}}
    <aside class="lg:w-64 shrink-0">
        <div class="glass-card rounded-2xl border border-slate-800 p-4 lg:sticky lg:top-24 space-y-3">
            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider px-2 mb-1">القائمة الرئيسية</h3>

            <button onclick="switchAdminTab('metrics')" id="admin-tab-btn-metrics"
                class="admin-sidebar-btn w-full text-right flex items-center gap-3 px-4 py-3 rounded-xl border transition-all duration-200 bg-slate-900/50 border-violet-500/60 text-violet-400 font-bold">
                <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                <span class="text-xs">الإحصائيات العامة</span>
            </button>

            <button onclick="switchAdminTab('games')" id="admin-tab-btn-games"
                class="admin-sidebar-btn w-full text-right flex items-center gap-3 px-4 py-3 rounded-xl border transition-all duration-200 border-transparent text-slate-400 hover:bg-slate-800/60 hover:text-white">
                <i class="fa-solid fa-gamepad w-5 text-center"></i>
                <span class="text-xs">إدارة الألعاب وتحديثاتها</span>
            </button>

            <button onclick="switchAdminTab('modpacks')" id="admin-tab-btn-modpacks"
                class="admin-sidebar-btn w-full text-right flex items-center gap-3 px-4 py-3 rounded-xl border transition-all duration-200 border-transparent text-slate-400 hover:bg-slate-800/60 hover:text-white">
                <i class="fa-solid fa-boxes-stacked w-5 text-center"></i>
                <span class="text-xs">إدارة التجميعات (ModPacks)</span>
            </button>

            <button onclick="switchAdminTab('mods')" id="admin-tab-btn-mods"
                class="admin-sidebar-btn w-full text-right flex items-center gap-3 px-4 py-3 rounded-xl border transition-all duration-200 border-transparent text-slate-400 hover:bg-slate-800/60 hover:text-white">
                <i class="fa-solid fa-cube w-5 text-center"></i>
                <span class="text-xs">إدارة المودات والمكتبة</span>
            </button>

            <button onclick="switchAdminTab('users')" id="admin-tab-btn-users"
                class="admin-sidebar-btn w-full text-right flex items-center gap-3 px-4 py-3 rounded-xl border transition-all duration-200 border-transparent text-slate-400 hover:bg-slate-800/60 hover:text-white">
                <i class="fa-solid fa-users w-5 text-center"></i>
                <span class="text-xs">إدارة الأعضاء (Users)</span>
            </button>

            <button onclick="switchAdminTab('comments')" id="admin-tab-btn-comments"
                class="admin-sidebar-btn w-full text-right flex items-center gap-3 px-4 py-3 rounded-xl border transition-all duration-200 border-transparent text-slate-400 hover:bg-slate-800/60 hover:text-white">
                <i class="fa-solid fa-comments w-5 text-center"></i>
                <span class="text-xs">إدارة التعليقات والتقييمات</span>
            </button>

            <button onclick="switchAdminTab('ai-hub')" id="admin-tab-btn-ai-hub"
                class="admin-sidebar-btn w-full text-right flex items-center gap-3 px-4 py-3 rounded-xl border transition-all duration-200 border-transparent text-slate-400 hover:bg-slate-800/60 hover:text-white">
                <i class="fa-solid fa-robot w-5 text-center"></i>
                <span class="text-xs">مركز الأتمتة والذكاء الاصطناعي</span>
            </button>

            <button onclick="switchAdminTab('conflicts-metrics')" id="admin-tab-btn-conflicts-metrics"
                class="admin-sidebar-btn w-full text-right flex items-center gap-3 px-4 py-3 rounded-xl border transition-all duration-200 border-transparent text-slate-400 hover:bg-slate-800/60 hover:text-white">
                <i class="fa-solid fa-triangle-exclamation w-5 text-center text-amber-500"></i>
                <span class="text-xs">إحصائيات التعارضات</span>
            </button>

            <button onclick="switchAdminTab('extraction-logs')" id="admin-tab-btn-extraction-logs"
                class="admin-sidebar-btn w-full text-right flex items-center gap-3 px-4 py-3 rounded-xl border transition-all duration-200 border-transparent text-slate-400 hover:bg-slate-800/60 hover:text-white">
                <i class="fa-solid fa-list-check w-5 text-center text-blue-500"></i>
                <span class="text-xs">سجل استخراج المودات</span>
            </button>

            <button onclick="switchAdminTab('settings')" id="admin-tab-btn-settings"
                class="admin-sidebar-btn w-full text-right flex items-center gap-3 px-4 py-3 rounded-xl border transition-all duration-200 border-transparent text-slate-400 hover:bg-slate-800/60 hover:text-white">
                <i class="fa-solid fa-gear w-5 text-center text-slate-300"></i>
                <span class="text-xs">إعدادات الموقع (Settings)</span>
            </button>

            <button onclick="switchAdminTab('newsletter')" id="admin-tab-btn-newsletter"
                class="admin-sidebar-btn w-full text-right flex items-center gap-3 px-4 py-3 rounded-xl border transition-all duration-200 border-transparent text-slate-400 hover:bg-slate-800/60 hover:text-white">
                <i class="fa-solid fa-envelope-open-text w-5 text-center text-teal-400"></i>
                <span class="text-xs">القائمة البريدية (Newsletter)</span>
            </button>

            <a href="{{ route('admin.review-queue') }}" class="w-full text-right flex items-center gap-3 px-4 py-3 rounded-xl border transition-all duration-200 border-transparent text-amber-400 hover:bg-slate-800/60 hover:text-amber-300 font-bold">
                <i class="fa-solid fa-list-check w-5 text-center"></i>
                <span class="text-xs">مراجعة المودات (Review Queue)</span>
            </a>

            <a href="{{ route('admin.ads.index') }}" class="w-full text-right flex items-center gap-3 px-4 py-3 rounded-xl border transition-all duration-200 border-transparent text-slate-400 hover:bg-slate-800/60 hover:text-slate-200 font-bold">
                <i class="fa-solid fa-ad w-5 text-center"></i>
                <span class="text-xs">إدارة الإعلانات (Ads Management)</span>
            </a>

            <a href="{{ route('admin.review-queue') }}" class="w-full text-right flex items-center gap-3 px-4 py-3 rounded-xl border transition-all duration-200 border-transparent text-slate-400 hover:bg-slate-800/60 hover:text-slate-200 font-bold">
                <div class="relative w-5 text-center">
                    <i class="fa-solid fa-flag text-rose-500"></i>
                    @if(isset($pendingReportsCount) && $pendingReportsCount > 3)
                        <span class="absolute -top-2 -right-2 flex h-3 w-3 items-center justify-center rounded-full bg-red-600 text-[8px] text-white animate-bounce shadow-lg shadow-red-500/50">!</span>
                    @endif
                </div>
                <span class="text-xs flex-1">البلاغات (Reports)</span>
                @if(isset($pendingReportsCount) && $pendingReportsCount > 0)
                    <span class="px-2 py-0.5 rounded-full bg-slate-800 text-[10px] text-slate-300">{{ $pendingReportsCount }}</span>
                @endif
            </a>

            <div class="border-t border-slate-800 pt-3 mt-2">
                <div class="text-[10px] text-slate-600 px-2 flex items-center gap-2">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                    الأنظمة تعمل بكفاءة
                </div>
            </div>
        </div>
    </aside>

    {{-- ===== MAIN CONTENT AREA ===== --}}
    <div class="flex-1 min-w-0 space-y-6">

        {{-- Dashboard Header --}}
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-white">لوحة تحكم الإدارة</h1>
                <p class="text-xs text-slate-500 mt-1">مرحباً بك في وحدة التحكم والإشراف الفني لمنصة <span class="text-violet-400 font-bold">LoadOrderHub</span>.</p>
            </div>
            @if(auth()->check())
            <div class="flex items-center gap-2 text-xs">
                <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                <span class="text-emerald-400 font-bold">Logged in as {{ auth()->user()->name }}</span>
            </div>
            @endif
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="glass-card p-4 rounded-xl border border-emerald-800/60 text-emerald-400 text-xs font-bold flex items-center gap-2">
            <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="glass-card p-4 rounded-xl border border-red-800/60 text-red-400 text-xs font-bold flex items-center gap-2">
            <i class="fa-solid fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif

        {{-- Global Search --}}
        <form method="GET" action="{{ route('admin.dashboard') }}" class="glass-card rounded-xl border border-slate-800 p-3 flex items-center gap-3">
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="البحث في كل الأقسام..." class="flex-1 bg-transparent text-white text-xs placeholder-slate-500 focus:outline-none">
            <button type="submit" class="px-4 py-2 bg-violet-600 hover:bg-violet-500 rounded-lg text-white text-xs font-bold transition-all">
                <i class="fa-solid fa-search ml-1"></i> البحث
            </button>
        </form>

        {{-- ============================================= --}}
        {{-- TAB 1: METRICS / STATISTICS --}}
        {{-- ============================================= --}}
        <div id="admin-panel-metrics" class="admin-tab-panel space-y-6">
            <div class="flex justify-between items-center">
                <h2 class="text-white font-bold text-lg">نظرة عامة</h2>
                <a href="{{ route('admin.export.csv') }}" class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-500 hover:to-teal-400 rounded-lg text-white text-xs font-bold transition-all shadow-lg shadow-emerald-500/20">
                    <i class="fa-solid fa-file-csv ml-1"></i> تصدير للإكسيل (CSV)
                </a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="glass-card rounded-2xl border border-slate-800 p-5 text-center gradient-border-hover">
                    <div class="text-3xl font-black text-white">{{ $metrics['games_count'] ?? 0 }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">الألعاب المدعومة</div>
                    <i class="fa-solid fa-gamepad text-violet-500/30 text-2xl mt-2"></i>
                </div>
                <div class="glass-card rounded-2xl border border-slate-800 p-5 text-center gradient-border-hover">
                    <div class="text-3xl font-black text-white">{{ $metrics['versions_count'] ?? 0 }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">تحديثات الألعاب</div>
                    <i class="fa-solid fa-code-branch text-blue-500/30 text-2xl mt-2"></i>
                </div>
                <div class="glass-card rounded-2xl border border-slate-800 p-5 text-center gradient-border-hover">
                    <div class="text-3xl font-black text-white">{{ $metrics['modpacks_count'] ?? 0 }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">التجميعات المنشورة</div>
                    <i class="fa-solid fa-boxes-stacked text-emerald-500/30 text-2xl mt-2"></i>
                </div>
                <div class="glass-card rounded-2xl border border-slate-800 p-5 text-center gradient-border-hover">
                    <div class="text-3xl font-black text-white">{{ $metrics['mods_count'] ?? 0 }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">إجمالي المودات</div>
                    <i class="fa-solid fa-cube text-cyan-500/30 text-2xl mt-2"></i>
                </div>
                <div class="glass-card rounded-2xl border border-slate-800 p-5 text-center gradient-border-hover">
                    <div class="text-3xl font-black text-rose-500">{{ $metrics['missing_images_count'] ?? 0 }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">صور مفقودة</div>
                    <i class="fa-solid fa-image text-rose-500/30 text-2xl mt-2"></i>
                </div>
                <div class="glass-card rounded-2xl border border-slate-800 p-5 text-center gradient-border-hover">
                    <div class="text-3xl font-black text-white">{{ $metrics['users_count'] ?? 0 }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">الأعضاء المسجلين</div>
                    <i class="fa-solid fa-users text-amber-500/30 text-2xl mt-2"></i>
                </div>
                <div class="glass-card rounded-2xl border border-slate-800 p-5 text-center gradient-border-hover">
                    <div class="text-3xl font-black text-white">{{ $metrics['comments_count'] ?? 0 }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">إجمالي التعليقات</div>
                    <i class="fa-solid fa-comments text-rose-500/30 text-2xl mt-2"></i>
                </div>
            </div>
            <div class="glass-card rounded-2xl border border-slate-800 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-white text-sm mb-1">إحصائيات المنصة</h3>
                        <p class="text-xs text-slate-400 leading-relaxed max-w-xl">المنصة تعمل الآن بنظام القيادة الذاتية (Zero-Touch). المهام المجدولة تقوم بالبحث اليومي التلقائي عن المودات وتوليد العناوين بالذكاء الاصطناعي ونشرها لزوار الموقع دون الحاجة لأي تدخل فني.</p>
                    </div>
                    <form method="POST" action="{{ route('admin.mods.translate-old') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-violet-600/20 border border-violet-500/30 hover:bg-violet-600/40 text-violet-400 font-bold text-xs transition">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                            أداة تنظيف وترجمة المودات بالـ AI
                        </button>
                    </form>
                    
                    <form method="POST" action="{{ route('admin.fix-missing-images') }}" class="mt-2 md:mt-0 md:mr-2 md:rtl:ml-2 md:rtl:mr-0">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600/20 border border-emerald-500/30 hover:bg-emerald-600/40 text-emerald-400 font-bold text-xs transition">
                            <i class="fa-solid fa-images"></i>
                            إصلاح وجلب الصور المفقودة للمودات
                        </button>
                    </form>
                </div>
            </div>

            <!-- System Status Card -->
            <div class="glass-card rounded-2xl border border-slate-800 p-5 mt-6">
                <h3 class="text-white font-bold mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-server text-emerald-500"></i> حالة النظام (System Status)
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="p-4 bg-slate-900/50 rounded-xl border border-slate-800/60">
                        <div class="text-xs text-slate-500 font-bold mb-1">إصدار Laravel</div>
                        <div class="text-sm text-slate-300 font-mono">{{ app()->version() }}</div>
                    </div>
                    <div class="p-4 bg-slate-900/50 rounded-xl border border-slate-800/60">
                        <div class="text-xs text-slate-500 font-bold mb-1">إصدار PHP</div>
                        <div class="text-sm text-slate-300 font-mono">{{ phpversion() }}</div>
                    </div>
                    <div class="p-4 bg-slate-900/50 rounded-xl border border-slate-800/60">
                        <div class="text-xs text-slate-500 font-bold mb-1">قاعدة البيانات</div>
                        <div class="text-sm text-emerald-400 font-mono flex items-center gap-1.5">
                            <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                            متصلة ({{ \DB::connection()->getPdo()->getAttribute(\PDO::ATTR_DRIVER_NAME) }})
                        </div>
                    </div>
                    <div class="p-4 bg-slate-900/50 rounded-xl border border-slate-800/60">
                        <div class="text-xs text-slate-500 font-bold mb-1">بيئة التشغيل</div>
                        <div class="text-sm text-slate-300 font-mono uppercase">{{ app()->environment() }}</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Activity Log --}}
                <div class="glass-card rounded-2xl border border-slate-800 p-6 space-y-4">
                    <h3 class="font-bold text-white text-sm flex items-center gap-2">
                        <i class="fa-solid fa-bolt text-amber-400"></i>
                        أحدث النشاطات (Activity)
                    </h3>
                    <div class="space-y-3">
                        @foreach(collect($comments)->take(3) as $c)
                        <div class="flex items-start gap-3 p-3 bg-slate-900/50 rounded-xl border border-slate-800/60">
                            <i class="fa-solid fa-comment text-rose-500/80 mt-1"></i>
                            <div>
                                <p class="text-[10px] text-slate-300">علق <span class="text-white font-bold">{{ $c->user->name ?? 'زائر' }}</span> على تجميعة <span class="text-violet-400">{{ Str::limit($c->modPack?->title_en, 20) }}</span></p>
                                <p class="text-[9px] text-slate-500 mt-0.5">{{ $c->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach
                        @foreach(collect($extractionLogs)->take(2) as $log)
                        <div class="flex items-start gap-3 p-3 bg-slate-900/50 rounded-xl border border-slate-800/60">
                            <i class="fa-solid fa-robot text-violet-500/80 mt-1"></i>
                            <div>
                                <p class="text-[10px] text-slate-300">أداة الذكاء الاصطناعي استخرجت <span class="text-white font-bold">{{ $log->total_mods_extracted }}</span> مود من فيديو يوتيوب</p>
                                <p class="text-[9px] text-slate-500 mt-0.5">{{ $log->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- ModPacks Views Chart (Simple CSS Bar Chart) --}}
                <div class="glass-card rounded-2xl border border-slate-800 p-6 space-y-4">
                    <h3 class="font-bold text-white text-sm flex items-center gap-2">
                        <i class="fa-solid fa-chart-line text-emerald-400"></i>
                        التجميعات الأكثر زيارة
                    </h3>
                    <div class="space-y-3 mt-4">
                        @php
                            $topPacks = collect($modPacks)->sortByDesc('views_count')->take(5);
                            $maxViews = $topPacks->max('views_count') ?: 1;
                        @endphp
                        @foreach($topPacks as $tp)
                        <div>
                            <div class="flex justify-between text-[10px] mb-1">
                                <span class="text-slate-300 truncate max-w-[200px]">{{ $tp->title_en }}</span>
                                <span class="text-emerald-400 font-bold">{{ number_format($tp->views_count) }} زيارة</span>
                            </div>
                            <div class="w-full bg-slate-900 rounded-full h-1.5">
                                <div class="bg-gradient-to-r from-emerald-500 to-teal-400 h-1.5 rounded-full" style="width: {{ ($tp->views_count / $maxViews) * 100 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ── Nexus Mods Import Panel ──────────────────────────────── --}}
            <div class="glass-card rounded-2xl border border-slate-800 p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-white text-sm flex items-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-down text-orange-400"></i>
                        استيراد مود من Nexus Mods
                    </h3>
                    <form method="POST" action="{{ route('admin.nexus.sync') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition">
                            <i class="fa-solid fa-rotate text-blue-400"></i>
                            مزامنة كل المودات
                        </button>
                    </form>
                </div>
                <p class="text-xs text-slate-500">الصق رابط مود من Nexus Mods وسيتم استيراد الاسم والصورة والإصدار والاعتماديات تلقائياً.</p>
                <form method="POST" action="{{ route('admin.nexus.import') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    @csrf
                    <input type="url" name="nexus_url" placeholder="https://www.nexusmods.com/skyrim/mods/12345"
                           required
                           class="md:col-span-2 px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 text-xs placeholder-slate-600 focus:outline-none focus:border-orange-500">
                    <select name="game_id" required class="px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-300 text-xs focus:outline-none focus:border-orange-500">
                        <option value="">اختر اللعبة</option>
                        @foreach(\App\Models\Game::orderBy('name')->get() as $g)
                            <option value="{{ $g->id }}">{{ $g->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="md:col-span-3 px-5 py-2.5 rounded-xl bg-gradient-to-r from-orange-600 to-amber-500 hover:from-orange-500 hover:to-amber-400 text-white font-bold text-xs transition">
                        <i class="fa-solid fa-cloud-arrow-down mr-1"></i>
                        استيراد المود
                    </button>
                </form>
                @if($errors->has('nexus_url'))
                    <p class="text-xs text-red-400">{{ $errors->first('nexus_url') }}</p>
                @endif
            </div>

            {{-- ── Ad Performance Panel ─────────────────────────────────── --}}
            @php
                $adStats = \App\Models\AdSlot::select('id','name','impressions','clicks','is_active')->get();
            @endphp
            @if($adStats->count())
            <div class="glass-card rounded-2xl border border-slate-800 p-6 space-y-4">
                <h3 class="font-bold text-white text-sm flex items-center gap-2">
                    <i class="fa-solid fa-chart-bar text-violet-400"></i>
                    أداء الإعلانات (Ad Performance)
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-500 text-right">
                                <th class="py-2 px-3">الإعلان</th>
                                <th class="py-2 px-3">المشاهدات</th>
                                <th class="py-2 px-3">النقرات</th>
                                <th class="py-2 px-3">CTR%</th>
                                <th class="py-2 px-3">الحالة</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @foreach($adStats as $ad)
                            <tr class="text-right">
                                <td class="py-2 px-3 text-slate-200 font-bold">{{ $ad->name }}</td>
                                <td class="py-2 px-3 text-slate-400">{{ number_format($ad->impressions) }}</td>
                                <td class="py-2 px-3 text-slate-400">{{ number_format($ad->clicks) }}</td>
                                <td class="py-2 px-3 text-violet-400 font-mono">
                                    {{ $ad->impressions > 0 ? round(($ad->clicks / $ad->impressions) * 100, 2) : 0 }}%
                                </td>
                                <td class="py-2 px-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $ad->is_active ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-slate-800 text-slate-500' }}">
                                        {{ $ad->is_active ? 'نشط' : 'متوقف' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>

        {{-- ============================================= --}}
        {{-- TAB 2: GAMES --}}
        {{-- ============================================= --}}
        <div id="admin-panel-games" class="admin-tab-panel hidden space-y-6">
            {{-- RAWG Auto-Fetch --}}
            <div class="glass-card rounded-2xl border border-slate-800 p-5">
                <h4 class="font-bold text-white text-sm mb-4"><i class="fa-solid fa-cloud-arrow-down text-blue-500 ml-2"></i>جلب لعبة من RAWG API</h4>
                <form action="{{ route('admin.games.store') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                    @csrf
                    <input type="text" name="name" placeholder="اسم اللعبة بالإنجليزية (مثال: Skyrim)" required class="flex-1 bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-violet-600">
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-violet-600 to-blue-500 hover:from-violet-500 hover:to-blue-400 rounded-xl text-white text-xs font-bold transition-all shadow-lg shadow-violet-500/20"><i class="fa-solid fa-download ml-1"></i> جلب وإضافة</button>
                </form>
            </div>

            {{-- Manual Game Add --}}
            <div class="glass-card rounded-2xl border border-emerald-500/20 p-5">
                <h4 class="font-bold text-white text-sm mb-4"><i class="fa-solid fa-plus-circle text-emerald-500 ml-2"></i>إضافة لعبة يدوياً (Manual)</h4>
                <form action="{{ route('admin.games.store-manual') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] text-slate-400 font-bold block mb-1">اسم اللعبة *</label>
                            <input type="text" name="name" required placeholder="مثال: Skyrim Special Edition" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-violet-600">
                        </div>
                        <div>
                            <label class="text-[10px] text-slate-400 font-bold block mb-1">Slug *</label>
                            <input type="text" name="slug" required placeholder="skyrim-special-edition" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-violet-600 font-mono">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] text-slate-400 font-bold block mb-1">الوصف</label>
                        <textarea name="description" rows="2" placeholder="وصف اللعبة..." class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-violet-600"></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] text-slate-400 font-bold block mb-1">صورة الغلاف (رفع ملف)</label>
                            <input type="file" name="thumbnail" accept="image/*" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-400 file:mr-3 file:px-3 file:py-1 file:rounded-lg file:border-0 file:bg-violet-600 file:text-white file:font-bold file:text-[10px] file:cursor-pointer focus:outline-none">
                        </div>
                        <div>
                            <label class="text-[10px] text-slate-400 font-bold block mb-1">Nexus Domain</label>
                            <input type="text" name="nexus_domain" placeholder="skyrimspecialedition" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-orange-300 placeholder-slate-600 font-mono focus:outline-none focus:border-orange-500">
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-500 hover:to-teal-400 rounded-xl text-white text-xs font-bold transition-all shadow-lg shadow-emerald-500/20"><i class="fa-solid fa-plus ml-1"></i> إضافة يدوياً</button>
                    </div>
                </form>
            </div>

            {{-- Manual Game Version Add --}}
            <div class="glass-card rounded-2xl border border-blue-500/20 p-5">
                <h4 class="font-bold text-white text-sm mb-4"><i class="fa-solid fa-code-branch text-blue-400 ml-2"></i>إضافة إصدار للعبة يدوياً (Manual Game Version Add)</h4>
                <form action="{{ route('admin.game-versions.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    @csrf
                    <div>
                        <label class="text-[10px] text-slate-400 font-bold block mb-1">اختر اللعبة *</label>
                        <select name="game_id" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-blue-500">
                            <option value="">اختر اللعبة</option>
                            @foreach($games as $g)
                                <option value="{{ $g->id }}">{{ $g->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] text-slate-400 font-bold block mb-1">رقم/اسم الإصدار *</label>
                        <input type="text" name="version" required placeholder="مثال: 1.6.1170 أو v2.0" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 font-mono">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full py-2 bg-gradient-to-r from-blue-600 to-indigo-500 hover:from-blue-500 hover:to-indigo-400 rounded-xl text-white text-xs font-bold transition-all shadow-lg shadow-blue-500/20">
                            <i class="fa-solid fa-plus ml-1"></i> إضافة الإصدار
                        </button>
                    </div>
                </form>
            </div>

            <div class="glass-card rounded-2xl border border-slate-800 overflow-hidden">
                <div class="p-4 border-b border-slate-800"><h4 class="font-bold text-white text-sm">الألعاب المسجلة ({{ $games->count() }})</h4></div>
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead class="bg-slate-950/60 text-slate-400 border-b border-slate-800"><tr><th class="px-5 py-3">الصورة</th><th class="px-5 py-3">الاسم</th><th class="px-5 py-3">Slug</th><th class="px-5 py-3">التحديثات / الإصدارات</th><th class="px-5 py-3 text-left">التحكم</th></tr></thead>
                        <tbody class="divide-y divide-slate-800/50">
                            @forelse($games as $game)
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="px-5 py-3"><div class="w-10 h-10 rounded-lg overflow-hidden bg-slate-900 border border-slate-800">@if($game->thumbnail)<img src="{{ $game->thumbnail }}" class="w-full h-full object-cover">@else<div class="w-full h-full flex items-center justify-center text-slate-600"><i class="fa-solid fa-gamepad"></i></div>@endif</div></td>
                                <td class="px-5 py-3 font-bold text-white">{{ $game->name }}</td>
                                <td class="px-5 py-3 text-slate-500 font-mono text-[10px]">{{ $game->slug }}</td>
                                <td class="px-5 py-3">
                                    <div class="flex flex-wrap items-center gap-1">
                                        @forelse($game->versions as $gv)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-500/10 text-blue-400 border border-blue-500/20 rounded-md font-mono text-[10px]">
                                                {{ $gv->version }}
                                                <form action="{{ route('admin.game-versions.delete', $gv) }}" method="POST" class="inline" onsubmit="return confirm('هل تريد حذف الإصدار {{ $gv->version }}؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-slate-400 hover:text-red-400 font-bold text-[11px] leading-none px-0.5">&times;</button>
                                                </form>
                                            </span>
                                        @empty
                                            <span class="text-slate-600 text-[10px]">لا يوجد إصدارات</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-left"><div class="flex items-center gap-2 justify-end">
                                    <button onclick="toggleEditModal('game-{{ $game->id }}')" class="text-violet-400 hover:text-violet-300 font-bold text-[10px]">تعديل / إدارة الإصدارات</button>
                                    <form action="{{ route('admin.games.delete', $game) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد؟')">@csrf @method('DELETE')<button type="submit" class="text-red-500 hover:text-red-400 font-bold text-[10px]">حذف</button></form>
                                </div></td>
                            </tr>
                            <div id="edit-modal-game-{{ $game->id }}" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
                                <div class="glass-card p-6 rounded-2xl border border-slate-800 max-w-md w-full space-y-4">
                                    <div class="flex justify-between items-center"><h4 class="font-bold text-white text-sm">تعديل: {{ $game->name }}</h4><button onclick="toggleEditModal('game-{{ $game->id }}')" class="text-slate-500 hover:text-white text-lg">&times;</button></div>
                                    <form action="{{ route('admin.games.update', $game) }}" method="POST" enctype="multipart/form-data" class="space-y-3 text-xs">
                                        @csrf
                                        @method('PUT')
                                        <div><label class="text-slate-400 font-bold block mb-1">الاسم</label><input type="text" name="name" value="{{ $game->name }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-violet-600" required></div>
                                        <div><label class="text-slate-400 font-bold block mb-1">Slug</label><input type="text" name="slug" value="{{ $game->slug }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-violet-600" required></div>
                                        <div><label class="text-slate-400 font-bold block mb-1">الوصف</label><textarea name="description" rows="3" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-violet-600">{{ $game->description }}</textarea></div>
                                        <div><label class="text-slate-400 font-bold block mb-1">رابط الصورة (URL)</label><input type="text" name="thumbnail" value="{{ $game->thumbnail }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-violet-600"></div>
                                        <div><label class="text-slate-400 font-bold block mb-1">أو رفع صورة جديدة</label><input type="file" name="thumbnail_file" accept="image/*" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-slate-400 file:mr-3 file:px-3 file:py-1 file:rounded-lg file:border-0 file:bg-violet-600 file:text-white file:font-bold file:text-[10px] file:cursor-pointer focus:outline-none"></div>
                                        <div><label class="text-slate-400 font-bold block mb-1">Nexus Domain</label><input type="text" name="nexus_domain" value="{{ $game->nexus_domain }}" placeholder="skyrimspecialedition" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-orange-300 font-mono focus:outline-none focus:border-orange-500"></div>
                                        <div class="flex justify-end gap-2 pt-2"><button type="submit" class="px-5 py-2 bg-violet-600 hover:bg-violet-500 rounded-xl text-white font-bold transition-all">حفظ البيانات الأساسية</button><button type="button" onclick="toggleEditModal('game-{{ $game->id }}')" class="px-5 py-2 bg-slate-800 hover:bg-slate-700 rounded-xl text-slate-400 font-bold transition-all">إلغاء</button></div>
                                    </form>

                                    {{-- Inline Game Version Management in Modal --}}
                                    <div class="border-t border-slate-800 pt-3 mt-3 space-y-2 text-xs">
                                        <label class="text-slate-300 font-bold block">إدارة إصدارات اللعبة المسجلة:</label>
                                        <div class="flex flex-wrap gap-1 max-h-28 overflow-y-auto p-2 bg-slate-900/60 rounded-xl border border-slate-800">
                                            @forelse($game->versions as $gv)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-slate-800 border border-slate-700 text-blue-300 rounded font-mono text-[10px]">
                                                    {{ $gv->version }}
                                                    <form action="{{ route('admin.game-versions.delete', $gv) }}" method="POST" class="inline" onsubmit="return confirm('حذف الإصدار {{ $gv->version }}؟')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-slate-400 hover:text-red-400 font-bold ml-1">&times;</button>
                                                    </form>
                                                </span>
                                            @empty
                                                <span class="text-slate-500 text-[10px]">لا توجد إصدارات حالياً.</span>
                                            @endforelse
                                        </div>
                                        <form action="{{ route('admin.game-versions.store') }}" method="POST" class="flex gap-2 pt-1">
                                            @csrf
                                            <input type="hidden" name="game_id" value="{{ $game->id }}">
                                            <input type="text" name="version" placeholder="إصدار جديد (مثال: 1.6.640)" required class="flex-1 bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none focus:border-blue-500 font-mono">
                                            <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-500 rounded-xl text-white font-bold text-xs transition">+ إضافة</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr><td colspan="5" class="px-5 py-10 text-center text-slate-500">لا توجد ألعاب مسجلة.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>




        {{-- ============================================= --}}
        {{-- TAB 3: MODPACKS --}}
        {{-- ============================================= --}}
        <div id="admin-panel-modpacks" class="admin-tab-panel hidden space-y-6">
            <div class="glass-card rounded-2xl border border-slate-800 overflow-hidden">
                <div class="p-4 border-b border-slate-800"><h4 class="font-bold text-white text-sm"><i class="fa-solid fa-boxes-stacked text-emerald-500 ml-2"></i>التجميعات ({{ $modPacks->count() }})</h4></div>
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead class="bg-slate-950/60 text-slate-400 border-b border-slate-800"><tr><th class="px-5 py-3">العنوان (EN)</th><th class="px-5 py-3">العنوان (AR)</th><th class="px-5 py-3">اللعبة</th><th class="px-5 py-3">الحالة</th><th class="px-5 py-3">المشاهدات</th><th class="px-5 py-3 text-left">التحكم</th></tr></thead>
                        <tbody class="divide-y divide-slate-800/50">
                            @forelse($modPacks as $mp)
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="px-5 py-3 font-bold text-white max-w-[200px] truncate">{{ $mp->title_en }}</td>
                                <td class="px-5 py-3 text-slate-400 max-w-[200px] truncate">{{ $mp->title_ar }}</td>
                                <td class="px-5 py-3 text-slate-400">{{ $mp->gameVersions->first()?->game?->name ?? '—' }}</td>
                                <td class="px-5 py-3">@if($mp->is_published)<span class="px-2 py-1 bg-emerald-500/10 text-emerald-400 rounded-lg font-bold text-[10px]">منشور</span>@else<span class="px-2 py-1 bg-amber-500/10 text-amber-400 rounded-lg font-bold text-[10px]">مسودة</span>@endif</td>
                                <td class="px-5 py-3 font-mono text-slate-400">{{ $mp->views_count }}</td>
                                <td class="px-5 py-3 text-left"><div class="flex items-center gap-2 justify-end flex-wrap">
                                    @if(!$mp->is_published)<form action="{{ route('admin.modpacks.publish', $mp) }}" method="POST" class="inline">@csrf<button type="submit" class="text-emerald-400 hover:text-emerald-300 font-bold text-[10px]">نشر</button></form>@endif
                                    <button onclick="toggleEditModal('mp-{{ $mp->id }}')" class="text-violet-400 hover:text-violet-300 font-bold text-[10px]">تعديل</button>
                                    <form action="{{ route('admin.modpacks.delete', $mp) }}" method="POST" class="inline" onsubmit="return confirm('حذف؟')">@csrf @method('DELETE')<button type="submit" class="text-red-500 hover:text-red-400 font-bold text-[10px]">حذف</button></form>
                                </div></td>
                            </tr>
                            <div id="edit-modal-mp-{{ $mp->id }}" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4"><div class="glass-card p-6 rounded-2xl border border-slate-800 max-w-lg w-full space-y-4 max-h-[80vh] overflow-y-auto"><div class="flex justify-between items-center"><h4 class="font-bold text-white text-sm">تعديل التجميعة</h4><button onclick="toggleEditModal('mp-{{ $mp->id }}')" class="text-slate-500 hover:text-white text-lg">&times;</button></div><form action="{{ route('admin.modpacks.update', $mp) }}" method="POST" class="space-y-3 text-xs">@csrf @method('PUT')<div><label class="text-slate-400 font-bold block mb-1">العنوان (EN)</label><input type="text" name="title_en" value="{{ $mp->title_en }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-violet-600" required></div><div><label class="text-slate-400 font-bold block mb-1">العنوان (AR)</label><input type="text" name="title_ar" value="{{ $mp->title_ar }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-violet-600" required></div><div><label class="text-slate-400 font-bold block mb-1">الوصف (EN)</label><textarea name="description_en" rows="2" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-violet-600">{{ $mp->description_en }}</textarea></div><div><label class="text-slate-400 font-bold block mb-1">الوصف (AR)</label><textarea name="description_ar" rows="2" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-violet-600">{{ $mp->description_ar }}</textarea></div><div class="grid grid-cols-3 gap-3"><div><label class="text-slate-400 font-bold block mb-1">المشاهدات</label><input type="number" name="views_count" value="{{ $mp->views_count }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-violet-600" required></div><div><label class="text-slate-400 font-bold block mb-1">إعجاب</label><input type="number" name="upvotes" value="{{ $mp->upvotes }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-violet-600" required></div><div><label class="text-slate-400 font-bold block mb-1">عدم إعجاب</label><input type="number" name="downvotes" value="{{ $mp->downvotes }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-violet-600" required></div></div><div><label class="text-slate-400 font-bold block mb-1">YouTube ID</label><input type="text" name="youtube_video_id" value="{{ $mp->youtube_video_id }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-violet-600"></div><div class="flex justify-end gap-2 pt-2"><button type="submit" class="px-5 py-2 bg-violet-600 hover:bg-violet-500 rounded-xl text-white font-bold transition-all">حفظ</button><button type="button" onclick="toggleEditModal('mp-{{ $mp->id }}')" class="px-5 py-2 bg-slate-800 hover:bg-slate-700 rounded-xl text-slate-400 font-bold transition-all">إلغاء</button></div></form></div></div>
                            @empty
                            <tr><td colspan="6" class="px-5 py-10 text-center text-slate-500">لا توجد تجميعات.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ============================================= --}}
        {{-- TAB 4: MODS LIBRARY --}}
        {{-- ============================================= --}}
        <div id="admin-panel-mods" class="admin-tab-panel hidden space-y-6">
            <div class="glass-card rounded-2xl border border-slate-800 p-5 flex flex-col md:flex-row gap-4 items-center justify-between">
                <div><h4 class="font-bold text-white text-sm"><i class="fa-solid fa-cube text-cyan-500 ml-2"></i>مكتبة المودات الشاملة</h4><p class="text-[10px] text-slate-500 mt-1">إدارة كافة المودات وتحديث روابطها وصورها.</p></div>
                <div class="flex items-center gap-3 w-full md:w-auto overflow-x-auto">
                    <a href="{{ route('admin.mods.create') }}" class="px-4 py-2 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 rounded-xl text-white text-xs font-bold transition-all shadow-md flex items-center gap-1.5 shrink-0">
                        <i class="fa-solid fa-plus-circle"></i> إضافة مود يدوياً
                    </a>
                    <form action="{{ route('admin.enrich') }}" method="POST" class="inline shrink-0">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 rounded-xl text-white text-xs font-bold transition-all shadow-md">
                            <i class="fa-solid fa-sync mr-1 rtl:ml-1"></i> جلب الصور والبيانات تلقائياً
                        </button>
                    </form>
                    <input type="text" id="admin-mod-search" onkeyup="filterModsTable()" placeholder="ابحث باسم المود..." class="bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-violet-600 w-full md:w-64 shrink-0">
                </div>
            </div>

            <!-- Direct Nexus Mods Search Panel -->
            <div class="glass-card rounded-2xl border border-slate-850 p-5 space-y-4 bg-slate-950/20">
                <div class="flex items-center justify-between border-b border-slate-900 pb-3">
                    <h5 class="text-xs font-bold text-slate-300 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass text-orange-500"></i>
                        البحث المباشر والإضافة السريعة من Nexus Mods
                    </h5>
                    <span class="text-[9px] px-2 py-0.5 rounded-full bg-orange-500/10 text-orange-400 font-bold border border-orange-500/25">بدون يوتيوب</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 block mb-1">اختر اللعبة</label>
                        <select id="nexus-search-game" onchange="onNexusGameChange()" class="w-full bg-slate-900 border border-slate-850 rounded-xl px-3 py-2 text-xs text-slate-350 focus:outline-none focus:border-violet-600">
                            @foreach($games as $game)
                                <option value="{{ $game->id }}">{{ $game->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-slate-500 block mb-1">اسم المود للبحث <span class="text-slate-600">(اتركه فارغاً للمودات الشائعة)</span></label>
                        <input type="text" id="nexus-search-query" placeholder="مثال: SkyUI" class="w-full bg-slate-900 border border-slate-850 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-violet-600">
                    </div>

                    <button type="button" onclick="performNexusSearch()" class="py-2 px-5 bg-orange-600 hover:bg-orange-500 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-orange-500/10 flex items-center justify-center gap-1.5 h-[34px]">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        بحث في Nexus Mods
                    </button>
                </div>

                <!-- Nexus Search Results Spinner -->
                <div id="nexus-search-spinner" class="hidden py-8 text-center text-xs text-slate-500 space-y-2">
                    <i class="fa-solid fa-spinner animate-spin text-lg text-orange-500"></i>
                    <p class="animate-pulse">جاري جلب نتائج البحث من Nexus Mods عبر وكيل آمن...</p>
                </div>

                <!-- Search Results Grid -->
                <div id="nexus-search-results" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"></div>
            </div>

            <div class="glass-card rounded-2xl border border-slate-800 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead class="bg-slate-950/60 text-slate-400 border-b border-slate-800"><tr><th class="px-5 py-3">الصورة</th><th class="px-5 py-3">اسم المود</th><th class="px-5 py-3">اللعبة</th><th class="px-5 py-3">الترتيب</th><th class="px-5 py-3">الروابط</th><th class="px-5 py-3 text-left">التحكم</th></tr></thead>
                        <tbody class="divide-y divide-slate-800/50" id="mods-table-body">
                            @forelse($modsList ?? [] as $m)
                            <tr class="mod-row hover:bg-slate-800/30 transition-colors" data-name="{{ strtolower($m->name) }}">
                                <td class="px-5 py-3">
                                    <div class="w-10 h-10 rounded-lg overflow-hidden bg-slate-900 border border-slate-800 flex items-center justify-center">
                                        @if($m->local_image_path || $m->image_url)<img src="{{ $m->local_image_path ?: $m->image_url }}" class="w-full h-full object-cover">@else<i class="fa-solid fa-cube text-slate-600 text-sm"></i>@endif
                                    </div>
                                </td>
                                <td class="px-5 py-3 font-bold text-white">
                                    <div class="flex items-center gap-2">
                                        {{ $m->name }}
                                        @if($m->has_issues)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-amber-500/15 border border-amber-500/40 text-amber-400 rounded-lg text-[9px] font-bold" title="{{ $m->issues_note ?? 'يحتوي على مشكلة' }}">
                                            <i class="fa-solid fa-triangle-exclamation"></i> مشكلة
                                        </span>
                                        @endif
                                    </div>
                                    <div class="text-[9px] text-slate-600 font-mono">{{ $m->slug }}</div>
                                </td>
                                <td class="px-5 py-3 text-slate-400">{{ $m->game->name ?? '—' }}</td>
                                <td class="px-5 py-3 font-mono font-bold text-violet-400">{{ $m->load_order }}</td>
                                <td class="px-5 py-3"><div class="flex items-center gap-2 text-[10px]">@if($m->nexus_url)<a href="{{ $m->nexus_url }}" target="_blank" class="text-orange-400 hover:underline">Nexus</a>@endif @if($m->steam_url)<a href="{{ $m->steam_url }}" target="_blank" class="text-blue-400 hover:underline">Steam</a>@endif</div></td>
                                <td class="px-5 py-3 text-left">
                                    <div class="flex items-center gap-2 justify-end">
                                        <button onclick="toggleFlagMod({{ $m->id }}, {{ $m->has_issues ? 'true' : 'false' }}, this)" class="text-[10px] font-bold transition-colors {{ $m->has_issues ? 'text-amber-400 hover:text-slate-400' : 'text-slate-500 hover:text-amber-400' }}" title="{{ $m->has_issues ? 'إزالة علامة المشكلة' : 'تعليم كمشكلة' }}">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                        </button>
                                        <button onclick="toggleEditModal('mod-{{ $m->id }}')" class="text-violet-400 hover:text-violet-300 font-bold text-[10px]">تعديل</button>
                                        <form action="{{ route('admin.mods.delete', $m->id) }}" method="POST" class="inline" onsubmit="return confirm('حذف؟')">@csrf @method('DELETE')<button type="submit" class="text-red-500 hover:text-red-400 font-bold text-[10px]">حذف</button></form>
                                    </div>
                                </td>
                            </tr>
                            <div id="edit-modal-mod-{{ $m->id }}" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4"><div class="glass-card p-6 rounded-2xl border border-slate-800 max-w-md w-full space-y-4"><div class="flex justify-between items-center"><h4 class="font-bold text-white text-sm">تعديل: {{ $m->name }}</h4><button onclick="toggleEditModal('mod-{{ $m->id }}')" class="text-slate-500 hover:text-white text-lg">&times;</button></div><form action="{{ route('admin.mods.update', $m->id) }}" method="POST" class="space-y-3 text-xs">@csrf @method('PUT')<div><label class="text-slate-400 font-bold block mb-1">اسم المود</label><input type="text" name="name" value="{{ $m->name }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-violet-600" required></div><div class="grid grid-cols-2 gap-3"><div><label class="text-slate-400 font-bold block mb-1">ترتيب التحميل</label><input type="number" name="load_order" value="{{ $m->load_order }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-violet-600" required></div><div><label class="text-slate-400 font-bold block mb-1">رابط الصورة</label><input type="text" name="image_url" value="{{ $m->image_url }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-violet-600"></div></div><div><label class="text-slate-400 font-bold block mb-1">Nexus</label><input type="text" name="nexus_url" value="{{ $m->nexus_url }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-violet-600"></div><div><label class="text-slate-400 font-bold block mb-1">Steam</label><input type="text" name="steam_url" value="{{ $m->steam_url }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-violet-600"></div><div><label class="text-slate-400 font-bold block mb-1">تحميل مباشر</label><input type="text" name="download_url" value="{{ $m->download_url }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-violet-600"></div><div class="flex justify-end gap-2 pt-2"><button type="submit" class="px-5 py-2 bg-violet-600 hover:bg-violet-500 rounded-xl text-white font-bold transition-all">حفظ</button><button type="button" onclick="toggleEditModal('mod-{{ $m->id }}')" class="px-5 py-2 bg-slate-800 hover:bg-slate-700 rounded-xl text-slate-400 font-bold transition-all">إلغاء</button></div></form></div></div>
                            @empty
                            <tr><td colspan="6" class="px-5 py-10 text-center text-slate-500">لا توجد مودات حالياً.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($modsList instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $modsList->hasPages())
                <div class="px-5 py-4 border-t border-slate-900/60 bg-slate-950/20 flex items-center justify-between gap-4">
                    <div class="text-[10px] text-slate-500">
                        عرض المودات {{ $modsList->firstItem() }} إلى {{ $modsList->lastItem() }} من إجمالي {{ $modsList->total() }} مود
                    </div>
                    <div class="flex items-center gap-1.5">
                        {{-- Previous Page Link --}}
                        @if ($modsList->onFirstPage())
                            <span class="px-2.5 py-1.5 bg-slate-900 border border-slate-850 text-slate-600 rounded-lg text-[10px] cursor-not-allowed">السابق</span>
                        @else
                            <a href="{{ $modsList->previousPageUrl() }}" class="px-2.5 py-1.5 bg-slate-900 hover:bg-slate-850 border border-slate-800 text-slate-300 rounded-lg text-[10px] transition-colors">السابق</a>
                        @endif

                        {{-- Next Page Link --}}
                        @if ($modsList->hasMorePages())
                            <a href="{{ $modsList->nextPageUrl() }}" class="px-2.5 py-1.5 bg-slate-900 hover:bg-slate-850 border border-slate-800 text-slate-300 rounded-lg text-[10px] transition-colors">التالي</a>
                        @else
                            <span class="px-2.5 py-1.5 bg-slate-900 border border-slate-850 text-slate-600 rounded-lg text-[10px] cursor-not-allowed">التالي</span>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- ============================================= --}}
        {{-- TAB 5: USERS --}}
        {{-- ============================================= --}}
        <div id="admin-panel-users" class="admin-tab-panel hidden space-y-6">
            <div class="glass-card rounded-2xl border border-slate-800 overflow-hidden">
                <div class="p-4 border-b border-slate-800"><h4 class="font-bold text-white text-sm"><i class="fa-solid fa-users text-amber-500 ml-2"></i>الأعضاء ({{ $users->count() }})</h4></div>
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead class="bg-slate-950/60 text-slate-400 border-b border-slate-800"><tr><th class="px-5 py-3">الاسم</th><th class="px-5 py-3">البريد</th><th class="px-5 py-3">الصلاحية</th><th class="px-5 py-3">التعليقات</th><th class="px-5 py-3 text-left">التحكم</th></tr></thead>
                        <tbody class="divide-y divide-slate-800/50">
                            @forelse($users as $user)
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="px-5 py-3 font-bold text-white">{{ $user->name }}</td>
                                <td class="px-5 py-3 text-slate-400 font-mono text-[10px]">{{ $user->email }}</td>
                                <td class="px-5 py-3">@if($user->is_admin)<span class="px-2 py-1 bg-violet-500/10 text-violet-400 rounded-lg font-bold text-[10px]">أدمن</span>@else<span class="px-2 py-1 bg-slate-500/10 text-slate-400 rounded-lg font-bold text-[10px]">عضو</span>@endif</td>
                                <td class="px-5 py-3 font-mono text-slate-400">{{ $user->comments_count }}</td>
                                <td class="px-5 py-3 text-left"><div class="flex items-center gap-2 justify-end">
                                    <button onclick="toggleEditModal('user-{{ $user->id }}')" class="text-violet-400 hover:text-violet-300 font-bold text-[10px]">تعديل</button>
                                    @if($user->id !== auth()->id())<form action="{{ route('admin.users.delete', $user) }}" method="POST" class="inline" onsubmit="return confirm('حذف؟')">@csrf @method('DELETE')<button type="submit" class="text-red-500 hover:text-red-400 font-bold text-[10px]">حذف</button></form>@endif
                                </div></td>
                            </tr>
                            <div id="edit-modal-user-{{ $user->id }}" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4"><div class="glass-card p-6 rounded-2xl border border-slate-800 max-w-md w-full space-y-4"><div class="flex justify-between items-center"><h4 class="font-bold text-white text-sm">تعديل: {{ $user->name }}</h4><button onclick="toggleEditModal('user-{{ $user->id }}')" class="text-slate-500 hover:text-white text-lg">&times;</button></div><form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-3 text-xs">@csrf @method('PUT')<div><label class="text-slate-400 font-bold block mb-1">الاسم</label><input type="text" name="name" value="{{ $user->name }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-violet-600" required></div><div><label class="text-slate-400 font-bold block mb-1">البريد</label><input type="email" name="email" value="{{ $user->email }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-violet-600" required></div><div><label class="text-slate-400 font-bold block mb-1">الصلاحية</label><select name="is_admin" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-violet-600"><option value="0" {{ !$user->is_admin ? 'selected' : '' }}>عضو</option><option value="1" {{ $user->is_admin ? 'selected' : '' }}>أدمن</option></select></div><div><label class="text-slate-400 font-bold block mb-1">النبذة</label><textarea name="bio" rows="2" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-violet-600">{{ $user->profile?->bio }}</textarea></div><input type="hidden" name="phone" value="{{ $user->profile?->phone ?? '-' }}"><input type="hidden" name="address" value="{{ $user->profile?->address ?? '-' }}"><div class="flex justify-end gap-2 pt-2"><button type="submit" class="px-5 py-2 bg-violet-600 hover:bg-violet-500 rounded-xl text-white font-bold transition-all">حفظ</button><button type="button" onclick="toggleEditModal('user-{{ $user->id }}')" class="px-5 py-2 bg-slate-800 hover:bg-slate-700 rounded-xl text-slate-400 font-bold transition-all">إلغاء</button></div></form></div></div>
                            @empty
                            <tr><td colspan="5" class="px-5 py-10 text-center text-slate-500">لا يوجد أعضاء.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ============================================= --}}
        {{-- TAB 6: COMMENTS --}}
        {{-- ============================================= --}}
        <div id="admin-panel-comments" class="admin-tab-panel hidden space-y-6">
            <div class="glass-card rounded-2xl border border-slate-800 overflow-hidden">
                <div class="p-4 border-b border-slate-800"><h4 class="font-bold text-white text-sm"><i class="fa-solid fa-comments text-rose-500 ml-2"></i>التعليقات ({{ $comments->count() }})</h4></div>
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead class="bg-slate-950/60 text-slate-400 border-b border-slate-800"><tr><th class="px-5 py-3">الكاتب</th><th class="px-5 py-3">المحتوى</th><th class="px-5 py-3">التجميعة</th><th class="px-5 py-3">التاريخ</th><th class="px-5 py-3 text-left">التحكم</th></tr></thead>
                        <tbody class="divide-y divide-slate-800/50">
                            @forelse($comments as $comment)
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="px-5 py-3 font-bold text-white">{{ $comment->user?->name ?? 'مجهول' }}</td>
                                <td class="px-5 py-3 text-slate-400 max-w-[300px] truncate">{{ $comment->content }}</td>
                                <td class="px-5 py-3 text-slate-500 text-[10px]">{{ $comment->modPack?->title_en ?? '—' }}</td>
                                <td class="px-5 py-3 text-slate-500 text-[10px]">{{ $comment->created_at?->diffForHumans() }}</td>
                                <td class="px-5 py-3 text-left"><form action="{{ route('admin.comments.delete', $comment) }}" method="POST" class="inline" onsubmit="return confirm('حذف؟')">@csrf @method('DELETE')<button type="submit" class="text-red-500 hover:text-red-400 font-bold text-[10px]">حذف</button></form></td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-5 py-10 text-center text-slate-500">لا توجد تعليقات.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ============================================= --}}
        {{-- TAB 7: AI HUB --}}
        {{-- ============================================= --}}
        <div id="admin-panel-ai-hub" class="admin-tab-panel hidden space-y-6">
            <div class="glass-card rounded-2xl border border-slate-800 p-6 space-y-5">
                <h4 class="font-bold text-white text-sm"><i class="fa-solid fa-robot text-violet-500 ml-2"></i>مركز الأتمتة والذكاء الاصطناعي</h4>
                <p class="text-[10px] text-slate-500">ابحث عن فيديوهات YouTube لاستخراج قوائم المودات تلقائياً.</p>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div><label class="text-[10px] text-slate-400 font-bold block mb-1">اللعبة</label><select id="ai-game-id" onchange="updateQueryPrefill()" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-violet-600">@foreach($games as $game)<option value="{{ $game->id }}" data-name="{{ $game->name }}">{{ $game->name }}</option>@endforeach</select></div>
                    <div><label class="text-[10px] text-slate-400 font-bold block mb-1">النطاق الزمني</label><select id="ai-time-range" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-violet-600"><option value="month">شهر</option><option value="year" selected>سنة</option><option value="3years">3 سنوات</option><option value="all">الكل</option></select></div>
                    <div><label class="text-[10px] text-slate-400 font-bold block mb-1">العدد</label><select id="ai-limit" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-violet-600"><option value="5">5</option><option value="10" selected>10</option><option value="20">20</option></select></div>
                    <div><label class="text-[10px] text-slate-400 font-bold block mb-1">استعلام البحث</label><input type="text" id="ai-query-string" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-violet-600"></div>
                </div>
                <button onclick="searchYoutubeVideos()" class="px-6 py-2.5 bg-gradient-to-r from-violet-600 to-blue-500 hover:from-violet-500 hover:to-blue-400 rounded-xl text-white text-xs font-bold transition-all shadow-lg shadow-violet-500/20"><i class="fa-brands fa-youtube ml-1"></i> بحث في يوتيوب</button>
            </div>
            <div id="ai-results-section" class="hidden space-y-4">
                <h4 class="font-bold text-white text-sm">نتائج البحث</h4>
                <div id="youtube-videos-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"></div>
            </div>
        </div>

        {{-- ============================================= --}}
        {{-- TAB 8: CONFLICTS METRICS --}}
        {{-- ============================================= --}}
        <div id="admin-panel-conflicts-metrics" class="admin-tab-panel hidden space-y-6">
            <div class="glass-card rounded-2xl border border-slate-800 p-5 flex flex-col md:flex-row gap-4 items-center justify-between">
                <div>
                    <h4 class="font-bold text-white text-sm">
                        <i class="fa-solid fa-triangle-exclamation text-amber-500 ml-2"></i>إحصائيات المودات الأكثر تعارضاً (Most Conflicted Mods)
                    </h4>
                    <p class="text-[10px] text-slate-500 mt-1">عرض قائمة المودات التي تمتلك أكبر عدد من التعارضات المسجلة في النظام.</p>
                </div>
                <!-- Game Filter -->
                <div>
                    <select id="conflict-metrics-game-filter" onchange="filterConflictsByGame(this.value)" class="bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-violet-600 w-56">
                        <option value="all">كل الألعاب</option>
                        @foreach($games as $game)
                            <option value="{{ $game->id }}">{{ $game->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Metrics Table Grid -->
            <div class="glass-card rounded-2xl border border-slate-800 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead class="bg-slate-950/60 text-slate-400 border-b border-slate-800">
                            <tr>
                                <th class="px-5 py-3 text-right">اسم المود</th>
                                <th class="px-5 py-3 text-right">اللعبة</th>
                                <th class="px-5 py-3 text-center">عدد التعارضات المسجلة</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50" id="conflicts-metrics-table-body">
                            @forelse($mostConflictedMods ?? [] as $cm)
                            <tr class="conflict-metric-row hover:bg-slate-800/30 transition-colors" data-game-id="{{ $cm->game_id }}">
                                <td class="px-5 py-3 font-semibold text-white">
                                    <a href="{{ route('mods.show', $cm->slug) }}" class="hover:underline hover:text-violet-400">{{ $cm->name }}</a>
                                </td>
                                <td class="px-5 py-3 text-slate-400">{{ $cm->game_name }}</td>
                                <td class="px-5 py-3 text-center">
                                    <span class="px-2 py-0.5 rounded-full bg-red-500/10 text-red-400 border border-red-500/20 font-bold">
                                        {{ (int)$cm->conflicts_count }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="px-5 py-10 text-center text-slate-500">لا توجد تعارضات مسجلة حالياً.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ============================================= --}}
        {{-- TAB 9: EXTRACTION LOGS --}}
        {{-- ============================================= --}}
        <div id="admin-panel-extraction-logs" class="admin-tab-panel hidden space-y-6">
            <div class="glass-card rounded-2xl border border-slate-800 p-5">
                <h4 class="font-bold text-white text-sm">
                    <i class="fa-solid fa-list-check text-blue-500 ml-2"></i>سجل عمليات استخراج المودات (Extraction Logs)
                </h4>
                <p class="text-[10px] text-slate-500 mt-1">تتبع أداء وموثوقية عمليات استخراج المودات التلقائية وتشخيص أعطال الترجمة (Subtitles) أو استجابات الذكاء الاصطناعي.</p>
            </div>

            <!-- Logs Grid/Table -->
            <div class="glass-card rounded-2xl border border-slate-800 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead class="bg-slate-950/60 text-slate-400 border-b border-slate-800">
                            <tr>
                                <th class="px-5 py-3 text-right">عنوان الفيديو / المعرف</th>
                                <th class="px-5 py-3 text-center">جلب الترجمة؟</th>
                                <th class="px-5 py-3 text-center">صيغة JSON صالحة؟</th>
                                <th class="px-5 py-3 text-center">المودات المستخرجة</th>
                                <th class="px-5 py-3 text-center">موثوقية منخفضة (Low)</th>
                                <th class="px-5 py-3 text-center">التاريخ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50">
                            @forelse($extractionLogs ?? [] as $log)
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="px-5 py-3 font-semibold text-white max-w-xs truncate" title="{{ $log->title }}">
                                    <a href="https://youtube.com/watch?v={{ $log->video_id }}" target="_blank" class="hover:text-violet-400 hover:underline">
                                        <i class="fa-brands fa-youtube text-red-500 ml-1"></i>{{ $log->title }}
                                    </a>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    @if($log->transcript_fetched)
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-bold">نعم</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full bg-red-500/10 text-red-400 border border-red-500/20 font-bold" title="{{ $log->failure_reason }}">
                                            لا ⚠️
                                        </span>
                                        <div class="text-[9px] text-slate-500 mt-1 max-w-xxs truncate" title="{{ $log->failure_reason }}">{{ $log->failure_reason }}</div>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-center">
                                    @if($log->is_valid_json)
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-bold">نعم</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full bg-red-500/10 text-red-400 border border-red-500/20 font-bold">لا</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-center font-bold text-slate-200">
                                    {{ $log->total_mods_extracted }}
                                </td>
                                <td class="px-5 py-3 text-center font-bold text-amber-400">
                                    {{ $log->low_confidence_count }}
                                </td>
                                <td class="px-5 py-3 text-center text-slate-400 font-mono">
                                    {{ $log->created_at->diffForHumans() }}
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="px-5 py-10 text-center text-slate-500">سجل الاستخراج فارغ حالياً.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @include('admin.partials.settings')
        @include('admin.partials.newsletter')

    </div>
</div>

{{-- Import Wizard Modal --}}
<div id="import-wizard-modal" class="fixed inset-0 bg-slate-950/90 backdrop-blur-sm z-50 hidden items-center justify-center p-4 overflow-y-auto">
    <div class="glass-card p-6 md:p-8 rounded-2xl border border-slate-800 max-w-4xl w-full space-y-6" dir="rtl">
        <div class="flex justify-between items-center border-b border-slate-800 pb-4"><h3 class="font-bold text-white text-sm"><i class="fa-solid fa-wand-magic-sparkles text-violet-500 ml-2"></i>معالج الاستيراد</h3><button onclick="closeImportWizard()" class="text-slate-500 hover:text-white text-xl">&times;</button></div>
        <div id="wizard-loading-state" class="py-16 text-center"><i class="fa-solid fa-circle-notch fa-spin text-4xl text-violet-500 mb-4"></i><p class="text-sm text-slate-400">جاري الاستخراج...</p></div>
        <form id="wizard-form" class="hidden space-y-5" onsubmit="submitWizardForm(event)">
            <input type="hidden" id="wizard-video-id"><input type="hidden" id="wizard-game-id">
            {{-- Hidden field to carry AI-extracted game versions --}}
            <input type="hidden" id="wizard-version-ai">
            <div class="flex items-start gap-4"><img id="wizard-video-thumbnail" src="" class="w-32 h-20 rounded-lg object-cover bg-slate-900"><div class="flex-1 grid grid-cols-2 gap-3 text-xs"><div><label class="text-slate-400 font-bold block mb-1">العنوان (EN)</label><input type="text" id="wizard-title-en" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-violet-600" required></div><div><label class="text-slate-400 font-bold block mb-1">العنوان (AR)</label><input type="text" id="wizard-title-ar" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-violet-600" required></div></div></div>
            <div class="grid grid-cols-2 gap-3 text-xs"><div><label class="text-slate-400 font-bold block mb-1">الوصف (EN)</label><textarea id="wizard-desc-en" rows="2" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-violet-600"></textarea></div><div><label class="text-slate-400 font-bold block mb-1">الوصف (AR)</label><textarea id="wizard-desc-ar" rows="2" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-violet-600"></textarea></div></div>
            <div class="grid grid-cols-2 gap-3 text-xs"><div><label class="text-slate-400 font-bold block mb-1">نسخة اللعبة</label><select id="wizard-version-select" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-violet-600"><option value="auto">تعرف تلقائي</option></select><span id="wizard-version-warning" class="text-[10px] text-amber-400 font-bold mt-1 block hidden"><i class="fa-solid fa-triangle-exclamation mr-1"></i> لم يتم التعرف على النسخة تلقائياً، يرجى اختيارها يدوياً.</span></div><div><label class="text-slate-400 font-bold block mb-1">أو أدخل يدوياً</label><input type="text" id="wizard-version-custom" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-violet-600"></div></div>
            <div><h4 class="text-xs font-bold text-white mb-3">المودات المستخرجة</h4><div id="wizard-mods-list-container" class="space-y-3 max-h-[40vh] overflow-y-auto pr-2"></div></div>
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-800"><button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-violet-600 to-blue-500 rounded-xl text-white text-xs font-bold transition-all"><i class="fa-solid fa-save ml-1"></i> حفظ كمسودة</button><button type="button" onclick="closeImportWizard()" class="px-6 py-2.5 bg-slate-800 rounded-xl text-slate-400 text-xs font-bold transition-all">إلغاء</button></div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function switchAdminTab(tabId) {
    document.querySelectorAll('.admin-tab-panel').forEach(p => p.classList.add('hidden'));
    document.querySelectorAll('.admin-sidebar-btn').forEach(btn => {
        btn.classList.remove('bg-slate-900/50', 'border-violet-500/60', 'text-violet-400', 'font-bold');
        btn.classList.add('border-transparent', 'text-slate-400');
    });
    const panel = document.getElementById('admin-panel-' + tabId);
    if (panel) panel.classList.remove('hidden');
    const btn = document.getElementById('admin-tab-btn-' + tabId);
    if (btn) { 
        btn.classList.add('bg-slate-900/50', 'border-violet-500/60', 'text-violet-400', 'font-bold'); 
        btn.classList.remove('border-transparent', 'text-slate-400'); 
    }
    if (history.replaceState) {
        history.replaceState(null, null, '#' + tabId);
    } else {
        window.location.hash = tabId;
    }
}
function toggleEditModal(id) {
    const m = document.getElementById('edit-modal-' + id);
    if (m) { if (m.classList.contains('hidden')) { m.classList.remove('hidden'); m.classList.add('flex'); } else { m.classList.remove('flex'); m.classList.add('hidden'); } }
}
function filterModsTable() {
    const f = document.getElementById('admin-mod-search').value.toLowerCase();
    document.querySelectorAll('.mod-row').forEach(r => { r.style.display = (r.getAttribute('data-name') || '').includes(f) ? '' : 'none'; });
}
function filterConflictsByGame(gameId) {
    document.querySelectorAll('.conflict-metric-row').forEach(row => {
        if (gameId === 'all' || row.getAttribute('data-game-id') === gameId) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

/**
 * Called when the game selector in Nexus Search changes.
 * Auto-loads popular mods for the selected game with no query.
 */
function onNexusGameChange() {
    const query = document.getElementById('nexus-search-query').value.trim();
    // Only auto-load if query is empty (show popular mods)
    if (!query) {
        performNexusSearch();
    }
}

/**
 * Toggle the has_issues flag on a mod.
 * @param {number} modId
 * @param {boolean} currentState - current has_issues value
 * @param {HTMLElement} btn - the button element
 */
function toggleFlagMod(modId, currentState, btn) {
    const newState = !currentState;
    const label = newState ? 'هل تريد تعليم هذا المود كمشكل وتحذير المستخدمين؟' : 'هل تريد إزالة علامة المشكلة من هذا المود؟';
    if (!confirm(label)) return;

    const note = newState ? prompt('أدخل ملاحظة المشكلة (اختياري):', '') : null;
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    btn.disabled = true;
    const icon = btn.querySelector('i');

    fetch(`/admin/mods/${modId}/flag`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        body: JSON.stringify({ has_issues: newState, issues_note: note || '' })
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        if (data.success) {
            // Update button state visually
            const row = btn.closest('tr');
            if (newState) {
                btn.classList.remove('text-slate-500', 'hover:text-amber-400');
                btn.classList.add('text-amber-400', 'hover:text-slate-400');
                btn.title = 'إزالة علامة المشكلة';
                btn.setAttribute('onclick', `toggleFlagMod(${modId}, true, this)`);
                // Add badge to name cell
                const nameCell = row?.querySelector('td:nth-child(2) .flex');
                if (nameCell && !nameCell.querySelector('.issue-badge')) {
                    nameCell.insertAdjacentHTML('beforeend', `<span class="issue-badge inline-flex items-center gap-1 px-2 py-0.5 bg-amber-500/15 border border-amber-500/40 text-amber-400 rounded-lg text-[9px] font-bold"><i class="fa-solid fa-triangle-exclamation"></i> مشكلة</span>`);
                }
            } else {
                btn.classList.remove('text-amber-400', 'hover:text-slate-400');
                btn.classList.add('text-slate-500', 'hover:text-amber-400');
                btn.title = 'تعليم كمشكلة';
                btn.setAttribute('onclick', `toggleFlagMod(${modId}, false, this)`);
                // Remove badge
                row?.querySelector('.issue-badge')?.remove();
            }
        } else {
            alert('فشل التحديث: ' + (data.message || 'خطأ غير معروف'));
        }
    })
    .catch(err => {
        btn.disabled = false;
        console.error(err);
        alert('حدث خطأ أثناء الاتصال بالخادم.');
    });
}


function performNexusSearch() {
    const gameId = document.getElementById('nexus-search-game').value;
    const query = document.getElementById('nexus-search-query').value.trim();
    const resultsContainer = document.getElementById('nexus-search-results');
    const spinner = document.getElementById('nexus-search-spinner');

    // Removed length check to allow fetching popular mods on empty query

    resultsContainer.innerHTML = '';
    spinner.classList.remove('hidden');

    const url = `{{ route('admin.nexus.search') }}?game_id=${gameId}&q=${encodeURIComponent(query)}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            spinner.classList.add('hidden');
            if (data.success && data.results && data.results.length > 0) {
                data.results.forEach(item => {
                    const cardHtml = `
                        <div class="p-4 bg-slate-900/60 border border-slate-850 rounded-xl flex flex-col justify-between space-y-3 group hover:border-orange-500/30 transition-all">
                            <div class="space-y-1">
                                <h6 class="text-xs font-bold text-white group-hover:text-orange-400 transition-colors line-clamp-1">${item.title}</h6>
                                <span class="text-[9px] text-slate-500 font-mono block">Nexus ID: ${item.id}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="${item.url}" target="_blank" class="flex-1 py-1.5 bg-slate-950 border border-slate-800 hover:bg-slate-900 rounded-lg text-[10px] text-center font-bold text-slate-400">معاينة</a>
                                <button type="button" onclick="quickAddNexusMod(this, ${gameId}, '${item.url}', '${item.title.replace(/'/g, "\\'")}')" class="flex-1 py-1.5 bg-gradient-to-r from-orange-600 to-amber-500 hover:from-orange-500 hover:to-amber-400 text-white rounded-lg text-[10px] font-bold transition-all shadow shadow-orange-500/10">
                                    <i class="fa-solid fa-plus mr-1"></i> إضافة سريعة
                                </button>
                            </div>
                        </div>
                    `;
                    resultsContainer.insertAdjacentHTML('beforeend', cardHtml);
                });
            } else {
                resultsContainer.innerHTML = `
                    <div class="col-span-full py-6 text-center text-xs text-slate-650">
                        <i class="fa-solid fa-face-frown text-lg mb-2"></i><br>
                        لم نجد أي مودات مطابقة في Nexus Mods. يرجى تعديل اسم البحث.
                    </div>
                `;
            }
        })
        .catch(err => {
            spinner.classList.add('hidden');
            console.error(err);
            alert('حدث خطأ أثناء الاتصال بالخادم.');
        });
}

function quickAddNexusMod(btn, gameId, nexusUrl, name) {
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner animate-spin"></i> جاري الجلب...';

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(`{{ route('admin.mods.quick-add') }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            game_id: gameId,
            nexus_url: nexusUrl,
            name: name
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            btn.innerHTML = '<i class="fa-solid fa-check"></i> تم!';
            btn.classList.replace('from-orange-600', 'from-emerald-600');
            btn.classList.replace('to-amber-500', 'to-teal-500');

            // Show rich details in the card
            const card = btn.closest('.p-4');
            if (card && data.mod) {
                const m = data.mod;
                let extraHtml = '';
                if (m.image_url) extraHtml += `<img src="${m.image_url}" class="w-full h-20 object-cover rounded-lg mt-2 border border-slate-800">`;
                if (m.description) extraHtml += `<p class="text-[9px] text-slate-400 mt-1 line-clamp-2">${m.description}</p>`;
                if (m.steam_url) extraHtml += `<a href="${m.steam_url}" target="_blank" class="text-[9px] text-blue-400 hover:underline block mt-1"><i class="fa-brands fa-steam mr-1"></i>Steam Workshop</a>`;
                if (extraHtml) card.insertAdjacentHTML('beforeend', extraHtml);
            }

            setTimeout(() => { location.reload(); }, 1500);
        } else {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
            alert('فشل إضافة المود: ' + (data.message || 'خطأ غير معروف'));
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        console.error(err);
        alert('حدث خطأ أثناء جلب المود.');
    });
}
let activeGameVersions = [];
function updateQueryPrefill() {
    const s = document.getElementById('ai-game-id');
    if (s && s.options.length > 0) document.getElementById('ai-query-string').value = s.options[s.selectedIndex].getAttribute('data-name') + ' mod load order';
}
function searchYoutubeVideos() {
    const gameId = document.getElementById('ai-game-id').value, timeRange = document.getElementById('ai-time-range').value, limit = document.getElementById('ai-limit').value, query = encodeURIComponent(document.getElementById('ai-query-string').value);
    const grid = document.getElementById('youtube-videos-grid'), section = document.getElementById('ai-results-section');
    section.classList.remove('hidden');
    grid.innerHTML = '<div class="col-span-full py-12 text-center text-slate-400"><i class="fa-solid fa-circle-notch fa-spin text-3xl mb-3 text-violet-500"></i><p>جاري البحث...</p></div>';
    fetch(`{{ route('admin.ai.search') }}?game_id=${gameId}&time_range=${timeRange}&limit=${limit}&query=${query}`)
        .then(r => r.json()).then(data => {
            const videos = data.videos || []; activeGameVersions = data.versions || [];
            if (!videos.length) { grid.innerHTML = '<div class="col-span-full py-12 text-center text-slate-500">لا توجد نتائج.</div>'; return; }
            grid.innerHTML = '';
            videos.forEach(v => {
                const btn = v.exists ? '<button disabled class="w-full py-2 bg-slate-800 text-slate-500 text-xs rounded-xl cursor-not-allowed">مستورد بالفعل</button>' : `<button onclick="openImportWizard('${v.video_id}', '${gameId}')" class="w-full py-2 bg-violet-600 hover:bg-violet-500 text-white text-xs font-bold rounded-xl transition-all">استيراد</button>`;
                grid.insertAdjacentHTML('beforeend', `<div class="glass-card p-4 rounded-2xl border border-slate-800 space-y-3"><div class="aspect-video rounded-xl overflow-hidden bg-slate-950"><img src="${v.thumbnail_url}" class="w-full h-full object-cover"></div><h5 class="text-xs font-bold text-white line-clamp-2">${v.title}</h5><div class="pt-2 border-t border-slate-800">${btn}</div></div>`);
            });
        }).catch(err => { grid.innerHTML = `<div class="col-span-full py-12 text-center text-red-500">خطأ: ${err.message}</div>`; });
}
function openImportWizard(videoId, gameId) {
    const modal = document.getElementById('import-wizard-modal'); modal.classList.remove('hidden'); modal.classList.add('flex');
    document.getElementById('wizard-loading-state').classList.remove('hidden'); document.getElementById('wizard-form').classList.add('hidden');
    document.getElementById('wizard-video-id').value = videoId; document.getElementById('wizard-game-id').value = gameId;
    const vSel = document.getElementById('wizard-version-select'); vSel.innerHTML = '<option value="auto">تعرف تلقائي</option>';
    activeGameVersions.forEach(v => { vSel.insertAdjacentHTML('beforeend', `<option value="${v.id}">نسخة ${v.version}</option>`); });
    fetch(`/admin/ai/extract-metadata?video_id=${videoId}&game_id=${gameId}`).then(r => r.json()).then(data => {
        if (!data.success) { alert(data.error || 'فشل'); closeImportWizard(); return; }
        document.getElementById('wizard-loading-state').classList.add('hidden'); document.getElementById('wizard-form').classList.remove('hidden');
        document.getElementById('wizard-video-thumbnail').src = data.video.thumbnail_url;
        document.getElementById('wizard-title-en').value = data.title_en; document.getElementById('wizard-title-ar').value = data.title_ar;
        document.getElementById('wizard-desc-en').value = data.description_en; document.getElementById('wizard-desc-ar').value = data.description_ar;
        // Store AI-extracted versions in the hidden field and also show them in the custom field
        const aiVersions = (data.game_versions || []).filter(v => v && v !== 'unknown');
        document.getElementById('wizard-version-ai').value = aiVersions.join(',');
        document.getElementById('wizard-version-custom').value = aiVersions.join(', ') || (data.game_version !== 'unknown' ? data.game_version : '') || '';
        
        // Show/hide version warning label based on auto-detection result
        const warning = document.getElementById('wizard-version-warning');
        if (aiVersions.length === 0 && (data.game_version === 'unknown' || !data.game_version)) {
            if (warning) warning.classList.remove('hidden');
        } else {
            if (warning) warning.classList.add('hidden');
        }

        const c = document.getElementById('wizard-mods-list-container'); c.innerHTML = '';
        if (!data.mods || !data.mods.length) { c.innerHTML = '<div class="py-8 text-center text-slate-500 border border-dashed border-slate-800 rounded-xl">لم يتم العثور على مودات.</div>'; return; }
        data.mods.forEach((mod, i) => {
            const isLowConfidence = mod.confidence === 'low';
            const cardBorder = isLowConfidence ? 'border-amber-500/60' : 'border-slate-800/80';
            const warningAlert = isLowConfidence ? `<div class="text-[10px] text-amber-400 font-bold mt-1"><i class="fa-solid fa-triangle-exclamation mr-1"></i> لم يتم التعرف تلقائياً على النسخة، يرجى اختيارها يدوياً.</div>` : '';
            
            let manualSearchBtn = '';
            if (!mod.nexus_url || !mod.steam_url) {
                const searchQ = encodeURIComponent((mod.nexus_name || mod.extracted_name) + ' mod');
                manualSearchBtn = `<a href="https://www.google.com/search?q=${searchQ}" target="_blank" class="px-2 py-1 bg-slate-900 border border-slate-800 hover:border-violet-600 rounded text-[9px] text-violet-400 hover:text-white flex items-center gap-1 shrink-0"><i class="fa-solid fa-magnifying-glass"></i> بحث يدوي سريع</a>`;
            }

            let snippetHtml = '';
            if (mod.source_snippet) {
                snippetHtml = `<div class="text-[9px] text-slate-500 italic mt-0.5 pr-14">مصدر الاستخراج: "${mod.source_snippet}"</div>`;
            }

            c.insertAdjacentHTML('beforeend', `
                <div class="glass-card p-3 rounded-xl border ${cardBorder} flex flex-col gap-2 text-xs" id="mod-row-${i}">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="mod-cb-${i}" checked class="w-4 h-4 accent-violet-600">
                        <div class="w-10 h-10 bg-slate-950 border border-slate-800 rounded-lg overflow-hidden shrink-0">
                            <img id="mod-img-${i}" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiM0NzU1NjkiIHN0cm9rZS13aWR0aD0iMiI+PHJlY3Qgd2lkdGg9IjE4IiBoZWlnaHQ9IjE4IiB4PSIzIiB5PSIzIiByeD0iMiIvPjxwYXRoIGQ9Im0yMSAxNi00LTQiLz48Y2lyY2xlIGN4PSIxNSIgY3k9IjkiIHI9IjIiLz48L3N2Zz4=" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-2">
                            <input type="text" id="mod-name-${i}" value="${mod.nexus_name || mod.extracted_name}" class="bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-white">
                            <input type="text" id="mod-nexus-${i}" value="${mod.nexus_url || ''}" placeholder="Nexus URL" class="bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-white text-[10px] font-mono">
                            <input type="text" id="mod-steam-${i}" value="${mod.steam_url || ''}" placeholder="Steam URL" class="bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-white text-[10px] font-mono">
                            <input type="text" id="mod-download-${i}" value="${mod.download_url || ''}" placeholder="Direct Download" class="bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-white text-[10px] font-mono">
                        </div>
                        ${manualSearchBtn}
                        <input type="hidden" id="mod-order-${i}" value="${mod.load_order}">
                        <input type="hidden" id="mod-img-val-${i}" value="">
                        <input type="hidden" id="mod-desc-val-${i}" value="">
                    </div>
                    ${snippetHtml}
                    ${warningAlert}
                </div>
            `);
            if (mod.nexus_url) loadModDetailsAsync(i, mod.nexus_url);
        });
    }).catch(err => { alert('خطأ: ' + err.message); closeImportWizard(); });
}
function closeImportWizard() { const m = document.getElementById('import-wizard-modal'); m.classList.remove('flex'); m.classList.add('hidden'); }
function loadModDetailsAsync(i, url) {
    fetch(`/admin/ai/get-mod-details?url=${encodeURIComponent(url)}`).then(r => r.json()).then(d => {
        if (d.image_url) { const img = document.getElementById(`mod-img-${i}`); if (img) img.src = d.image_url; const v = document.getElementById(`mod-img-val-${i}`); if (v) v.value = d.image_url; }
        if (d.description) { const dv = document.getElementById(`mod-desc-val-${i}`); if (dv) dv.value = d.description; }
    }).catch(() => {});
}
function submitWizardForm(event) {
    event.preventDefault();
    const mods = [];
    document.querySelectorAll('[id^="mod-row-"]').forEach(row => {
        const i = row.id.replace('mod-row-', ''), cb = document.getElementById(`mod-cb-${i}`);
        if (cb && cb.checked) mods.push({ name: document.getElementById(`mod-name-${i}`).value, load_order: parseInt(document.getElementById(`mod-order-${i}`).value) || 1, nexus_url: document.getElementById(`mod-nexus-${i}`).value, steam_url: document.getElementById(`mod-steam-${i}`).value, download_url: document.getElementById(`mod-download-${i}`).value, image_url: document.getElementById(`mod-img-val-${i}`).value, description: document.getElementById(`mod-desc-val-${i}`).value });
    });
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content'), btn = event.target.querySelector('button[type="submit"]'), orig = btn.innerHTML;
    btn.disabled = true; btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> جاري الحفظ...';
    // Include AI-extracted versions in the payload
    const versionAi = document.getElementById('wizard-version-ai')?.value || '';
    fetch('{{ route("admin.ai.save-import") }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, body: JSON.stringify({ video_id: document.getElementById('wizard-video-id').value, game_id: document.getElementById('wizard-game-id').value, version_select: document.getElementById('wizard-version-select').value, version_custom: document.getElementById('wizard-version-custom').value, version_ai: versionAi, title_en: document.getElementById('wizard-title-en').value, title_ar: document.getElementById('wizard-title-ar').value, description_en: document.getElementById('wizard-desc-en').value, description_ar: document.getElementById('wizard-desc-ar').value, mods: mods }) })
    .then(r => r.json()).then(d => { btn.disabled = false; btn.innerHTML = orig; if (d.success) { alert(d.message); closeImportWizard(); window.location.reload(); } else alert(d.error || 'فشل'); })
    .catch(e => { btn.disabled = false; btn.innerHTML = orig; alert('خطأ: ' + e.message); });
}
document.addEventListener('DOMContentLoaded', function() {
    const hashTab = window.location.hash ? window.location.hash.replace('#', '') : null;
    const urlParams = new URLSearchParams(window.location.search);
    
    if (hashTab && document.getElementById('admin-panel-' + hashTab)) {
        switchAdminTab(hashTab);
    } else if (urlParams.has('mods_page')) {
        switchAdminTab('mods');
    } else {
        switchAdminTab('metrics');
    }
    updateQueryPrefill();
    // Auto-load popular mods for the default selected game on page load
    onNexusGameChange();
});
@if(session('confirm_delete_mod_id'))
<div id="confirm-delete-mod-modal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="glass-card p-6 rounded-2xl border border-slate-800 max-w-md w-full space-y-4" dir="rtl">
        <div class="flex items-center gap-3 text-red-500">
            <i class="fa-solid fa-triangle-exclamation text-2xl animate-pulse"></i>
            <h4 class="font-bold text-white text-sm">تنبيه حذف مود متصل بتجميعات</h4>
        </div>
        <p class="text-xs text-slate-350 leading-relaxed">
            المود <span class="font-bold text-white">"{{ session('confirm_delete_mod_name') }}"</span> مستخدم حالياً في التجميعات التالية:
        </p>
        <ul class="list-disc list-inside text-xs text-slate-450 space-y-1 bg-slate-950/40 p-3 rounded-xl border border-slate-900/60 max-h-28 overflow-y-auto text-right">
            @foreach(session('confirm_delete_mod_packs') as $packTitle)
                <li>{{ $packTitle }}</li>
            @endforeach
        </ul>
        <p class="text-[10px] text-red-400 font-semibold leading-relaxed">
            ⚠️ حذف هذا المود سيؤدي إلى فكه وإزالته تلقائياً من كافة هذه التجميعات المتأثرة. هل تريد المتابعة والحذف بأي حال؟
        </p>
        <div class="flex justify-end gap-2 pt-2 border-t border-slate-800">
            <form action="{{ route('admin.mods.delete', session('confirm_delete_mod_id')) }}" method="POST">
                @csrf
                @method('DELETE')
                <input type="hidden" name="force_delete" value="1">
                <button type="submit" class="px-5 py-2 bg-red-600 hover:bg-red-500 rounded-xl text-white font-bold text-xs transition-all">نعم، احذف وفك المود</button>
            </form>
            <button onclick="document.getElementById('confirm-delete-mod-modal').remove()" class="px-5 py-2 bg-slate-800 hover:bg-slate-700 rounded-xl text-slate-450 font-bold text-xs transition-all">إلغاء</button>
        </div>
    </div>
</div>
@endif

</script>
@endsection
