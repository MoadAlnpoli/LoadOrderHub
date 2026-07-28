@extends('layouts.app')

@section('title', (app()->getLocale() == 'ar' ? 'جاري تجهيز رابط التحميل — ' : 'Preparing Download — ') . ($mod->name ?? 'Mod'))

@section('head')
<meta name="robots" content="noindex, follow">
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-8 py-6">

    <!-- Top Sponsored Ad Banner -->
    <x-ad-slot type="leaderboard" />

    <!-- Main Gateway Box -->
    <div class="glass-card p-8 rounded-3xl border border-slate-800 space-y-6 text-center shadow-2xl relative overflow-hidden">
        <div class="absolute -top-24 -left-24 w-48 h-48 bg-violet-600/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Mod Summary Header -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 text-center sm:text-left rtl:sm:text-right border-b border-slate-800/80 pb-6">
            <div class="w-16 h-16 rounded-2xl bg-slate-950 border border-slate-800 overflow-hidden shrink-0 flex items-center justify-center shadow-lg">
                @if($mod && ($mod->local_image_path || $mod->image_url))
                    <img src="{{ $mod->local_image_path ?: $mod->image_url }}" alt="" class="w-full h-full object-cover">
                @else
                    <i class="fa-solid fa-cube text-violet-400 text-2xl"></i>
                @endif
            </div>
            <div class="space-y-1">
                <span class="px-2.5 py-0.5 rounded-full bg-violet-500/10 border border-violet-500/20 text-violet-400 text-[11px] font-bold">
                    {{ $mod->game->name ?? 'Game Mod' }}
                </span>
                <h1 class="text-2xl font-extrabold text-white">{{ $mod->name ?? 'Requested Mod' }}</h1>
                @if($mod && $mod->version)
                    <span class="text-xs text-slate-400 font-mono">Version v{{ $mod->version }}</span>
                @endif
            </div>
        </div>

        <!-- Countdown Section -->
        <div class="space-y-4 py-4" id="countdown-container">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-900 border border-slate-800 text-slate-300 text-xs font-bold">
                <i class="fa-solid fa-clock-rotate-left text-violet-400 animate-spin"></i>
                <span>{{ app()->getLocale() == 'ar' ? 'سيكون رابط التحميل جاهزاً خلال:' : 'Your download link will be ready in:' }}</span>
            </div>

            <div class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-violet-400 via-blue-400 to-emerald-400 font-mono tracking-wider py-2" id="timer-count">
                5
            </div>

            <p class="text-xs text-slate-500 max-w-md mx-auto">
                {{ app()->getLocale() == 'ar' 
                    ? 'يتم توجيهك بأمان إلى رابط التحميل المباشر. يرجى الانتظار بضع ثوانٍ.' 
                    : 'You are being safely redirected to the official download server. Thank you for using LoadOrderHub!' }}
            </p>
        </div>

        <!-- Action Button Container -->
        <div class="pt-2">
            <a href="{{ $targetUrl }}" id="download-btn" target="_blank"
               class="inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl bg-gradient-to-r from-violet-600 via-blue-600 to-emerald-500 hover:from-violet-500 hover:to-emerald-400 text-white font-extrabold text-base shadow-xl shadow-violet-500/20 transition-all duration-300 transform hover:scale-105 opacity-60 pointer-events-none">
                <i class="fa-solid fa-download"></i>
                <span id="btn-label">{{ app()->getLocale() == 'ar' ? 'جاري تجهيز الرابط...' : 'Preparing Link...' }}</span>
            </a>
        </div>

    </div>

    <!-- Middle Ad Placements -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-ad-slot type="sidebar" />
        <x-ad-slot type="sidebar" />
    </div>

</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let count = 5;
        const timerCount = document.getElementById('timer-count');
        const downloadBtn = document.getElementById('download-btn');
        const btnLabel = document.getElementById('btn-label');
        const isAr = "{{ app()->getLocale() }}" === 'ar';

        const interval = setInterval(() => {
            count--;
            if (timerCount) timerCount.innerText = count;

            if (count <= 0) {
                clearInterval(interval);
                if (timerCount) timerCount.innerText = "0";

                if (downloadBtn) {
                    downloadBtn.classList.remove('opacity-60', 'pointer-events-none');
                    downloadBtn.classList.add('animate-bounce');
                    if (btnLabel) btnLabel.innerText = isAr ? 'الانتقال إلى التحميل الآن 🚀' : 'Proceed to Download Now 🚀';
                }
            }
        }, 1000);
    });
</script>
@endsection
@endsection
