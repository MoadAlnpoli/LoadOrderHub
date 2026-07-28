@extends('layouts.app')

@section('title', 'Frequently Asked Questions')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 my-8">
    <div class="text-center space-y-4 mb-12">
        <h1 class="text-4xl md:text-5xl font-black text-white drop-shadow-lg">
            <i class="fa-solid fa-circle-question text-violet-500 mr-2 rtl:ml-2"></i> FAQ
        </h1>
        <p class="text-slate-400 text-lg">Frequently Asked Questions</p>
    </div>

    <div class="space-y-4">
        <!-- FAQ Item 1 -->
        <div class="glass-card rounded-2xl border border-slate-800 p-6 space-y-3 transition-colors hover:border-violet-500/50">
            <h3 class="text-xl font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-caret-right text-violet-500 rtl:rotate-180"></i> What is LoadOrderHub?
            </h3>
            <p class="text-slate-400 text-sm leading-relaxed pl-6 rtl:pr-6 rtl:pl-0">
                LoadOrderHub is a platform that hosts optimized, pre-configured mod packs and load orders for PC games to ensure a crash-free experience.
            </p>
        </div>

        <!-- FAQ Item 2 -->
        <div class="glass-card rounded-2xl border border-slate-800 p-6 space-y-3 transition-colors hover:border-violet-500/50">
            <h3 class="text-xl font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-caret-right text-violet-500 rtl:rotate-180"></i> How do I install a mod pack?
            </h3>
            <p class="text-slate-400 text-sm leading-relaxed pl-6 rtl:pr-6 rtl:pl-0">
                You can download the pack using the provided links (Google Drive, MediaFire, etc.) and follow the "Load Order Text" instructions provided by the creator.
            </p>
        </div>

        <!-- FAQ Item 3 -->
        <div class="glass-card rounded-2xl border border-slate-800 p-6 space-y-3 transition-colors hover:border-violet-500/50">
            <h3 class="text-xl font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-caret-right text-violet-500 rtl:rotate-180"></i> Can I upload my own mod pack?
            </h3>
            <p class="text-slate-400 text-sm leading-relaxed pl-6 rtl:pr-6 rtl:pl-0">
                Yes! Register for an account and click "Create Pack" in the navigation menu. You'll need to provide mod links, a load order, and a video showcase.
            </p>
        </div>
    </div>
</div>
@endsection
