{{-- resources/views/admin/users/_modal_edit.blade.php --}}
<div id="modalEditUser"
    class="fixed inset-0 z-50 hidden items-center justify-center p-4"
    role="dialog" aria-modal="true" aria-labelledby="titleEditUser">

    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('modalEditUser')"></div>

    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[94vh] flex flex-col">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-slate-100 dark:border-slate-700 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h2 id="titleEditUser" class="text-base font-bold text-slate-800 dark:text-slate-100 leading-tight">Edit User</h2>
                    <p id="editUserSubtitle" class="text-xs text-slate-400 mt-0.5"></p>
                </div>
            </div>
            <button onclick="closeModal('modalEditUser')" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-700 transition shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="overflow-y-auto px-6 py-5 flex-1">
            <form id="formEditUser" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                <input type="hidden" name="_form_context" value="edit">

                {{-- Info Akun --}}
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="h-px flex-1 bg-slate-100 dark:bg-slate-700"></div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Info Akun</span>
                        <div class="h-px flex-1 bg-slate-100 dark:bg-slate-700"></div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Nama <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="edit_name" required class="w-full rounded-xl border border-slate-200 dark:border-slate-600 px-3 py-2 text-sm bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="edit_email" required class="w-full rounded-xl border border-slate-200 dark:border-slate-600 px-3 py-2 text-sm bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                        </div>
                    </div>
                </div>

                {{-- SECTION GURU --}}
                <div id="editSectionGuru" class="hidden space-y-4">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="h-px flex-1 bg-slate-100 dark:bg-slate-700"></div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Data Guru</span>
                        <div class="h-px flex-1 bg-slate-100 dark:bg-slate-700"></div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">NIP</label>
                            <input type="text" name="nip" id="edit_nip" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 px-3 py-2 text-sm bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Jenis Kelamin</label>
                            <select name="jk" id="edit_jk_guru" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 px-3 py-2 text-sm bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                                <option value="">— Pilih —</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <!-- Field guru lainnya tetap sama seperti kode lama Anda -->
                        <!-- ... (singkat untuk ruang, saya sarankan copy dari kode lama Anda) ... -->
                    </div>
                </div>

                {{-- SECTION SISWA - KELAS DARI STUDY GROUP --}}
                <div id="editSectionSiswa" class="hidden space-y-4">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="h-px flex-1 bg-slate-100 dark:bg-slate-700"></div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Data Siswa</span>
                        <div class="h-px flex-1 bg-slate-100 dark:bg-slate-700"></div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">NIS/NIPD</label>
                            <input type="text" name="nidn" id="edit_nidn" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 px-3 py-2 text-sm bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">NIK</label>
                            <input type="text" name="nik" id="edit_nik" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 px-3 py-2 text-sm bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Jenis Kelamin</label>
                            <select name="jk" id="edit_jk_siswa" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 px-3 py-2 text-sm bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                                <option value="">— Pilih —</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" id="edit_tempat_lahir_siswa" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 px-3 py-2 text-sm bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" id="edit_tgl_lahir" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 px-3 py-2 text-sm bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Agama</label>
                            <select name="agama" id="edit_agama" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 px-3 py-2 text-sm bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                                <option value="">— Pilih —</option>
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Konghucu">Konghucu</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">No. Telepon</label>
                            <input type="text" name="no_telp" id="edit_no_telp" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 px-3 py-2 text-sm bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">SKHUN</label>
                            <input type="text" name="shkun" id="edit_shkun" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 px-3 py-2 text-sm bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                        </div>

                        {{-- Kelas dari Data Akademik --}}
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Kelas</label>
                            @if(isset($kelasList) && $kelasList->count())
                            <select name="kelas_id" id="edit_kelas_siswa"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-600 px-3 py-2 text-sm bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                                <option value="">— Belum ada kelas —</option>
                                @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}">
                                    {{ $kelas->name }}
                                    @if($kelas->grade) — Kelas {{ $kelas->grade }}{{ $kelas->section ? ' '.$kelas->section : '' }} @endif
                                </option>
                                @endforeach
                            </select>
                            @else
                            <div class="px-3 py-3 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 rounded-xl text-xs text-amber-700 dark:text-amber-400">
                                Belum ada kelas. Tambahkan di <a href="{{ route('admin.kelas.index') }}" class="underline font-medium">Kelola Kelas</a>.
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div class="flex items-center gap-2 mt-4 mb-3">
                        <div class="h-px flex-1 bg-slate-100 dark:bg-slate-700"></div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Alamat</span>
                        <div class="h-px flex-1 bg-slate-100 dark:bg-slate-700"></div>
                    </div>
                    <!-- Field alamat dan KPS tetap sama seperti kode lama Anda -->
                    <!-- ... (copy field alamat, RT, RW, dusun, dll dari kode sebelumnya) ... -->

                </div>

            </form>
        </div>

        {{-- Footer --}}
        <div class="flex gap-2 px-6 py-4 border-t border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/20 rounded-b-2xl shrink-0">
            <button type="button" onclick="closeModal('modalEditUser')"
                class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-400 text-sm font-medium hover:bg-white dark:hover:bg-slate-700 transition">
                Batal
            </button>
            <button type="submit" form="formEditUser"
                class="flex-1 px-4 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 active:scale-95 transition">
                Simpan Perubahan
            </button>
        </div>

    </div>
</div>

@push('scripts')
<script>
    function populateEditModal(data) {
        const user = data.user;
        const profile = data.profile;
        const role = data.role;

        document.getElementById('formEditUser').action = `/admin/users/${user.id}`;
        document.getElementById('editUserSubtitle').textContent = `Mengedit ${role === 'guru' ? 'Guru' : 'Siswa'}: ${profile?.nama ?? user.name}`;

        document.getElementById('editSectionGuru').classList.add('hidden');
        document.getElementById('editSectionSiswa').classList.add('hidden');

        document.getElementById('edit_name').value = user.name ?? '';
        document.getElementById('edit_email').value = user.email ?? '';

        const val = (id, v) => {
            const el = document.getElementById(id);
            if (el) el.value = v ?? '';
        };

        if (role === 'guru') {
            document.getElementById('editSectionGuru').classList.remove('hidden');
            // Isi field guru di sini (sama seperti kode lama)
        } else if (role === 'siswa') {
            document.getElementById('editSectionSiswa').classList.remove('hidden');

            val('edit_nidn', profile?.nidn);
            val('edit_nik', profile?.nik);
            val('edit_jk_siswa', profile?.jk);
            val('edit_tempat_lahir_siswa', profile?.tempat_lahir);
            val('edit_tgl_lahir', profile?.tgl_lahir);
            val('edit_agama', profile?.agama);
            val('edit_no_telp', profile?.no_telp);
            val('edit_shkun', profile?.shkun);
            val('edit_kelas_siswa', profile?.kelas_id); // ← Kelas dari StudyGroup

            // Field alamat
            val('edit_alamat', profile?.alamat);
            val('edit_rt', profile?.rt);
            val('edit_rw', profile?.rw);
            val('edit_dusun', profile?.dusun);
            val('edit_kecamatan', profile?.kecamatan);
            val('edit_kode_pos', profile?.kode_pos);
            val('edit_jenis_tinggal', profile?.jenis_tinggal);
            val('edit_transportasi', profile?.jalan_transportasi);
            val('edit_penerima_kps', profile?.penerima_kps ?? 'Tidak');
            val('edit_no_kps', profile?.no_kps);
        }
    }
</script>
@endpush