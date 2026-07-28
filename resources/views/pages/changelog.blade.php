@extends('layouts.app')

@section('title', 'Changelog')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 my-8">
    <div class="text-center space-y-4 mb-12">
        <h1 class="text-4xl md:text-5xl font-black text-white drop-shadow-lg">
            <i class="fa-solid fa-code-branch text-emerald-500 mr-2 rtl:ml-2"></i> Changelog
        </h1>
        <p class="text-slate-400 text-lg">Recent updates and improvements to the platform</p>
    </div>

    <div class="relative border-l-2 border-slate-800 ml-4 rtl:ml-0 rtl:mr-4 rtl:border-r-2 rtl:border-l-0 space-y-12 pb-8">
        
        <!-- v2.1.0 -->
        <div class="relative pl-8 rtl:pr-8 rtl:pl-0">
            <div class="absolute -left-[11px] rtl:-right-[11px] rtl:left-auto top-1 w-5 h-5 rounded-full bg-emerald-500 border-4 border-slate-950"></div>
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <span class="text-2xl font-black text-white">v2.1.0</span>
                    <span class="px-2.5 py-0.5 rounded-full bg-slate-800 text-slate-400 text-[10px] font-bold">Today</span>
                </div>
                <ul class="space-y-2 text-sm text-slate-300">
                    <li class="flex gap-2">
                        <i class="fa-solid fa-plus text-emerald-500 mt-1"></i>
                        <span>Added global Stats Bar to the homepage hero section.</span>
                    </li>
                    <li class="flex gap-2">
                        <i class="fa-solid fa-plus text-emerald-500 mt-1"></i>
                        <span>Introduced Top Mods Weekly page for trending content.</span>
                    </li>
                    <li class="flex gap-2">
                        <i class="fa-solid fa-wand-magic-sparkles text-violet-500 mt-1"></i>
                        <span>Grid/List toggle view added to the Mod Explorer.</span>
                    </li>
                    <li class="flex gap-2">
                        <i class="fa-solid fa-bolt text-amber-500 mt-1"></i>
                        <span>Implemented Schema.org JSON-LD and Lazy Loading for SEO & Performance.</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- v2.0.0 -->
        <div class="relative pl-8 rtl:pr-8 rtl:pl-0">
            <div class="absolute -left-[11px] rtl:-right-[11px] rtl:left-auto top-1 w-5 h-5 rounded-full bg-slate-700 border-4 border-slate-950"></div>
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <span class="text-2xl font-black text-slate-400">v2.0.0</span>
                    <span class="px-2.5 py-0.5 rounded-full bg-slate-800 text-slate-500 text-[10px] font-bold">July 2026</span>
                </div>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li class="flex gap-2">
                        <i class="fa-solid fa-paintbrush text-blue-500 mt-1"></i>
                        <span>Complete frontend UI modernization with glassmorphism design.</span>
                    </li>
                    <li class="flex gap-2">
                        <i class="fa-solid fa-plus text-emerald-500 mt-1"></i>
                        <span>Modularized Admin panel with dynamic tab routing.</span>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>
@endsection
