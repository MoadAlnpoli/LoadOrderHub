@extends('layouts.app')

@section('title', 'Securing Connection...')

@section('content')
<div class="max-w-3xl mx-auto my-8 space-y-8 text-center">
    
    <!-- Top Header Card -->
    <div class="glass-card p-8 rounded-3xl border border-slate-800 space-y-6 shadow-2xl relative overflow-hidden">
        <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-violet-600 via-blue-500 to-violet-600 animate-pulse"></div>

        <div class="w-16 h-16 mx-auto rounded-2xl bg-violet-600/10 border border-violet-500/30 flex items-center justify-center text-violet-400 text-2xl shadow-lg relative">
            <i class="fa-solid fa-shield-halved animate-bounce"></i>
            <span class="absolute -top-1 -right-1 w-3 h-3 rounded-full bg-emerald-500 animate-ping"></span>
        </div>

        <div class="space-y-2">
            <h1 class="text-xl md:text-2xl font-black text-white">Securing Your Connection</h1>
            <p class="text-xs text-slate-400">You are being redirected to an external server: <span class="text-violet-400 font-bold font-mono">{{ $host }}</span></p>
        </div>

        <!-- Timer / Progress section -->
        <div class="max-w-md mx-auto space-y-3">
            <div class="relative pt-1">
                <div class="overflow-hidden h-2 text-xs flex rounded-full bg-slate-950 border border-slate-850">
                    <div id="redirect-progress-bar" style="width: 0%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r from-violet-600 to-blue-500 transition-all duration-100"></div>
                </div>
            </div>
            <div class="text-xs text-slate-400 font-bold flex justify-between px-1">
                <span>Verifying destination...</span>
                <span id="countdown-text">Please wait 5s</span>
            </div>
        </div>

        <!-- Action Button -->
        <div class="pt-2">
            <a href="{{ $targetUrl }}" id="download-btn" class="inline-flex items-center justify-center space-x-2 rtl:space-x-reverse px-8 py-3.5 rounded-2xl bg-slate-900 border border-slate-800 text-slate-500 font-bold text-sm tracking-wide transition-all shadow-md cursor-not-allowed pointer-events-none">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                <span id="btn-label">Verifying Link (5)</span>
            </a>
        </div>

        <p class="text-[10px] text-slate-500 max-w-sm mx-auto leading-relaxed">
            By visiting sponsored ads on this page, you support the free AI automation infrastructure of LoadOrderHub. Thank you!
        </p>
    </div>

    <!-- Multi-Column Banner Ad Placements (AdSense Placeholder Slots) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Ad Slot 1 -->
        <div class="glass-card p-6 rounded-2xl border border-dashed border-slate-800 text-center space-y-3 bg-slate-950/20 hover:border-violet-650/45 transition-colors">
            <div class="text-[9px] text-slate-600 font-bold uppercase tracking-widest">SPONSORED ADVERTISEMENT</div>
            <div class="h-64 rounded-xl border border-slate-850 bg-slate-950 flex items-center justify-center text-xs text-slate-500 p-4 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-tr from-violet-950/5 to-blue-950/5 pointer-events-none"></div>
                <div class="space-y-2">
                    <i class="fa-solid fa-rectangle-ad text-4xl text-slate-700"></i>
                    <p class="text-xs text-slate-400 font-bold">Responsive Display Banner Ad</p>
                    <span class="text-[10px] text-slate-650 font-mono block">336x280 Large Rectangle</span>
                </div>
            </div>
        </div>

        <!-- Ad Slot 2 -->
        <div class="glass-card p-6 rounded-2xl border border-dashed border-slate-800 text-center space-y-3 bg-slate-950/20 hover:border-violet-650/45 transition-colors">
            <div class="text-[9px] text-slate-600 font-bold uppercase tracking-widest">SPONSORED ADVERTISEMENT</div>
            <div class="h-64 rounded-xl border border-slate-850 bg-slate-950 flex items-center justify-center text-xs text-slate-500 p-4 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-tr from-violet-950/5 to-blue-950/5 pointer-events-none"></div>
                <div class="space-y-2">
                    <i class="fa-solid fa-rectangle-ad text-4xl text-slate-700"></i>
                    <p class="text-xs text-slate-400 font-bold">Responsive Display Banner Ad</p>
                    <span class="text-[10px] text-slate-650 font-mono block">336x280 Large Rectangle</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let count = 5;
        const btn = document.getElementById('download-btn');
        const btnLabel = document.getElementById('btn-label');
        const countdownText = document.getElementById('countdown-text');
        const progressBar = document.getElementById('redirect-progress-bar');
        
        const interval = setInterval(function () {
            count--;
            const progress = ((5 - count) / 5) * 100;
            progressBar.style.width = progress + '%';
            
            if (count > 0) {
                btnLabel.innerText = 'Verifying Link (' + count + ')';
                countdownText.innerText = 'Please wait ' + count + 's';
            } else {
                clearInterval(interval);
                
                // Activate download button
                btn.classList.remove('bg-slate-900', 'border-slate-800', 'text-slate-500', 'cursor-not-allowed', 'pointer-events-none');
                btn.classList.add('bg-gradient-to-tr', 'from-violet-600', 'to-blue-500', 'hover:from-violet-500', 'hover:to-blue-400', 'text-white', 'shadow-violet-500/20');
                
                btnLabel.innerText = 'Proceed to Download';
                countdownText.innerText = 'Link verified successfully!';
                
                // Enable link click
                btn.style.pointerEvents = 'auto';
                
                // Automatically redirect in new window/tab after 0.5s for seamless UX
                setTimeout(function () {
                    window.location.href = "{{ $targetUrl }}";
                }, 500);
            }
        }, 1000);
    });
</script>
@endsection
