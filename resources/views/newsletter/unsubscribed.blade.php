@extends('layouts.app')
@section('title', 'Unsubscribed')
@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="text-center space-y-4 p-10 rounded-2xl border border-slate-800 bg-slate-900/40 max-w-md">
        <i class="fa-solid fa-envelope-open text-4xl text-slate-400"></i>
        <h1 class="text-2xl font-bold text-white">
            {{ app()->getLocale() == 'ar' ? 'تم إلغاء الاشتراك' : 'Unsubscribed' }}
        </h1>
        <p class="text-slate-400 text-sm">
            {{ app()->getLocale() == 'ar'
                ? 'لقد أُلغي اشتراكك بنجاح. لن تصلك رسائل بريدية بعد الآن.'
                : 'You have been unsubscribed successfully. You will no longer receive weekly emails.' }}
        </p>
        <a href="{{ route('home') }}" class="inline-block mt-2 px-6 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-sm font-bold transition">
            {{ app()->getLocale() == 'ar' ? 'العودة للرئيسية' : 'Back to Home' }}
        </a>
    </div>
</div>
@endsection
