@extends('layouts.app')

@section('title', 'Reset Link Simulated')

@section('content')
<div class="max-w-md mx-auto my-12">
    <div class="glass-card rounded-2xl border border-slate-800 p-8 space-y-6 shadow-2xl text-center">
        
        <!-- Icon -->
        <div class="w-16 h-16 mx-auto rounded-full bg-violet-500/10 border border-violet-500/20 flex items-center justify-center">
            <i class="fa-solid fa-paper-plane text-2xl text-violet-400 animate-bounce"></i>
        </div>

        <!-- Header -->
        <div class="space-y-2">
            <h2 class="text-2xl font-black text-white tracking-tight">Reset Link Simulated!</h2>
            <p class="text-xs text-slate-400 leading-relaxed">Since this is a development/sandbox environment, a password reset request has been logged and simulated immediately.</p>
        </div>

        <!-- Alert box -->
        <div class="p-4 rounded-xl bg-slate-950 border border-slate-850 space-y-3 text-left">
            <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider block">Development Helper</span>
            <p class="text-xs text-slate-350">A password reset token was created for user email <strong class="text-violet-400">{{ $email }}</strong>.</p>
            
            <a href="{{ route('password.reset', ['token' => $token, 'email' => $email]) }}" class="block text-center py-2.5 bg-violet-600/20 hover:bg-violet-600/30 text-violet-300 border border-violet-500/30 hover:border-violet-500/50 rounded-xl text-xs font-bold transition-all">
                Reset Password Now &rarr;
            </a>
        </div>

        <!-- Back to Login Link -->
        <div class="text-xs text-slate-500 pt-2">
            Or go back to <a href="{{ route('login') }}" class="text-violet-400 hover:text-violet-300 font-semibold">Sign in</a>.
        </div>

    </div>
</div>
@endsection
