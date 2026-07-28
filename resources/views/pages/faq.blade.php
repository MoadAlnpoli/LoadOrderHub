@extends('layouts.app')

@section('title', 'Frequently Asked Questions - LoadOrderHub')

@section('meta')
    <meta name="description" content="Find answers to frequently asked questions about LoadOrderHub — how to install mod packs, create your own builds, use load orders, and more.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ route('faq') }}">
    <meta property="og:title" content="FAQ - LoadOrderHub">
    <meta property="og:description" content="Find answers to frequently asked questions about LoadOrderHub — mod packs, load orders, installation guides, and more.">
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "FAQPage",
      "mainEntity": [
        {
          "@@type": "Question",
          "name": "What is LoadOrderHub?",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "LoadOrderHub is a platform that hosts optimized, pre-configured mod packs and load orders for PC games to ensure a crash-free experience."
          }
        },
        {
          "@@type": "Question",
          "name": "How do I install a mod pack?",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "Download the pack using the provided links and follow the Load Order Text instructions provided by the creator. Most packs are compatible with Mod Organizer 2 (MO2) and Vortex."
          }
        },
        {
          "@@type": "Question",
          "name": "Can I upload my own mod pack?",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "Yes! Register for an account and click Create Pack in the navigation menu. You'll need to provide mod links, a load order, and a video showcase."
          }
        },
        {
          "@@type": "Question",
          "name": "Is LoadOrderHub free to use?",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "Yes, LoadOrderHub is completely free to browse and use. Creating an account is also free and allows you to save packs, leave ratings, and share your own builds."
          }
        }
      ]
    }
    </script>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-8 my-8">

    <div class="text-center space-y-4">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold bg-violet-500/10 border border-violet-500/30 text-violet-300">
            <i class="fa-solid fa-circle-question"></i>
            <span>Help Center</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-black text-white">Frequently Asked <span class="bg-gradient-to-r from-violet-400 to-blue-400 bg-clip-text text-transparent">Questions</span></h1>
        <p class="text-slate-400 text-lg max-w-2xl mx-auto">Everything you need to know about LoadOrderHub. Can't find your answer? <a href="{{ route('contact') }}" class="text-violet-400 hover:text-violet-300 underline">Contact us</a>.</p>
    </div>

    {{-- Getting Started --}}
    <div class="space-y-3">
        <h2 class="text-lg font-extrabold text-slate-300 uppercase tracking-wider px-1 flex items-center gap-2">
            <i class="fa-solid fa-rocket text-violet-500 text-sm"></i> Getting Started
        </h2>

        <div class="glass-card rounded-2xl border border-slate-800 p-6 space-y-3 hover:border-violet-500/40 transition-colors">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-caret-right text-violet-500"></i> What is LoadOrderHub?
            </h3>
            <p class="text-slate-400 text-sm leading-relaxed pl-6">
                LoadOrderHub is a free platform that hosts optimized, pre-configured mod packs and load orders for popular PC games (Skyrim, Fallout, Cyberpunk, and more). Our goal is to help you achieve a stable, crash-free modded experience by providing tested load orders from experienced modders and AI-extracted builds from YouTube content creators.
            </p>
        </div>

        <div class="glass-card rounded-2xl border border-slate-800 p-6 space-y-3 hover:border-violet-500/40 transition-colors">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-caret-right text-violet-500"></i> Is LoadOrderHub free to use?
            </h3>
            <p class="text-slate-400 text-sm leading-relaxed pl-6">
                Yes! LoadOrderHub is completely free to browse and use. Creating an account is also free and gives you access to extra features like saving favorite packs, leaving ratings and comments, and sharing your own mod builds with the community.
            </p>
        </div>

        <div class="glass-card rounded-2xl border border-slate-800 p-6 space-y-3 hover:border-violet-500/40 transition-colors">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-caret-right text-violet-500"></i> What games are supported?
            </h3>
            <p class="text-slate-400 text-sm leading-relaxed pl-6">
                We support all moddable PC games. Currently our largest communities are around titles like The Elder Scrolls V: Skyrim, Fallout 4, Cyberpunk 2077, and others. New games are added regularly based on community demand.
            </p>
        </div>
    </div>

    {{-- Mod Packs --}}
    <div class="space-y-3">
        <h2 class="text-lg font-extrabold text-slate-300 uppercase tracking-wider px-1 flex items-center gap-2">
            <i class="fa-solid fa-layer-group text-blue-500 text-sm"></i> Mod Packs & Installation
        </h2>

        <div class="glass-card rounded-2xl border border-slate-800 p-6 space-y-3 hover:border-violet-500/40 transition-colors">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-caret-right text-violet-500"></i> How do I install a mod pack?
            </h3>
            <p class="text-slate-400 text-sm leading-relaxed pl-6">
                Each mod pack comes with a <strong class="text-slate-300">Load Order</strong> — an ordered list of all required mods. You download the individual mods from their respective pages (Nexus Mods, Mod DB, etc.) and install them using a mod manager like <strong class="text-slate-300">Mod Organizer 2 (MO2)</strong> or <strong class="text-slate-300">Vortex</strong>. You can export the load order directly from LoadOrderHub as a .txt, .json, or MO2-compatible format.
            </p>
        </div>

        <div class="glass-card rounded-2xl border border-slate-800 p-6 space-y-3 hover:border-violet-500/40 transition-colors">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-caret-right text-violet-500"></i> Does LoadOrderHub host the mod files?
            </h3>
            <p class="text-slate-400 text-sm leading-relaxed pl-6">
                No. LoadOrderHub <strong class="text-slate-300">does not host mod files</strong>. We provide organized information about mods (names, links, load order positions) and link you to the original mod pages on Nexus Mods, ModDB, and other hosting platforms. This respects modders' rights and keeps downloads safe and authorized.
            </p>
        </div>

        <div class="glass-card rounded-2xl border border-slate-800 p-6 space-y-3 hover:border-violet-500/40 transition-colors">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-caret-right text-violet-500"></i> What is a Load Order?
            </h3>
            <p class="text-slate-400 text-sm leading-relaxed pl-6">
                A load order is the sequence in which your mods are loaded by the game engine. An incorrect load order can cause crashes, missing textures, or broken quests. LoadOrderHub provides pre-tested load orders so you don't have to figure this out yourself.
            </p>
        </div>

        <div class="glass-card rounded-2xl border border-slate-800 p-6 space-y-3 hover:border-violet-500/40 transition-colors">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-caret-right text-violet-500"></i> Can I export a mod pack to use with Mod Organizer 2?
            </h3>
            <p class="text-slate-400 text-sm leading-relaxed pl-6">
                Yes! On any mod pack page, click the <strong class="text-slate-300">Export</strong> button to download the list as a plain text file, JSON file, or an MO2-compatible format. You can then import this directly into your mod manager.
            </p>
        </div>
    </div>

    {{-- Account & Content --}}
    <div class="space-y-3">
        <h2 class="text-lg font-extrabold text-slate-300 uppercase tracking-wider px-1 flex items-center gap-2">
            <i class="fa-solid fa-user text-fuchsia-500 text-sm"></i> Account & Creating Content
        </h2>

        <div class="glass-card rounded-2xl border border-slate-800 p-6 space-y-3 hover:border-violet-500/40 transition-colors">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-caret-right text-violet-500"></i> Can I upload my own mod pack?
            </h3>
            <p class="text-slate-400 text-sm leading-relaxed pl-6">
                Absolutely! Register for a free account, then click <strong class="text-slate-300">"Create Pack"</strong> in the navigation. You'll add a title, description, the game version, and a list of mods with their load order positions. You can optionally include a YouTube showcase video link.
            </p>
        </div>

        <div class="glass-card rounded-2xl border border-slate-800 p-6 space-y-3 hover:border-violet-500/40 transition-colors">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-caret-right text-violet-500"></i> How does the AI mod extraction work?
            </h3>
            <p class="text-slate-400 text-sm leading-relaxed pl-6">
                When you submit a YouTube video URL featuring a game mod showcase, our AI system automatically analyzes the video title, description, and transcript to identify and list the mods shown. The extracted list is then reviewed and published as a mod pack on the platform.
            </p>
        </div>

        <div class="glass-card rounded-2xl border border-slate-800 p-6 space-y-3 hover:border-violet-500/40 transition-colors">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-caret-right text-violet-500"></i> How are mod packs rated?
            </h3>
            <p class="text-slate-400 text-sm leading-relaxed pl-6">
                Registered users can rate any mod pack on a scale of 1–5 stars and leave comments describing their experience. The overall rating is the average of all community votes.
            </p>
        </div>
    </div>

    {{-- CTA --}}
    <div class="glass-card rounded-3xl p-8 border border-slate-800 text-center space-y-4 bg-gradient-to-br from-violet-900/20 to-blue-900/20">
        <i class="fa-solid fa-headset text-3xl text-violet-400"></i>
        <h2 class="text-xl font-extrabold text-white">Still have questions?</h2>
        <p class="text-slate-400 text-sm">Our team is happy to help. Send us a message and we'll get back to you as soon as possible.</p>
        <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-violet-600 to-blue-500 text-white text-sm font-bold hover:opacity-90 transition-opacity shadow-lg shadow-violet-500/20">
            <i class="fa-solid fa-envelope text-xs"></i>
            Contact Support
        </a>
    </div>

</div>
@endsection
