{{--
╔══════════════════════════════════════════════════════════════════╗
║  resources/views/admin/users/_modal_detail.blade.php             ║
║  Partial: overlay detail user (guru / siswa).                    ║
║  Data diisi via JS: openDetailModal(userId)                      ║
╚══════════════════════════════════════════════════════════════════╝
--}}
<div id="modalDetail"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     role="dialog" aria-modal="true" aria-labelledby="titleModalDetail">

    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
         onclick="closeModal('modalDetail')"></div>

    {{-- Panel --}}
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg
                max-h-[92vh] flex flex-col animate-modal">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 pt-5 pb-4
                    border-b border-slate-100 shrink-0">
            <div class="flex items-center gap-3">
                {{-- Avatar (diisi JS) --}}
                <div id="detailAvatarWrap"></div>
                <div>
                    <h2 id="detailName"
                        class="text-base font-bold text-slate-800 leading-tight"
                        style="font-family:'Outfit',sans-serif;"></h2>
                    <p id="detailEmail" class="text-xs text-slate-400 truncate max-w-[16rem]"></p>
                </div>
            </div>
            <button onclick="closeModal('modalDetail')"
                    class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600
                           hover:bg-slate-100 transition shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body (diisi JS) --}}
        <div id="detailBody"
             class="overflow-y-auto px-6 py-5 flex-1 space-y-5 text-sm">
        </div>

        {{-- Footer --}}
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 rounded-b-2xl shrink-0">
            <button type="button" onclick="closeModal('modalDetail')"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600
                           text-sm font-medium hover:bg-white transition">
                Tutup
            </button>
        </div>

    </div>
</div>