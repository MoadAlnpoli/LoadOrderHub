@extends('layouts.app')

@section('title', 'Admin Review Queue')

@section('content')
<div class="min-h-[85vh] flex flex-col gap-6" dir="rtl">
    <div class="glass-card rounded-2xl border border-slate-800 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-list-check text-violet-500"></i>
                Review Queue (Auto-Published Mods)
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="text-xs text-slate-400 hover:text-white transition-colors">
                &larr; Back to Dashboard
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 rounded-xl bg-green-500/10 border border-green-500/30 text-green-400 text-xs font-bold">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left rtl:text-right text-sm">
                <thead class="bg-slate-950/60 text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-3">Mod Name</th>
                        <th class="px-6 py-3">Game</th>
                        <th class="px-6 py-3">Description</th>
                        <th class="px-6 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($mods as $mod)
                        <tr class="hover:bg-slate-850/30 transition-colors">
                            <td class="px-6 py-4 text-white font-bold">
                                {{ $mod->name }}
                                <div class="text-[10px] text-slate-500 font-normal truncate max-w-[200px] mt-1" dir="ltr">
                                    {{ $mod->nexus_url ?? $mod->steam_url ?? 'No URL' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-300 text-xs">
                                {{ $mod->game->name }}
                            </td>
                            <td class="px-6 py-4 text-slate-400 text-xs">
                                <div class="line-clamp-2 max-w-sm">
                                    {{ $mod->description }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <form action="{{ route('admin.mods.approve', $mod->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-green-600/20 text-green-400 hover:bg-green-600/40 border border-green-500/30 font-bold text-[10px] transition-colors" title="Approve">
                                            <i class="fa-solid fa-check"></i> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.mods.reject', $mod->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-600/20 text-red-400 hover:bg-red-600/40 border border-red-500/30 font-bold text-[10px] transition-colors" title="Reject">
                                            <i class="fa-solid fa-xmark"></i> Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                <i class="fa-regular fa-face-smile text-2xl mb-2 text-slate-700 block"></i>
                                No mods in the review queue. Good job!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($mods->hasPages())
            <div class="mt-4 border-t border-slate-800 pt-4">
                {{ $mods->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
