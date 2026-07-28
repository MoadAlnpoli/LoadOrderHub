{{-- ============================================= --}}
{{-- TAB 10: SITE SETTINGS --}}
{{-- ============================================= --}}
<div id="admin-panel-settings" class="admin-tab-panel hidden space-y-6">
    <div class="glass-card rounded-2xl border border-slate-800 p-6 space-y-5">
        <h4 class="font-bold text-white text-sm"><i class="fa-solid fa-gear text-slate-300 ml-2"></i>إعدادات الموقع العامة</h4>
        <p class="text-[10px] text-slate-500">تحكم في إعدادات المنصة الأساسية، وصيانة النظام.</p>
        
        <form action="#" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] text-slate-400 font-bold block mb-1">اسم الموقع</label>
                    <input type="text" name="site_name" value="LoadOrderHub" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-violet-600">
                </div>
                <div>
                    <label class="text-[10px] text-slate-400 font-bold block mb-1">بريد التواصل</label>
                    <input type="email" name="contact_email" value="support@loadorderhub.com" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-violet-600">
                </div>
                <div class="md:col-span-2">
                    <label class="text-[10px] text-slate-400 font-bold block mb-1">وصف الموقع (SEO Description)</label>
                    <textarea name="site_description" rows="3" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-violet-600">The ultimate platform for discovering, organizing, and sharing video game mod configurations and load orders.</textarea>
                </div>
            </div>
            <button type="button" class="px-6 py-2.5 bg-gradient-to-r from-violet-600 to-blue-500 hover:from-violet-500 hover:to-blue-400 rounded-xl text-white text-xs font-bold transition-all shadow-lg shadow-violet-500/20">
                <i class="fa-solid fa-save ml-1"></i> حفظ الإعدادات
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-800 space-y-4">
            <h5 class="text-xs font-bold text-rose-400">منطقة الخطر (Danger Zone)</h5>
            <div class="flex flex-col sm:flex-row gap-3">
                <form action="/admin/cache-clear" method="POST">
                    @csrf
                    <button type="button" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl text-slate-300 text-xs font-bold transition-all">
                        <i class="fa-solid fa-broom mr-1"></i> مسح الذاكرة المؤقتة (Clear Cache)
                    </button>
                </form>
                <form action="{{ route('admin.maintenance.toggle') }}" method="POST" onsubmit="return confirm('{{ app()->isDownForMaintenance() ? 'Are you sure you want to disable maintenance mode?' : 'هل أنت متأكد من تفعيل وضع الصيانة؟ الموقع لن يكون متاحاً للزوار.' }}')">
                    @csrf
                    @if(app()->isDownForMaintenance())
                        <button type="submit" class="px-4 py-2 bg-emerald-600/20 hover:bg-emerald-600/40 border border-emerald-500/30 rounded-xl text-emerald-400 text-xs font-bold transition-all">
                            <i class="fa-solid fa-unlock mr-1"></i> تعطيل وضع الصيانة (Disable)
                        </button>
                    @else
                        <button type="submit" class="px-4 py-2 bg-rose-600/20 hover:bg-rose-600/40 border border-rose-500/30 rounded-xl text-rose-400 text-xs font-bold transition-all">
                            <i class="fa-solid fa-lock mr-1"></i> تفعيل وضع الصيانة (Enable)
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
