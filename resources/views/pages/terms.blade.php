@extends('layouts.app')

@section('title', 'Terms of Service - LoadOrderHub')

@section('meta')
    <meta name="description" content="Read the Terms of Service for LoadOrderHub. Understand the rules, guidelines, and your responsibilities when using our modding community platform.">
    <meta property="og:title" content="Terms of Service - LoadOrderHub">
    <meta property="og:description" content="Read the Terms of Service for LoadOrderHub. Understand the rules, guidelines, and your responsibilities when using our modding community platform.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('terms') }}">
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-8 my-8">
    <div class="glass-card rounded-3xl p-8 md:p-12 border border-slate-800">
        <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-6 border-b border-slate-800 pb-4">Terms of Service</h1>
        
        <div class="space-y-6 text-slate-300 leading-relaxed text-sm">
            <p><strong>Last Updated:</strong> {{ date('Y-m-d') }}</p>
            
            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white">1. Acceptance of Terms</h2>
                <p>By accessing or using LoadOrderHub, you agree to be bound by these Terms of Service and all applicable laws and regulations. If you do not agree with any part of these terms, you may not use our services.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white">2. User Content</h2>
                <p>Users may post, upload, or otherwise contribute content to the site (e.g., mod lists, comments, ratings). You retain all rights in, and are solely responsible for, the User Content you post to LoadOrderHub.</p>
                <p>You agree not to post content that is illegal, abusive, harassing, or violates the rights of any third party.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white">3. Third-Party Content & Mods</h2>
                <p>LoadOrderHub aggregates information about modifications (mods) created by third-party developers. We do not host the mod files themselves unless explicitly stated. We are not responsible for the safety, functionality, or legality of third-party mods downloaded from external sites.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white">4. Termination</h2>
                <p>We may terminate or suspend access to our service immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach the Terms.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-white">5. Changes to Terms</h2>
                <p>We reserve the right, at our sole discretion, to modify or replace these Terms at any time. By continuing to access or use our service after those revisions become effective, you agree to be bound by the revised terms.</p>
            </section>
        </div>
    </div>
</div>
@endsection
