@extends('layouts.app')

@section('title', 'About Us - LoadOrderHub')

@section('meta')
    <meta name="description" content="Learn more about LoadOrderHub, the ultimate platform for discovering, organizing, and sharing video game mod configurations and load orders.">
    <meta property="og:title" content="About Us - LoadOrderHub">
    <meta property="og:description" content="Learn more about LoadOrderHub, the ultimate platform for discovering, organizing, and sharing video game mod configurations and load orders.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('about') }}">
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-8 my-8">
    <div class="glass-card rounded-3xl p-8 md:p-12 border border-slate-800 text-center space-y-6">
        <div class="w-20 h-20 mx-auto rounded-2xl bg-gradient-to-tr from-violet-600 to-blue-500 flex items-center justify-center shadow-lg shadow-violet-500/20 mb-6">
            <svg class="w-10 h-10 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="6" width="20" height="12" rx="4"></rect>
                <path d="M6 12h4m-2-2v4"></path>
                <circle cx="15.5" cy="11.5" r="1" fill="currentColor"></circle>
                <circle cx="18.5" cy="12.5" r="1" fill="currentColor"></circle>
            </svg>
        </div>
        <h1 class="text-3xl md:text-5xl font-extrabold text-white">About <span class="bg-gradient-to-r from-violet-400 to-blue-400 bg-clip-text text-transparent">LoadOrderHub</span></h1>
        
        <p class="text-slate-300 text-lg max-w-2xl mx-auto leading-relaxed">
            Welcome to the ultimate modding companion platform. We make it easy for players to discover, share, and discuss the best mod lists and load orders for their favorite games.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12 text-left rtl:text-right">
            <div class="bg-slate-950/50 p-6 rounded-2xl border border-slate-800">
                <i class="fa-solid fa-layer-group text-3xl text-violet-500 mb-4"></i>
                <h3 class="text-white font-bold text-lg mb-2">Curated Mod Packs</h3>
                <p class="text-slate-400 text-sm">Find load orders curated by the community. Say goodbye to game crashes and conflicting mods.</p>
            </div>
            <div class="bg-slate-950/50 p-6 rounded-2xl border border-slate-800">
                <i class="fa-solid fa-robot text-3xl text-blue-500 mb-4"></i>
                <h3 class="text-white font-bold text-lg mb-2">AI-Powered</h3>
                <p class="text-slate-400 text-sm">We use advanced AI to extract mod lists from YouTube showcases automatically.</p>
            </div>
            <div class="bg-slate-950/50 p-6 rounded-2xl border border-slate-800">
                <i class="fa-solid fa-users text-3xl text-fuchsia-500 mb-4"></i>
                <h3 class="text-white font-bold text-lg mb-2">Community Driven</h3>
                <p class="text-slate-400 text-sm">Rate, comment, and save your favorite packs. Join thousands of modders worldwide.</p>
            </div>
        </div>
    </div>
</div>
@endsection
