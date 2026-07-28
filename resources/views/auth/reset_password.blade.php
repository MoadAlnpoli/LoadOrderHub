@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="max-w-md mx-auto my-12">
    <div class="glass-card rounded-2xl border border-slate-800 p-8 space-y-6 shadow-2xl">
        
        <!-- Header -->
        <div class="text-center space-y-2">
            <h2 class="text-2xl font-extrabold text-white tracking-tight">Set New Password</h2>
            <p class="text-xs text-slate-400">Choose a new secure password for your account.</p>
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
        <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
            @csrf
            
            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email (Read-only/Prefilled) -->
            <div class="space-y-1">
                <label for="email" class="text-xs font-bold uppercase tracking-wider text-slate-400">Email Address</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email', $email) }}" 
                    class="w-full bg-slate-950 border border-slate-850 rounded-xl px-4 py-3 text-sm text-slate-450 focus:outline-none cursor-not-allowed"
                    readonly
                    required>
            </div>

            <!-- Password -->
            <div class="space-y-1">
                <label for="password" class="text-xs font-bold uppercase tracking-wider text-slate-400">New Password</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    class="w-full bg-slate-950 border border-slate-850 rounded-xl px-4 py-3 text-sm text-slate-200 placeholder-slate-650 focus:outline-none focus:border-violet-600"
                    placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                    required>
            </div>

            <!-- Confirm Password -->
            <div class="space-y-1">
                <label for="password_confirmation" class="text-xs font-bold uppercase tracking-wider text-slate-400">Confirm Password</label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    id="password_confirmation" 
                    class="w-full bg-slate-950 border border-slate-850 rounded-xl px-4 py-3 text-sm text-slate-200 placeholder-slate-650 focus:outline-none focus:border-violet-600"
                    placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                    required>
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-violet-600 to-blue-500 hover:from-violet-500 hover:to-blue-400 text-white font-bold text-sm tracking-wide shadow-lg shadow-violet-500/10 transition-all">
                Reset Password
            </button>
        </form>

    </div>
</div>
@endsection
