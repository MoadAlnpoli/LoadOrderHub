@props([
    'type' => 'leaderboard', {{-- leaderboard | sidebar | in_content --}}
    'class' => '',
])

@php
    // Map type to DB name
    $adRecord = null;
    try {
        $adRecord = \App\Models\AdSlot::where('name', $type)->where('is_active', true)->first();
    } catch (\Exception $e) {
        $adRecord = null;
    }
@endphp

@if($adRecord)
    <div class="w-full text-center space-y-1 {{ $class }}">
        <span class="text-[9px] text-slate-600 font-bold uppercase tracking-widest">إعلان</span>
        <div class="mx-auto w-full flex items-center justify-center">
            {!! $adRecord->code !!}
        </div>
    </div>
@else
    {{-- Placeholder --}}
    @if($type === 'sidebar')
        <div class="w-full text-center space-y-1 {{ $class }}">
            <span class="text-[9px] text-slate-600 font-bold uppercase tracking-widest">مساحة إعلانية</span>
            <div class="w-full min-h-[250px] rounded-2xl border border-dashed border-slate-800/80 bg-slate-950/40 flex items-center justify-center text-xs text-slate-500">
                <div class="flex flex-col items-center gap-2">
                    <i class="fa-solid fa-rectangle-ad text-3xl text-slate-700"></i>
                    <span class="text-slate-500 text-[10px]">300×250</span>
                </div>
            </div>
        </div>
    @elseif($type === 'in_content')
        <div class="w-full text-center space-y-1 {{ $class }}">
            <span class="text-[9px] text-slate-600 font-bold uppercase tracking-widest">مساحة إعلانية</span>
            <div class="mx-auto max-w-2xl h-16 rounded-xl border border-dashed border-slate-800/80 bg-slate-950/40 flex items-center justify-center text-xs text-slate-500">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-rectangle-ad text-xl text-slate-700"></i>
                    <span class="text-slate-500 text-[10px]">468×60 In-Content</span>
                </div>
            </div>
        </div>
    @else
        {{-- leaderboard --}}
        <div class="w-full text-center space-y-1 {{ $class }}">
            <span class="text-[9px] text-slate-600 font-bold uppercase tracking-widest">مساحة إعلانية</span>
            <div class="mx-auto max-w-4xl h-24 rounded-2xl border border-dashed border-slate-800/80 bg-slate-950/40 flex items-center justify-center text-xs text-slate-500">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-rectangle-ad text-3xl text-slate-700"></i>
                    <span class="text-slate-500 text-[10px]">728×90 Leaderboard</span>
                </div>
            </div>
        </div>
    @endif
@endif
