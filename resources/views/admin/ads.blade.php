@extends('layouts.app')

@section('title', 'Ads Management')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-800 pb-4">
        <h1 class="text-2xl font-bold text-white flex items-center gap-2">
            <i class="fa-solid fa-ad text-blue-500"></i>
            <span>إدارة الإعلانات (Ads Management)</span>
        </h1>
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-bold transition-all">العودة للوحة التحكم</a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-bold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Create Ad Slot Form -->
    <div class="glass-card p-6 rounded-3xl border border-slate-850">
        <h2 class="text-lg font-bold text-white mb-4">إضافة مساحة إعلانية جديدة</h2>
        <form action="{{ route('admin.ads.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-400">اسم المساحة (Name)</label>
                    <input type="text" name="name" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-400">حالة التفعيل (Active)</label>
                    <div class="pt-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" checked class="w-5 h-5 rounded border-slate-800 bg-slate-900 text-blue-500 focus:ring-blue-500">
                            <span class="text-slate-300 font-bold">مفعل</span>
                        </label>
                    </div>
                </div>
                <div class="space-y-2 md:col-span-2">
                    <label class="text-sm font-bold text-slate-400">كود الإعلان (Ad Code)</label>
                    <textarea name="code" rows="4" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500" dir="ltr" required></textarea>
                </div>
            </div>
            <div class="flex justify-end pt-2">
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 rounded-xl text-white font-bold transition-all shadow-lg shadow-blue-500/20">
                    حفظ المساحة الإعلانية
                </button>
            </div>
        </form>
    </div>

    <!-- Existing Ad Slots -->
    <div class="grid grid-cols-1 gap-4">
        @forelse($ads as $ad)
            <div class="glass-card p-6 rounded-3xl border border-slate-850 space-y-4">
                <form action="{{ route('admin.ads.update', $ad->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400">اسم المساحة</label>
                            <input type="text" name="name" value="{{ $ad->name }}" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2 text-white focus:outline-none focus:border-blue-500" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400">الحالة</label>
                            <div class="pt-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" {{ $ad->is_active ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-800 bg-slate-900 text-blue-500 focus:ring-blue-500">
                                    <span class="text-slate-300 text-xs font-bold">مفعل</span>
                                </label>
                            </div>
                        </div>
                        <div class="space-y-2 md:col-span-2">
                            <label class="text-xs font-bold text-slate-400">كود الإعلان</label>
                            <textarea name="code" rows="3" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2 text-white focus:outline-none focus:border-blue-500" dir="ltr" required>{{ $ad->code }}</textarea>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-slate-800">
                        <button type="submit" class="px-5 py-2 bg-slate-800 hover:bg-slate-700 rounded-xl text-white text-xs font-bold transition-all">تحديث</button>
                        <button type="button" onclick="if(confirm('هل أنت متأكد من الحذف؟')) document.getElementById('delete-ad-{{ $ad->id }}').submit();" class="px-5 py-2 bg-red-500/10 hover:bg-red-500/20 text-red-500 rounded-xl text-xs font-bold transition-all border border-red-500/20">حذف</button>
                    </div>
                </form>
                <form id="delete-ad-{{ $ad->id }}" action="{{ route('admin.ads.delete', $ad->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        @empty
            <div class="text-center py-12 text-slate-500 bg-slate-900/30 rounded-3xl border border-slate-800">
                <i class="fa-solid fa-ad text-4xl mb-4 opacity-50 block"></i>
                <p>لا توجد مساحات إعلانية حالياً.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
