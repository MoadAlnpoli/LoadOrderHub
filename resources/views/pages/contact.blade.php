@extends('layouts.app')

@section('title', 'Contact Us - LoadOrderHub')

@section('meta')
    <meta name="description" content="Get in touch with LoadOrderHub. Have a question, feedback, or need support? Contact our team and we'll get back to you as soon as possible.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ route('contact') }}">
    <meta property="og:title" content="Contact Us - LoadOrderHub">
    <meta property="og:description" content="Get in touch with LoadOrderHub. Have a question, feedback, or need support? Contact our team and we'll get back to you as soon as possible.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('contact') }}">
@endsection

@section('content')
<div class="max-w-4xl mx-auto my-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Left Info Panel --}}
        <div class="space-y-5">
            <div class="glass-card rounded-3xl p-6 border border-slate-800 space-y-4">
                <h2 class="text-lg font-extrabold text-white">Contact Info</h2>
                <div class="space-y-3 text-sm text-slate-400">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-violet-500/15 border border-violet-500/30 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-envelope text-violet-400 text-xs"></i>
                        </div>
                        <div>
                            <div class="text-slate-300 font-bold text-xs mb-0.5">Email</div>
                            <span>support@loadorderhub.com</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-clock text-blue-400 text-xs"></i>
                        </div>
                        <div>
                            <div class="text-slate-300 font-bold text-xs mb-0.5">Response Time</div>
                            <span>Usually within 24–48 hours</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-3xl p-6 border border-slate-800 space-y-3">
                <h2 class="text-sm font-extrabold text-slate-300 uppercase tracking-wider">Quick Links</h2>
                <div class="space-y-2">
                    <a href="{{ route('faq') }}" class="flex items-center gap-2 text-sm text-slate-400 hover:text-violet-400 transition-colors py-1.5 group">
                        <i class="fa-solid fa-circle-question text-violet-500 group-hover:text-violet-300 transition-colors w-4 text-center"></i>
                        Browse FAQ
                    </a>
                    <a href="{{ route('privacy') }}" class="flex items-center gap-2 text-sm text-slate-400 hover:text-violet-400 transition-colors py-1.5 group">
                        <i class="fa-solid fa-shield-halved text-violet-500 group-hover:text-violet-300 transition-colors w-4 text-center"></i>
                        Privacy Policy
                    </a>
                    <a href="{{ route('terms') }}" class="flex items-center gap-2 text-sm text-slate-400 hover:text-violet-400 transition-colors py-1.5 group">
                        <i class="fa-solid fa-scale-balanced text-violet-500 group-hover:text-violet-300 transition-colors w-4 text-center"></i>
                        Terms of Service
                    </a>
                    <a href="{{ route('about') }}" class="flex items-center gap-2 text-sm text-slate-400 hover:text-violet-400 transition-colors py-1.5 group">
                        <i class="fa-solid fa-info-circle text-violet-500 group-hover:text-violet-300 transition-colors w-4 text-center"></i>
                        About Us
                    </a>
                </div>
            </div>
        </div>

        {{-- Contact Form --}}
        <div class="md:col-span-2">
            <div class="glass-card rounded-3xl p-8 md:p-10 border border-slate-800">
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-violet-600 to-blue-500 flex items-center justify-center">
                            <i class="fa-solid fa-paper-plane text-white text-sm"></i>
                        </div>
                        <h1 class="text-2xl md:text-3xl font-extrabold text-white">Contact Us</h1>
                    </div>
                    <p class="text-slate-400 text-sm pl-13">Have a question, suggestion, or want to report an issue? We'd love to hear from you.</p>
                </div>

                @if(session('success'))
                    <div class="mb-6 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl p-5 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-circle-check text-emerald-400 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-emerald-300 mb-1">Message Received!</h3>
                            <p class="text-emerald-400/80 text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 bg-red-500/10 border border-red-500/30 rounded-2xl p-5">
                        <ul class="space-y-1 text-red-400 text-sm">
                            @foreach($errors->all() as $error)
                                <li class="flex items-center gap-2"><i class="fa-solid fa-circle-exclamation text-xs"></i> {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('contact.post') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Name <span class="text-red-400">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-violet-600 focus:ring-1 focus:ring-violet-600/20 transition-all placeholder-slate-600"
                                   placeholder="Your Name">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Email <span class="text-red-400">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-violet-600 focus:ring-1 focus:ring-violet-600/20 transition-all placeholder-slate-600"
                                   placeholder="your@email.com">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Subject</label>
                        <select name="subject" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-violet-600 transition-all">
                            <option value="general">General Question</option>
                            <option value="bug">Bug Report</option>
                            <option value="feature">Feature Request</option>
                            <option value="modpack">Issue with a Mod Pack</option>
                            <option value="account">Account Problem</option>
                            <option value="advertising">Advertising / Partnership</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Message <span class="text-red-400">*</span></label>
                        <textarea name="message" rows="6" required
                                  class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-violet-600 focus:ring-1 focus:ring-violet-600/20 transition-all placeholder-slate-600 resize-none"
                                  placeholder="Describe your question or issue in detail...">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit"
                            class="w-full py-3.5 rounded-xl bg-gradient-to-r from-violet-600 to-blue-500 hover:from-violet-500 hover:to-blue-400 text-white font-bold transition-all shadow-lg shadow-violet-500/20 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane text-sm"></i>
                        Send Message
                    </button>

                    <p class="text-[11px] text-slate-600 text-center">
                        By submitting this form, you agree to our
                        <a href="{{ route('privacy') }}" class="text-slate-500 hover:text-slate-400 underline">Privacy Policy</a>.
                    </p>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
