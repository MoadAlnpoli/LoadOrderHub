@extends('layouts.app')

@section('title', 'Contact Us - LoadOrderHub')

@section('meta')
    <meta name="description" content="Get in touch with LoadOrderHub. Have a question, feedback, or need support? Contact our team and we'll get back to you as soon as possible.">
    <meta property="og:title" content="Contact Us - LoadOrderHub">
    <meta property="og:description" content="Get in touch with LoadOrderHub. Have a question, feedback, or need support? Contact our team and we'll get back to you as soon as possible.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('contact') }}">
@endsection

@section('content')
<div class="max-w-2xl mx-auto space-y-8 my-8">
    <div class="glass-card rounded-3xl p-8 md:p-12 border border-slate-800">
        <div class="text-center mb-8">
            <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-2">Contact Us</h1>
            <p class="text-slate-400 text-sm">Have a question or want to report an issue? Send us a message.</p>
        </div>
        
        <form action="{{ route('contact.post') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Name</label>
                <input type="text" name="name" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-violet-600 transition-colors" placeholder="Your Name">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Email</label>
                <input type="email" name="email" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-violet-600 transition-colors" placeholder="your@email.com">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Message</label>
                <textarea name="message" rows="5" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-violet-600 transition-colors" placeholder="How can we help you?"></textarea>
            </div>

            <button type="submit" class="w-full btn-shimmer py-3 rounded-xl bg-gradient-to-r from-violet-600 to-blue-500 hover:from-violet-500 hover:to-blue-400 text-white font-bold transition-all shadow-lg shadow-violet-500/20">
                Send Message
            </button>
        </form>
    </div>
</div>
@endsection
