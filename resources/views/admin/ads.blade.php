@extends('layouts.app')

@section('title', 'إدارة الإعلانات')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-800 pb-4">
        <h1 class="text-2xl font-bold text-white flex items-center gap-2">
            <i class="fa-solid fa-rectangle-ad text-blue-500"></i>
            <span>إدارة الإعلانات</span>
        </h1>
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-bold transition-all">العودة</a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-bold">
            <i class="fa-solid fa-check-circle ml-2"></i>{{ session('success') }}
        </div>
    @endif

    {{-- ── THE 3 AD TYPES ─────────────────────────────────────── --}}
    <div class="glass-card p-5 rounded-2xl border border-blue-500/20 space-y-4">
        <h2 class="text-sm font-bold text-white flex items-center gap-2">
            <i class="fa-solid fa-circle-info text-blue-400"></i>
            النظام بسيط جداً — 3 أحجام فقط
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div class="bg-slate-900/60 border border-orange-500/25 rounded-2xl p-4 space-y-3 text-center">
                <div class="w-12 h-12 bg-orange-500/15 rounded-xl flex items-center justify-center mx-auto">
                    <i class="fa-solid fa-grip-lines text-orange-400 text-lg"></i>
                </div>
                <div>
                    <code class="text-orange-300 font-mono font-bold text-sm">leaderboard</code>
                    <p class="text-[10px] text-slate-500 mt-1">728×90 بانر عريض</p>
                </div>
                <div class="text-[10px] text-slate-500 space-y-0.5 text-right">
                    <p>✓ الصفحة الرئيسية</p>
                    <p>✓ أعلى صفحة المود</p>
                    <p>✓ مستكشف المودات</p>
                    <p>✓ صفحات الألعاب</p>
                </div>
            </div>

            <div class="bg-slate-900/60 border border-violet-500/25 rounded-2xl p-4 space-y-3 text-center">
                <div class="w-12 h-12 bg-violet-500/15 rounded-xl flex items-center justify-center mx-auto">
                    <i class="fa-solid fa-square text-violet-400 text-lg"></i>
                </div>
                <div>
                    <code class="text-violet-300 font-mono font-bold text-sm">sidebar</code>
                    <p class="text-[10px] text-slate-500 mt-1">300×250 مربع جانبي</p>
                </div>
                <div class="text-[10px] text-slate-500 space-y-0.5 text-right">
                    <p>✓ جانب صفحة المود</p>
                    <p>✓ جانب صفحة التجميعة</p>
                </div>
            </div>

            <div class="bg-slate-900/60 border border-cyan-500/25 rounded-2xl p-4 space-y-3 text-center">
                <div class="w-12 h-12 bg-cyan-500/15 rounded-xl flex items-center justify-center mx-auto">
                    <i class="fa-solid fa-minus text-cyan-400 text-lg"></i>
                </div>
                <div>
                    <code class="text-cyan-300 font-mono font-bold text-sm">in_content</code>
                    <p class="text-[10px] text-slate-500 mt-1">468×60 داخل المحتوى</p>
                </div>
                <div class="text-[10px] text-slate-500 space-y-0.5 text-right">
                    <p>✓ وسط صفحة المود</p>
                    <p>✓ بين المودات في المستكشف</p>
                </div>
            </div>

        </div>
        <p class="text-[11px] text-slate-500 text-center pt-1">
            <i class="fa-solid fa-lightbulb text-yellow-400 ml-1"></i>
            أنشئ إعلاناً بأحد الأسماء الثلاثة أعلاه وسيظهر تلقائياً في جميع صفحاته
        </p>
    </div>

    {{-- ── CREATE FORM ─────────────────────────────────────── --}}
    <div class="glass-card p-6 rounded-3xl border border-slate-800">
        <h2 class="text-base font-bold text-white mb-4 flex items-center gap-2">
            <i class="fa-solid fa-plus text-blue-400"></i>
            إضافة إعلان جديد
        </h2>
        <form action="{{ route('admin.ads.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="space-y-2">
                <label class="text-xs font-bold text-slate-400">اختر نوع الإعلان</label>
                <div class="grid grid-cols-3 gap-3" id="type-selector">
                    <label class="cursor-pointer">
                        <input type="radio" name="name" value="leaderboard" class="sr-only peer" required>
                        <div class="peer-checked:border-orange-500 peer-checked:bg-orange-500/10 border border-slate-800 rounded-xl p-3 text-center transition-all hover:border-orange-500/50">
                            <i class="fa-solid fa-grip-lines text-orange-400 text-lg block mb-1"></i>
                            <code class="text-orange-300 text-xs font-bold">leaderboard</code>
                            <p class="text-[10px] text-slate-600 mt-1">بانر عريض</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="name" value="sidebar" class="sr-only peer">
                        <div class="peer-checked:border-violet-500 peer-checked:bg-violet-500/10 border border-slate-800 rounded-xl p-3 text-center transition-all hover:border-violet-500/50">
                            <i class="fa-solid fa-square text-violet-400 text-lg block mb-1"></i>
                            <code class="text-violet-300 text-xs font-bold">sidebar</code>
                            <p class="text-[10px] text-slate-600 mt-1">مربع جانبي</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="name" value="in_content" class="sr-only peer">
                        <div class="peer-checked:border-cyan-500 peer-checked:bg-cyan-500/10 border border-slate-800 rounded-xl p-3 text-center transition-all hover:border-cyan-500/50">
                            <i class="fa-solid fa-minus text-cyan-400 text-lg block mb-1"></i>
                            <code class="text-cyan-300 text-xs font-bold">in_content</code>
                            <p class="text-[10px] text-slate-600 mt-1">داخل المحتوى</p>
                        </div>
                    </label>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 rounded border-slate-700 bg-slate-900 text-blue-500">
                    <span class="text-slate-300 text-sm font-bold">مفعّل</span>
                </label>
            </div>
            <div class="space-y-2">
                <label class="text-xs font-bold text-slate-400">كود الإعلان (HTML / Google AdSense)</label>
                <textarea name="code" rows="5" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 font-mono text-xs" dir="ltr" required placeholder="الصق هنا كود Google AdSense أو أي كود HTML..."></textarea>
            </div>
            <button type="submit" class="w-full py-3 bg-gradient-to-r from-blue-600 to-violet-600 hover:from-blue-500 hover:to-violet-500 rounded-xl text-white font-bold transition-all shadow-lg">
                <i class="fa-solid fa-floppy-disk ml-2"></i> حفظ الإعلان
            </button>
        </form>
    </div>

    {{-- ── EXISTING ADS ─────────────────────────────────────── --}}
    @if($ads->count())
    <div class="space-y-3">
        <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider">الإعلانات المحفوظة ({{ $ads->count() }}/3)</h2>
        @foreach($ads as $ad)
        @php
            $typeConfig = match($ad->name) {
                'leaderboard' => ['color' => 'orange', 'icon' => 'fa-grip-lines', 'label' => 'بانر عريض — يظهر في أعلى الصفحات'],
                'sidebar'     => ['color' => 'violet', 'icon' => 'fa-square',     'label' => 'مربع جانبي — يظهر في الشريط الجانبي'],
                'in_content'  => ['color' => 'cyan',   'icon' => 'fa-minus',      'label' => 'داخل المحتوى — يظهر بين عناصر الصفحة'],
                default       => ['color' => 'slate',  'icon' => 'fa-rectangle-ad','label' => $ad->name],
            };
        @endphp
        <div class="glass-card p-5 rounded-2xl border border-slate-800 space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-{{ $typeConfig['color'] }}-500/15 flex items-center justify-center">
                        <i class="fa-solid {{ $typeConfig['icon'] }} text-{{ $typeConfig['color'] }}-400"></i>
                    </div>
                    <div>
                        <code class="text-{{ $typeConfig['color'] }}-300 font-mono font-bold text-sm">{{ $ad->name }}</code>
                        <p class="text-[10px] text-slate-500">{{ $typeConfig['label'] }}</p>
                    </div>
                    @if($ad->is_active)
                        <span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-400 rounded-full text-[10px] font-bold border border-emerald-500/20">● مفعّل</span>
                    @else
                        <span class="px-2 py-0.5 bg-red-500/10 text-red-400 rounded-full text-[10px] font-bold border border-red-500/20">○ موقوف</span>
                    @endif
                </div>
                <div class="flex gap-2">
                    <button onclick="document.getElementById('edit-{{ $ad->id }}').classList.toggle('hidden')"
                        class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-bold transition">
                        <i class="fa-solid fa-pen"></i> تعديل
                    </button>
                    <button onclick="if(confirm('حذف هذا الإعلان؟')) document.getElementById('del-{{ $ad->id }}').submit()"
                        class="px-3 py-1.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 rounded-lg text-xs font-bold transition border border-red-500/20">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="bg-slate-950 rounded-lg p-2.5 font-mono text-[10px] text-slate-500 truncate border border-slate-900" dir="ltr">
                {{ Str::limit($ad->code, 130) }}
            </div>
            <div id="edit-{{ $ad->id }}" class="hidden border-t border-slate-800 pt-4 space-y-3">
                <form action="{{ route('admin.ads.update', $ad->id) }}" method="POST">
                    @csrf @method('PUT')
                    <input type="hidden" name="name" value="{{ $ad->name }}">
                    <div class="flex items-center gap-3 mb-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ $ad->is_active ? 'checked' : '' }} class="w-4 h-4 rounded">
                            <span class="text-slate-300 text-xs font-bold">مفعّل</span>
                        </label>
                    </div>
                    <textarea name="code" rows="4" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white text-xs font-mono focus:outline-none focus:border-blue-500" dir="ltr" required>{{ $ad->code }}</textarea>
                    <div class="flex justify-end gap-2 mt-2">
                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-500 rounded-xl text-white font-bold text-xs transition">حفظ</button>
                        <button type="button" onclick="document.getElementById('edit-{{ $ad->id }}').classList.add('hidden')" class="px-5 py-2 bg-slate-800 rounded-xl text-slate-400 font-bold text-xs">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
        <form id="del-{{ $ad->id }}" action="{{ route('admin.ads.delete', $ad->id) }}" method="POST" class="hidden">
            @csrf @method('DELETE')
        </form>
        @endforeach
    </div>
    @else
    <div class="text-center py-12 text-slate-500 bg-slate-900/30 rounded-3xl border border-dashed border-slate-800">
        <i class="fa-solid fa-rectangle-ad text-4xl mb-4 opacity-30 block"></i>
        <p class="font-bold">لا توجد إعلانات بعد</p>
        <p class="text-xs mt-1 text-slate-600">أضف إعلاناً لكل نوع من الأنواع الثلاثة أعلاه</p>
    </div>
    @endif
</div>
@endsection
