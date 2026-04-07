{{-- resources/views/admin/kelas/_modal_tambah.blade.php --}}
<div id="modalTambahKelas"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     role="dialog" aria-modal="true">

    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
         onclick="closeModal('modalTambahKelas')"></div>

    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-sm
                max-h-[92vh] flex flex-col animate-modal">

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100
                    dark:border-slate-700 shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-indigo-100 dark:bg-indigo-900/40
                            flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9
                                 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1
                                 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100 leading-tight">
                        Tambah Kelas Baru
                    </h2>
                    <p class="text-[10px] text-slate-400 mt-0.5">Isi data kelas yang akan ditambahkan</p>
                </div>
            </div>
            <button onclick="closeModal('modalTambahKelas')"
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
            <form id="formTambahKelas"
                  action="{{ route('admin.kelas.store') }}"
                  method="POST"
                  class="space-y-3.5">
                @csrf

                {{-- Nama Kelas --}}
                <div>
                    <label for="tambahKelasNama"
                           class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                  uppercase tracking-wide mb-1">
                        Nama Kelas <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" id="tambahKelasNama"
                           value="{{ old('nama') }}" required
                           placeholder="mis. Kelas 7A"
                           class="w-full rounded-xl border px-3 py-2 text-xs transition
                                  focus:outline-none focus:ring-2 focus:ring-indigo-300
                                  dark:bg-slate-700 dark:text-slate-200
                                  @error('nama') border-red-400 bg-red-50 @else border-slate-200 dark:border-slate-600 @enderror">
                    @error('nama')
                        <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tingkat --}}
                <div>
                    <label for="tambahKelasTingkat"
                           class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                  uppercase tracking-wide mb-1">
                        Tingkat <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="tingkat" id="tambahKelasTingkat"
                           value="{{ old('tingkat') }}" required
                           placeholder="mis. 7"
                           class="w-full rounded-xl border px-3 py-2 text-xs transition
                                  focus:outline-none focus:ring-2 focus:ring-indigo-300
                                  dark:bg-slate-700 dark:text-slate-200
                                  @error('tingkat') border-red-400 bg-red-50 @else border-slate-200 dark:border-slate-600 @enderror">
                    @error('tingkat')
                        <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tahun Ajaran --}}
                <div>
                    <label for="tambahKelasTahun"
                           class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                  uppercase tracking-wide mb-1">
                        Tahun Ajaran <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="tahun_ajaran" id="tambahKelasTahun"
                           value="{{ old('tahun_ajaran') }}" required
                           placeholder="mis. 2024/2025"
                           class="w-full rounded-xl border px-3 py-2 text-xs transition
                                  focus:outline-none focus:ring-2 focus:ring-indigo-300
                                  dark:bg-slate-700 dark:text-slate-200
                                  @error('tahun_ajaran') border-red-400 bg-red-50 @else border-slate-200 dark:border-slate-600 @enderror">
                    @error('tahun_ajaran')
                        <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Wali Kelas --}}
                <div>
                    <label for="tambahKelasGuru"
                           class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                  uppercase tracking-wide mb-1">
                        Wali Kelas
                        <span class="normal-case font-normal text-slate-400">(opsional)</span>
                    </label>
                    <select name="guru_id" id="tambahKelasGuru"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                   px-3 py-2 text-xs transition
                                   focus:outline-none focus:ring-2 focus:ring-indigo-300
                                   bg-white dark:bg-slate-700 dark:text-slate-200">
                        <option value="">— Pilih wali kelas —</option>
                        @foreach($gurus as $guru)
                            <option value="{{ $guru->id }}"
                                    {{ old('guru_id') == $guru->id ? 'selected' : '' }}>
                                {{ $guru->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('guru_id')
                        <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </form>
        </div>

        {{-- Footer --}}
        <div class="flex gap-2 px-5 py-3.5 border-t border-slate-100 dark:border-slate-700
                    bg-slate-50/50 dark:bg-slate-900/20 rounded-b-2xl shrink-0">
            <button type="button" onclick="closeModal('modalTambahKelas')"
                    class="flex-1 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                           text-slate-600 dark:text-slate-400 text-xs font-medium
                           hover:bg-white dark:hover:bg-slate-700 transition">
                Batal
            </button>
            <button type="submit" form="formTambahKelas"
                    class="flex-1 px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-semibold
                           hover:bg-indigo-700 active:scale-95 transition">
                Simpan
            </button>
        </div>

    </div>
</div>