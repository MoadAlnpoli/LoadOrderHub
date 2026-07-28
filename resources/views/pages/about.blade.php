@extends('layouts.app')

@section('title', 'About Us - LoadOrderHub')

@section('meta')
    <meta name="description" content="Learn about LoadOrderHub — the ultimate platform for discovering, organizing, and sharing video game mod configurations and load orders for PC gamers worldwide.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ route('about') }}">
    <meta property="og:title" content="About Us - LoadOrderHub">
    <meta property="og:description" content="Learn about LoadOrderHub — the ultimate platform for discovering, organizing, and sharing video game mod configurations and load orders.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('about') }}">
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "Organization",
      "name": "LoadOrderHub",
      "url": "{{ url('/') }}",
      "description": "The ultimate platform for discovering, organizing, and sharing PC game mod configurations and load orders.",
      "foundingDate": "2025"
    }
    </script>
@endsection

@section('content')
<div class="max-w-5xl mx-auto space-y-10 my-8">

    {{-- Hero Section --}}
    <div class="glass-card rounded-3xl p-10 md:p-16 border border-slate-800 text-center relative overflow-hidden">
        <div class="absolute -top-20 -left-20 w-72 h-72 bg-violet-600/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative space-y-6">
            <div class="w-20 h-20 mx-auto rounded-2xl bg-gradient-to-tr from-violet-600 to-blue-500 flex items-center justify-center shadow-lg shadow-violet-500/20">
                <i class="fa-solid fa-gamepad text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold text-white">
                About <span class="bg-gradient-to-r from-violet-400 to-blue-400 bg-clip-text text-transparent">LoadOrderHub</span>
            </h1>
            <p class="text-slate-300 text-lg max-w-2xl mx-auto leading-relaxed">
                The ultimate modding companion platform. We help PC gamers discover, share, and install the best mod configurations for their favorite games — crash-free and hassle-free.
            </p>
            <div class="flex flex-wrap justify-center gap-3 pt-2">
                <a href="{{ route('home') }}" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-violet-600 to-blue-500 text-white text-sm font-bold hover:opacity-90 transition-opacity">
                    Browse Games
                </a>
                <a href="{{ route('contact') }}" class="px-6 py-2.5 rounded-xl border border-slate-700 text-slate-300 text-sm font-bold hover:border-violet-500 hover:text-white transition-colors">
                    Contact Us
                </a>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @php
            $totalMods = \App\Models\Mod::count();
            $totalPacks = \App\Models\ModPack::count();
            $totalGames = \App\Models\Game::count();
            $totalUsers = \App\Models\User::count();
        @endphp
        <div class="glass-card rounded-2xl p-6 border border-slate-800 text-center space-y-2">
            <div class="text-3xl font-black bg-gradient-to-r from-violet-400 to-blue-400 bg-clip-text text-transparent">{{ number_format($totalGames) }}+</div>
            <div class="text-slate-400 text-xs font-bold uppercase tracking-wider">Games</div>
        </div>
        <div class="glass-card rounded-2xl p-6 border border-slate-800 text-center space-y-2">
            <div class="text-3xl font-black bg-gradient-to-r from-violet-400 to-blue-400 bg-clip-text text-transparent">{{ number_format($totalMods) }}+</div>
            <div class="text-slate-400 text-xs font-bold uppercase tracking-wider">Mods Indexed</div>
        </div>
        <div class="glass-card rounded-2xl p-6 border border-slate-800 text-center space-y-2">
            <div class="text-3xl font-black bg-gradient-to-r from-violet-400 to-blue-400 bg-clip-text text-transparent">{{ number_format($totalPacks) }}+</div>
            <div class="text-slate-400 text-xs font-bold uppercase tracking-wider">Mod Packs</div>
        </div>
        <div class="glass-card rounded-2xl p-6 border border-slate-800 text-center space-y-2">
            <div class="text-3xl font-black bg-gradient-to-r from-violet-400 to-blue-400 bg-clip-text text-transparent">{{ number_format($totalUsers) }}+</div>
            <div class="text-slate-400 text-xs font-bold uppercase tracking-wider">Members</div>
        </div>
    </div>

    {{-- Mission & Features --}}
    <div class="glass-card rounded-3xl p-8 md:p-12 border border-slate-800 space-y-8">
        <div>
            <h2 class="text-2xl font-extrabold text-white mb-3">Our Mission</h2>
            <p class="text-slate-300 leading-relaxed">
                LoadOrderHub was built to solve one of the biggest frustrations in PC gaming: mod conflicts and crashes. We provide a centralized hub where experienced modders can share their optimized mod lists and load orders, and where new players can find stable, tested configurations with a single click.
            </p>
            <p class="text-slate-300 leading-relaxed mt-3">
                Whether you play Skyrim, Fallout, Cyberpunk, or any other moddable game, our platform makes it easy to go from a vanilla install to a fully modded experience in minutes — not hours.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-slate-950/50 p-6 rounded-2xl border border-slate-800 space-y-3">
                <div class="w-10 h-10 rounded-xl bg-violet-500/15 border border-violet-500/30 flex items-center justify-center">
                    <i class="fa-solid fa-layer-group text-violet-400 text-lg"></i>
                </div>
                <h3 class="text-white font-bold">Curated Mod Packs</h3>
                <p class="text-slate-400 text-sm">Community-tested load orders that eliminate compatibility issues. Each pack is organized for maximum stability.</p>
            </div>
            <div class="bg-slate-950/50 p-6 rounded-2xl border border-slate-800 space-y-3">
                <div class="w-10 h-10 rounded-xl bg-blue-500/15 border border-blue-500/30 flex items-center justify-center">
                    <i class="fa-solid fa-robot text-blue-400 text-lg"></i>
                </div>
                <h3 class="text-white font-bold">AI-Powered Extraction</h3>
                <p class="text-slate-400 text-sm">Our AI automatically extracts mod lists from YouTube showcases — so popular content creators' builds are always available.</p>
            </div>
            <div class="bg-slate-950/50 p-6 rounded-2xl border border-slate-800 space-y-3">
                <div class="w-10 h-10 rounded-xl bg-fuchsia-500/15 border border-fuchsia-500/30 flex items-center justify-center">
                    <i class="fa-solid fa-users text-fuchsia-400 text-lg"></i>
                </div>
                <h3 class="text-white font-bold">Community Driven</h3>
                <p class="text-slate-400 text-sm">Rate, comment, save packs, and share your own builds. A growing community of modders helping each other.</p>
            </div>
            <div class="bg-slate-950/50 p-6 rounded-2xl border border-slate-800 space-y-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center">
                    <i class="fa-solid fa-file-export text-emerald-400 text-lg"></i>
                </div>
                <h3 class="text-white font-bold">One-Click Export</h3>
                <p class="text-slate-400 text-sm">Export any mod pack as a .txt, .json, or MO2-compatible format. Compatible with all major mod managers.</p>
            </div>
            <div class="bg-slate-950/50 p-6 rounded-2xl border border-slate-800 space-y-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/15 border border-amber-500/30 flex items-center justify-center">
                    <i class="fa-solid fa-code-compare text-amber-400 text-lg"></i>
                </div>
                <h3 class="text-white font-bold">Mod Comparison</h3>
                <p class="text-slate-400 text-sm">Compare multiple mods side-by-side to make the best choice for your build. See ratings, downloads, and compatibility at a glance.</p>
            </div>
            <div class="bg-slate-950/50 p-6 rounded-2xl border border-slate-800 space-y-3">
                <div class="w-10 h-10 rounded-xl bg-cyan-500/15 border border-cyan-500/30 flex items-center justify-center">
                    <i class="fa-solid fa-globe text-cyan-400 text-lg"></i>
                </div>
                <h3 class="text-white font-bold">Multi-Language</h3>
                <p class="text-slate-400 text-sm">Available in English and Arabic, with RTL support. We're building a platform for the global gaming community.</p>
            </div>
        </div>
    </div>

    {{-- Our Values --}}
    <div class="glass-card rounded-3xl p-8 md:p-12 border border-slate-800 space-y-6">
        <h2 class="text-2xl font-extrabold text-white">Our Values</h2>
        <div class="space-y-4">
            <div class="flex items-start gap-4">
                <div class="w-8 h-8 rounded-lg bg-violet-500/15 border border-violet-500/30 flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fa-solid fa-check text-violet-400 text-xs"></i>
                </div>
                <div>
                    <h3 class="font-bold text-white text-sm">Free & Open Access</h3>
                    <p class="text-slate-400 text-sm mt-1">We believe modding knowledge should be freely accessible. LoadOrderHub is free to use and browse without any paywall.</p>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <div class="w-8 h-8 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fa-solid fa-check text-blue-400 text-xs"></i>
                </div>
                <div>
                    <h3 class="font-bold text-white text-sm">Community First</h3>
                    <p class="text-slate-400 text-sm mt-1">Every feature we build is driven by the needs of our community. Your feedback shapes the platform.</p>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <div class="w-8 h-8 rounded-lg bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fa-solid fa-check text-emerald-400 text-xs"></i>
                </div>
                <div>
                    <h3 class="font-bold text-white text-sm">Privacy & Transparency</h3>
                    <p class="text-slate-400 text-sm mt-1">We are transparent about how we collect and use your data. We respect your privacy and comply with GDPR.</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
