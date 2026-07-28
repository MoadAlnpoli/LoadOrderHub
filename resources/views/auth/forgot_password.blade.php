@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="max-w-md mx-auto my-12">
    <div class="glass-card rounded-2xl border border-slate-800 p-8 space-y-6 shadow-2xl">
        
        <!-- Header -->
        <div class="text-center space-y-2">
            <h2 class="text-2xl font-extrabold text-white tracking-tight">Forgot Password</h2>
            <p class="text-xs text-slate-400">Enter your email address and we'll help you reset your password.</p>
        </div>

        <!-- Errors -->
        @if ($errors->any())
            <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-xs text-red-400 space-y-1">
                @foreach ($errors->all() as $error)
                    <div class="flex items-center space-x-1.5 rtl:space-x-reverse">
                        <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                        <span>{{ $error }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Email -->
            <div class="space-y-1">
                <label for="email" class="text-xs font-bold uppercase tracking-wider text-slate-400">Email Address</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email') }}" 
                    class="w-full bg-slate-950 border border-slate-850 rounded-xl px-4 py-3 text-sm text-slate-200 placeholder-slate-650 focus:outline-none focus:border-violet-600"
                    placeholder="name@example.com"
                    required>
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-violet-600 to-blue-500 hover:from-violet-500 hover:to-blue-400 text-white font-bold text-sm tracking-wide shadow-lg shadow-violet-500/10 transition-all">
                Send Reset Link
            </button>
        </form>

        <!-- Back to Login Link -->
        <div class="text-center text-xs text-slate-500 border-t border-slate-850 pt-4">
            Remember your password? 
            <a href="{{ route('login') }}" class="text-violet-400 hover:text-violet-300 font-semibold">Sign in here</a>
        </div>

    </div>
</div>
@endsection
