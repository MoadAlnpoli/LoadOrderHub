{{-- ============================================= --}}
{{-- TAB 11: NEWSLETTER --}}
{{-- ============================================= --}}
<div id="admin-panel-newsletter" class="admin-tab-panel hidden space-y-6">
    <div class="glass-card rounded-2xl border border-slate-800 p-6 space-y-5">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div>
                <h4 class="font-bold text-white text-sm"><i class="fa-solid fa-envelope-open-text text-teal-400 ml-2"></i>القائمة البريدية (Newsletter)</h4>
                <p class="text-[10px] text-slate-500 mt-1">إدارة المشتركين في النشرة البريدية وإرسال تحديثات للمشتركين.</p>
            </div>
            <button type="button" class="px-4 py-2 bg-teal-600/20 hover:bg-teal-600/40 border border-teal-500/30 rounded-xl text-teal-400 text-xs font-bold transition-all flex items-center gap-2">
                <i class="fa-solid fa-paper-plane"></i> إرسال حملة بريدية
            </button>
        </div>

        @php
            $subscribers = [];
            if (\Illuminate\Support\Facades\Schema::hasTable('newsletter_subscribers')) {
                $subscribers = \App\Models\NewsletterSubscriber::orderBy('created_at', 'desc')->paginate(20) ?? collect();
            }
        @endphp

        <div class="glass-card rounded-xl border border-slate-800 overflow-hidden mt-4">
            <div class="p-3 border-b border-slate-800 flex justify-between items-center">
                <h5 class="text-xs font-bold text-white">قائمة المشتركين</h5>
                <span class="px-2 py-0.5 bg-slate-800 text-slate-300 rounded text-[10px]">{{ count($subscribers) }} مشترك</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead class="bg-slate-950/60 text-slate-400 border-b border-slate-800">
                        <tr>
                            <th class="px-4 py-3">البريد الإلكتروني</th>
                            <th class="px-4 py-3">تاريخ الاشتراك</th>
                            <th class="px-4 py-3 text-left">إجراء</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50">
                        @forelse($subscribers as $sub)
                        <tr class="hover:bg-slate-800/30">
                            <td class="px-4 py-3 font-mono text-slate-300">{{ $sub->email }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $sub->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3 text-left">
                                <form action="#" method="POST" class="inline">
                                    @csrf
                                    <button type="button" class="text-red-500 hover:text-red-400 text-[10px] font-bold">إزالة</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-slate-500">لا يوجد مشتركون في القائمة البريدية حتى الآن.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
