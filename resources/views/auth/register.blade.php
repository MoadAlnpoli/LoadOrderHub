@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="max-w-md mx-auto my-12">
    <div class="glass-card rounded-2xl border border-slate-800 p-8 space-y-6 shadow-2xl">
        
        <!-- Header -->
        <div class="text-center space-y-2">
            <h2 class="text-2xl font-extrabold text-white tracking-tight">Create Account</h2>
            <p class="text-xs text-slate-400">Join us to start creating and sharing modpacks.</p>
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
        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Name -->
            <div class="space-y-1">
                <label for="name" class="text-xs font-bold uppercase tracking-wider text-slate-400">Username/Name</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name') }}" 
                    class="w-full bg-slate-950 border border-slate-850 rounded-xl px-4 py-3 text-sm text-slate-200 placeholder-slate-650 focus:outline-none focus:border-violet-600"
                    placeholder="GamerXYZ"
                    required>
            </div>

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

            <!-- Password -->
            <div class="space-y-1">
                <label for="password" class="text-xs font-bold uppercase tracking-wider text-slate-400">Password</label>
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

            <!-- Terms of Service Agreement -->
            <div class="flex items-start space-x-2 rtl:space-x-reverse pt-1">
                <input 
                    type="checkbox" 
                    name="terms" 
                    id="terms" 
                    class="mt-1 w-4 h-4 rounded bg-slate-950 border-slate-850 text-violet-600 focus:ring-violet-500" 
                    required>
                <label for="terms" class="text-xs text-slate-400">
                    I agree to the <a href="#" class="text-violet-400 hover:underline">Terms of Service</a> and <a href="#" class="text-violet-400 hover:underline">Privacy Policy</a>.
                </label>
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-violet-600 to-blue-500 hover:from-violet-500 hover:to-blue-400 text-white font-bold text-sm tracking-wide shadow-lg shadow-violet-500/10 transition-all">
                Sign Up
            </button>
        </form>

        <!-- Login Link -->
        <div class="text-center text-xs text-slate-500 border-t border-slate-850 pt-4">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-violet-400 hover:text-violet-300 font-semibold">Sign in here</a>
        </div>

    </div>
</div>
@endsection
