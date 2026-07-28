@extends('layouts.app')

@section('title', $user->name . ' - Profile')

@section('content')
<div class="space-y-8">
    <!-- Breadcrumb -->
    <nav class="flex text-xs text-slate-500 space-x-2 rtl:space-x-reverse">
        <a href="{{ route('home') }}" class="hover:text-slate-300">{{ __('messages.home') }}</a>
        <span>/</span>
        <span class="text-slate-300">{{ $user->name }}</span>
    </nav>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-xs text-emerald-400 flex items-center space-x-2 rtl:space-x-reverse shadow-lg shadow-emerald-500/5">
            <i class="fa-solid fa-circle-check text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Profile Info Card / Update form -->
        <div class="lg:col-span-1 space-y-6">
            <div class="glass-card p-6 md:p-8 rounded-3xl border border-slate-800 space-y-6">
                <!-- Avatar & Username -->
                <div class="text-center space-y-3 pb-6 border-b border-slate-850">
                    <div class="relative w-28 h-28 mx-auto rounded-full overflow-hidden bg-slate-950 border-2 border-violet-500/40">
                        @if($user->profile?->avatar)
                            <img src="{{ $user->profile->avatar }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-slate-900 flex items-center justify-center text-4xl font-extrabold text-violet-400">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <div class="space-y-1">
                        <h2 class="text-xl font-extrabold text-white">{{ $user->name }}</h2>
                        <div class="flex items-center justify-center gap-2 flex-wrap pt-1">
                            <span class="px-2.5 py-0.5 rounded-full bg-amber-500/10 border border-amber-500/20 text-[10px] font-bold text-amber-400">
                                {{ $user->badge_title ?: 'Novice Modder' }}
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full bg-violet-600/10 border border-violet-500/20 text-[10px] font-bold text-violet-400">
                                ⭐ {{ number_format($user->points ?: 100) }} {{ app()->getLocale() == 'ar' ? 'نقطة' : 'Points' }}
                            </span>
                            @if($user->is_admin)
                                <span class="px-2 py-0.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-[10px] font-bold text-emerald-400">
                                    Admin
                                </span>
                            @endif
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 italic">"{{ $user->profile?->bio ?? 'Gaming enthusiast.' }}"</p>
                </div>

                <!-- Update Form (Only visible to profile owner) -->
                @if(auth()->check() && auth()->id() === $user->id)
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4 pt-2">
                        @csrf
                        
                        <div class="space-y-1">
                            <label for="bio" class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('messages.about_me') }}</label>
                            <textarea 
                                name="bio" 
                                id="bio" 
                                rows="2" 
                                class="w-full bg-slate-950 border border-slate-850 rounded-xl p-3 text-xs text-slate-200 focus:outline-none focus:border-violet-600"
                                placeholder="Tell the community about yourself...">{{ $user->profile?->bio }}</textarea>
                        </div>

                        <div class="space-y-1">
                            <label for="phone" class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('messages.phone') }}</label>
                            <input 
                                type="text" 
                                name="phone" 
                                id="phone" 
                                value="{{ $user->profile?->phone }}" 
                                class="w-full bg-slate-950 border border-slate-850 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-violet-600">
                        </div>

                        <div class="space-y-1">
                            <label for="address" class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('messages.address') }}</label>
                            <input 
                                type="text" 
                                name="address" 
                                id="address" 
                                value="{{ $user->profile?->address }}" 
                                class="w-full bg-slate-950 border border-slate-850 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-violet-600">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block mb-1">{{ __('messages.update_avatar') }}</label>
                            <div class="relative w-full h-10 bg-slate-950 border border-slate-850 rounded-xl flex items-center justify-between px-3 text-xs text-slate-400 cursor-pointer overflow-hidden">
                                <span id="file-label" class="truncate max-w-[200px]">Choose file...</span>
                                <input 
                                    type="file" 
                                    name="avatar" 
                                    class="absolute inset-0 opacity-0 cursor-pointer w-full h-full"
                                    onchange="document.getElementById('file-label').innerText = this.files[0] ? this.files[0].name : 'Choose file...'">
                            </div>
                        <div class="border-t border-slate-850 my-4 pt-3 space-y-3">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block"><i class="fa-solid fa-lock text-violet-500 mr-1"></i> Change Password (Optional)</span>
                            
                            <div class="space-y-1">
                                <label for="password" class="text-[10px] font-bold uppercase tracking-wider text-slate-500">New Password</label>
                                <input 
                                    type="password" 
                                    name="password" 
                                    id="password" 
                                    placeholder="Leave blank to keep current" 
                                    class="w-full bg-slate-950 border border-slate-850 rounded-xl px-3 py-2 text-xs text-slate-200 placeholder-slate-700 focus:outline-none focus:border-violet-600">
                            </div>

                            <div class="space-y-1">
                                <label for="password_confirmation" class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Confirm New Password</label>
                                <input 
                                    type="password" 
                                    name="password_confirmation" 
                                    id="password_confirmation" 
                                    placeholder="Confirm new password" 
                                    class="w-full bg-slate-950 border border-slate-850 rounded-xl px-3 py-2 text-xs text-slate-200 placeholder-slate-700 focus:outline-none focus:border-violet-600">
                            </div>
                        </div>

                        <button type="submit" class="w-full py-2.5 rounded-xl bg-slate-900 border border-slate-800 hover:border-violet-600/40 text-slate-200 hover:text-white font-bold text-xs tracking-wide transition-all">
                            {{ __('messages.save_changes') }}
                        </button>
                    </form>
                @else
                    <!-- Display profile fields for public view -->
                    <div class="space-y-3 pt-2 text-xs">
                        <div class="flex justify-between py-2 border-b border-slate-850">
                            <span class="text-slate-500">{{ __('messages.address') }}</span>
                            <span class="text-slate-300 font-medium">{{ $user->profile?->address ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-slate-850">
                            <span class="text-slate-500">{{ __('messages.phone') }}</span>
                            <span class="text-slate-300 font-medium">{{ $user->profile?->phone ?? '-' }}</span>
                        </div>
                    </div>
                @endif

            </div>
        </div>

        <!-- Left: Activity Tabs -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Tabs Controls -->
            <div class="flex border-b border-slate-800 space-x-6 rtl:space-x-reverse text-sm">
                <button onclick="switchTab('saved')" id="tab-btn-saved" class="py-3 border-b-2 border-violet-500 font-bold text-white transition-all">
                    <i class="fa-regular fa-bookmark mr-1.5 rtl:ml-1.5"></i> {{ __('messages.saved_packs') }} ({{ $savedPacks->count() }})
                </button>
                <button onclick="switchTab('upvoted')" id="tab-btn-upvoted" class="py-3 border-b-2 border-transparent font-medium text-slate-400 hover:text-white transition-all">
                    <i class="fa-regular fa-thumbs-up mr-1.5 rtl:ml-1.5"></i> {{ __('messages.upvoted_packs') }} ({{ $user->ratings->count() }})
                </button>
                <button onclick="switchTab('comments')" id="tab-btn-comments" class="py-3 border-b-2 border-transparent font-medium text-slate-400 hover:text-white transition-all">
                    <i class="fa-regular fa-comments mr-1.5 rtl:ml-1.5"></i> {{ __('messages.comment_history') }} ({{ $user->comments->count() }})
                </button>
            </div>

            <!-- Tab Content Panel -->
            <div class="space-y-4">
                
                <!-- Saved Packs Panel -->
                <div id="panel-saved" class="tab-panel space-y-4">
                    @forelse($savedPacks as $pack)
                        <div class="glass-card p-5 rounded-2xl border border-slate-800 flex items-center justify-between hover:border-violet-500/30 transition-all">
                            <div class="space-y-2 text-right rtl:text-right">
                                <span class="px-2 py-0.5 rounded bg-slate-900 border border-slate-850 text-[10px] font-bold text-slate-400">
                                    {{ $pack->gameVersion->game->name }} ({{ $pack->gameVersion->version }})
                                </span>
                                <h4 class="font-bold text-white hover:text-violet-400 transition-colors">
                                    <a href="{{ route('modpacks.show', $pack->id) }}">
                                        {{ app()->getLocale() == 'ar' ? $pack->title_ar : $pack->title_en }}
                                    </a>
                                </h4>
                                <div class="text-[11px] text-slate-500">
                                    <span>{{ $pack->mods_count }} mods</span> &bull; <span>{{ $pack->views_count }} views</span>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-slate-600 rtl:rotate-180"></i>
                        </div>
                    @empty
                        <div class="py-12 text-center text-slate-650 text-xs border border-dashed border-slate-850 rounded-2xl">
                            <i class="fa-regular fa-bookmark text-2xl mb-2 text-slate-700 block"></i>
                            {{ app()->getLocale() == 'ar' ? 'لا توجد تجميعات محفوظة بعد.' : 'No saved modpacks yet.' }}
                        </div>
                    @endforelse
                </div>

                <!-- Upvoted Packs Panel -->
                <div id="panel-upvoted" class="tab-panel hidden space-y-4">
                    @forelse($user->ratings as $rating)
                        @if($rating->modPack)
                            <div class="glass-card p-5 rounded-2xl border border-slate-800 flex items-center justify-between hover:border-violet-500/30 transition-all">
                                <div class="space-y-2 text-right rtl:text-right">
                                    <span class="px-2 py-0.5 rounded bg-slate-900 border border-slate-850 text-[10px] font-bold text-slate-400">
                                        {{ $rating->modPack->gameVersion->game->name }} ({{ $rating->modPack->gameVersion->version }})
                                    </span>
                                    <h4 class="font-bold text-white hover:text-violet-400 transition-colors">
                                        <a href="{{ route('modpacks.show', $rating->modPack->id) }}">
                                            {{ app()->getLocale() == 'ar' ? $rating->modPack->title_ar : $rating->modPack->title_en }}
                                        </a>
                                    </h4>
                                    <div class="text-[11px] text-slate-500">
                                        <span class="text-emerald-400 font-bold"><i class="fa-solid fa-thumbs-up mr-1 rtl:ml-1"></i> {{ __('messages.upvote') }}</span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-chevron-right text-slate-600 rtl:rotate-180"></i>
                            </div>
                        @endif
                    @empty
                        <div class="py-12 text-center text-slate-650 text-xs border border-dashed border-slate-850 rounded-2xl">
                            <i class="fa-regular fa-thumbs-up text-2xl mb-2 text-slate-700 block"></i>
                            {{ app()->getLocale() == 'ar' ? 'لا توجد تجميعات مقيمة بعد.' : 'No upvoted modpacks yet.' }}
                        </div>
                    @endforelse
                </div>

                <!-- Comments History Panel -->
                <div id="panel-comments" class="tab-panel hidden space-y-4">
                    @forelse($user->comments as $comment)
                        @if($comment->modPack)
                            <div class="glass-card p-5 rounded-2xl border border-slate-800 space-y-3">
                                <div class="flex items-center justify-between text-[11px] text-slate-500">
                                    <span>
                                        {{ app()->getLocale() == 'ar' ? 'علق على:' : 'Commented on:' }} 
                                        <a href="{{ route('modpacks.show', $comment->modPack->id) }}" class="text-violet-400 hover:underline font-bold">
                                            {{ app()->getLocale() == 'ar' ? $comment->modPack->title_ar : $comment->modPack->title_en }}
                                        </a>
                                    </span>
                                    <span>{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-slate-300 bg-slate-950/40 p-3 rounded-xl border border-slate-850/80 leading-relaxed text-right rtl:text-right">
                                    {{ $comment->content }}
                                </p>
                            </div>
                        @endif
                    @empty
                        <div class="py-12 text-center text-slate-650 text-xs border border-dashed border-slate-850 rounded-2xl">
                            <i class="fa-regular fa-comment-dots text-2xl mb-2 text-slate-700 block"></i>
                            {{ app()->getLocale() == 'ar' ? 'لا يوجد سجل تعليقات بعد.' : 'No comment history found.' }}
                        </div>
                    @endforelse
                </div>

            </div>

        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    // Tab switching engine
    function switchTab(tabName) {
        // Hide all panels
        document.querySelectorAll('.tab-panel').forEach(panel => {
            panel.classList.add('hidden');
        });
        
        // Show selected panel
        document.getElementById(`panel-${tabName}`).classList.remove('hidden');

        // Reset all tab button styles
        document.querySelectorAll('[id^="tab-btn-"]').forEach(btn => {
            btn.classList.remove('border-violet-500', 'text-white');
            btn.classList.add('border-transparent', 'text-slate-400', 'font-medium');
        });

        // Set active tab button style
        const activeBtn = document.getElementById(`tab-btn-${tabName}`);
        activeBtn.classList.remove('border-transparent', 'text-slate-400', 'font-medium');
        activeBtn.classList.add('border-violet-500', 'text-white', 'font-bold');
    }
</script>
@endsection
