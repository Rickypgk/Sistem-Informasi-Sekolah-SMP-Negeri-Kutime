{{-- resources/views/admin/kelas/_modal_edit.blade.php --}}
<div id="modalEditKelas"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     role="dialog" aria-modal="true">

    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
         onclick="closeModal('modalEditKelas')"></div>

    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-sm
                max-h-[92vh] flex flex-col animate-modal">

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100
                    dark:border-slate-700 shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-amber-100 dark:bg-amber-900/30
                            flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2
                                 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100 leading-tight">
                        Edit Kelas
                    </h2>
                    <p class="text-[10px] text-slate-400 mt-0.5">Perbarui informasi kelas</p>
                </div>
            </div>
            <button onclick="closeModal('modalEditKelas')"
                    class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600
                           hover:bg-slate-100 dark:hover:bg-slate-700 transition shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="overflow-y-auto px-5 py-4 flex-1">
            <form id="formEditKelas" method="POST" class="space-y-3.5">
                @csrf
                @method('PUT')

                {{-- Nama Kelas --}}
                <div>
                    <label for="editKelasNama"
                           class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                  uppercase tracking-wide mb-1">
                        Nama Kelas <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" id="editKelasNama"
                           required placeholder="mis. Kelas 7A"
                           class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                  px-3 py-2 text-xs transition
                                  focus:outline-none focus:ring-2 focus:ring-indigo-300
                                  bg-white dark:bg-slate-700 dark:text-slate-200">
                </div>

                {{-- Tingkat --}}
                <div>
                    <label for="editKelasTingkat"
                           class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                  uppercase tracking-wide mb-1">
                        Tingkat <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="tingkat" id="editKelasTingkat"
                           required placeholder="mis. 7"
                           class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                  px-3 py-2 text-xs transition
                                  focus:outline-none focus:ring-2 focus:ring-indigo-300
                                  bg-white dark:bg-slate-700 dark:text-slate-200">
                </div>

                {{-- Tahun Ajaran --}}
                <div>
                    <label for="editKelasTahun"
                           class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                  uppercase tracking-wide mb-1">
                        Tahun Ajaran <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="tahun_ajaran" id="editKelasTahun"
                           required placeholder="mis. 2024/2025"
                           class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                  px-3 py-2 text-xs transition
                                  focus:outline-none focus:ring-2 focus:ring-indigo-300
                                  bg-white dark:bg-slate-700 dark:text-slate-200">
                </div>

                {{-- Wali Kelas --}}
                <div>
                    <label for="editKelasGuru"
                           class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                  uppercase tracking-wide mb-1">
                        Wali Kelas
                        <span class="normal-case font-normal text-slate-400">(opsional)</span>
                    </label>
                    <select name="guru_id" id="editKelasGuru"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                   px-3 py-2 text-xs transition
                                   focus:outline-none focus:ring-2 focus:ring-indigo-300
                                   bg-white dark:bg-slate-700 dark:text-slate-200">
                        <option value="">— Pilih wali kelas —</option>
                        @foreach($gurus as $guru)
                            <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                        @endforeach
                    </select>
                </div>

            </form>
        </div>

        {{-- Footer --}}
        <div class="flex gap-2 px-5 py-3.5 border-t border-slate-100 dark:border-slate-700
                    bg-slate-50/50 dark:bg-slate-900/20 rounded-b-2xl shrink-0">
            <button type="button" onclick="closeModal('modalEditKelas')"
                    class="flex-1 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                           text-slate-600 dark:text-slate-400 text-xs font-medium
                           hover:bg-white dark:hover:bg-slate-700 transition">
                Batal
            </button>
            <button type="submit" form="formEditKelas"
                    class="flex-1 px-4 py-2 rounded-xl bg-amber-500 text-white text-xs font-semibold
                           hover:bg-amber-600 active:scale-95 transition">
                Simpan Perubahan
            </button>
        </div>

    </div>
</div>