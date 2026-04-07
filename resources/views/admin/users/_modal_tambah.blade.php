{{--
╔══════════════════════════════════════════════════════════════════╗
║  resources/views/admin/users/_modal_tambah.blade.php             ║
║  Tambah user baru — dengan dropdown pilih kelas untuk siswa      ║
╚══════════════════════════════════════════════════════════════════╝

Variabel yang dibutuhkan dari parent view (admin.users.index):
  $kelasList  → Collection<Kelas>  (dikirim dari UserController::index())
--}}

<div id="modalTambahUser"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     role="dialog" aria-modal="true" aria-labelledby="titleTambahUser">

    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
         onclick="closeModal('modalTambahUser')"></div>

    {{-- Panel --}}
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md
                max-h-[92vh] flex flex-col animate-modal">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 pt-5 pb-4
                    border-b border-slate-100 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-100 flex items-center
                            justify-center shrink-0">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0
                                 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <div>
                    <h2 id="titleTambahUser"
                        class="text-base font-bold text-slate-800 leading-tight">
                        Tambah User Baru
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">
                        Buat akun login untuk guru atau siswa
                    </p>
                </div>
            </div>
            <button onclick="closeModal('modalTambahUser')"
                    class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600
                           hover:bg-slate-100 transition shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="overflow-y-auto px-6 py-5 flex-1 space-y-4">

            {{-- Info banner --}}
            <div class="flex items-start gap-2.5 px-3 py-3 bg-indigo-50
                        border border-indigo-100 rounded-xl text-xs text-indigo-700">
                <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0
                             012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0
                             00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <span>
                    Admin membuat akun login. Data diri lengkap dilengkapi pengguna
                    melalui dashboard masing-masing setelah login.
                </span>
            </div>

            <form id="formTambahUser"
                  action="{{ route('admin.users.store') }}"
                  method="POST"
                  class="space-y-4">
                @csrf

                {{-- ── Role Selector ────────────────────────────────── --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-2">

                        {{-- Guru --}}
                        <label id="roleCardGuru"
                               class="role-card flex items-center gap-2.5 px-3 py-3 rounded-xl
                                      border-2 cursor-pointer transition-all
                                      border-indigo-500 bg-indigo-50">
                            <input type="radio" name="role" value="guru" class="sr-only" checked>
                            <div class="rc-icon w-8 h-8 rounded-lg bg-indigo-100 flex items-center
                                        justify-center shrink-0">
                                <svg class="w-4 h-4 text-indigo-600" fill="none"
                                     stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0
                                             00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="rc-title text-sm font-semibold text-indigo-700">
                                    Guru
                                </p>
                                <p class="rc-sub text-xs text-indigo-400">Tenaga pengajar</p>
                            </div>
                        </label>

                        {{-- Siswa --}}
                        <label id="roleCardSiswa"
                               class="role-card flex items-center gap-2.5 px-3 py-3 rounded-xl
                                      border-2 cursor-pointer transition-all
                                      border-slate-200 bg-white">
                            <input type="radio" name="role" value="siswa" class="sr-only">
                            <div class="rc-icon w-8 h-8 rounded-lg bg-slate-100 flex items-center
                                        justify-center shrink-0">
                                <svg class="w-4 h-4 text-slate-500" fill="none"
                                     stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168
                                             5.477 3 6.253v13C4.168 18.477 5.754 18 7.5
                                             18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5
                                             16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832
                                             18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5
                                             1.253"/>
                                </svg>
                            </div>
                            <div>
                                <p class="rc-title text-sm font-semibold text-slate-600">
                                    Siswa
                                </p>
                                <p class="rc-sub text-xs text-slate-400">Peserta didik</p>
                            </div>
                        </label>
                    </div>
                    @error('role')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ── Nama ──────────────────────────────────────────── --}}
                <div>
                    <label for="inp_name"
                           class="block text-sm font-medium text-slate-700 mb-1">
                        Nama <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="inp_name"
                           value="{{ old('name') }}" required
                           placeholder="Nama lengkap pengguna"
                           class="w-full rounded-xl border px-3 py-2.5 text-sm transition
                                  focus:outline-none focus:ring-2 focus:ring-indigo-300
                                  @error('name') border-red-400 bg-red-50
                                  @else border-slate-200 @enderror">
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ── Email ─────────────────────────────────────────── --}}
                <div>
                    <label for="inp_email"
                           class="block text-sm font-medium text-slate-700 mb-1">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="inp_email"
                           value="{{ old('email') }}" required
                           placeholder="contoh@sekolah.sch.id"
                           class="w-full rounded-xl border px-3 py-2.5 text-sm transition
                                  focus:outline-none focus:ring-2 focus:ring-indigo-300
                                  @error('email') border-red-400 bg-red-50
                                  @else border-slate-200 @enderror">
                    @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ── Pilih Kelas (hanya muncul saat role = siswa) ──── --}}
                <div id="kelasFieldWrap" style="display:none;">
                    <label for="inp_kelas"
                           class="block text-sm font-medium text-slate-700 mb-1">
                        Kelas
                        <span class="text-slate-400 text-xs font-normal">(opsional)</span>
                    </label>

                    @if(isset($kelasList) && $kelasList->count())
                        <select name="kelas_id" id="inp_kelas"
                                class="w-full rounded-xl border border-slate-200 px-3 py-2.5
                                       text-sm bg-white focus:outline-none focus:ring-2
                                       focus:ring-indigo-300 transition">
                            <option value="">— Pilih Kelas (opsional) —</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}"
                                        {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama }}
                                    @if($kelas->tingkat)
                                        — Tingkat {{ $kelas->tingkat }}
                                    @endif
                                    @if($kelas->tahun_ajaran)
                                        ({{ $kelas->tahun_ajaran }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    @else
                        {{-- Fallback: belum ada kelas yang dibuat --}}
                        <div class="flex items-start gap-2 px-3 py-3 bg-amber-50
                                    border border-amber-200 rounded-xl text-xs text-amber-700">
                            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none"
                                 stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71
                                         3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                            </svg>
                            <span>
                                Belum ada kelas yang dibuat. Buat kelas terlebih dahulu di
                                <a href="{{ route('admin.kelas.index') }}"
                                   class="font-semibold underline hover:text-amber-900"
                                   target="_blank">
                                    Kelola Kelas
                                </a>.
                                Setelah kelas dibuat, kelas akan tampil di sini.
                            </span>
                        </div>
                        {{-- Hidden input agar validasi tidak error --}}
                        <input type="hidden" name="kelas_id" value="">
                    @endif

                    @error('kelas_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ── Password ──────────────────────────────────────── --}}
                <div>
                    <label for="pwdNew"
                           class="block text-sm font-medium text-slate-700 mb-1">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="pwdNew"
                               required placeholder="Min. 8 karakter"
                               class="w-full rounded-xl border px-3 py-2.5 pr-10 text-sm
                                      transition focus:outline-none focus:ring-2
                                      focus:ring-indigo-300
                                      @error('password') border-red-400 bg-red-50
                                      @else border-slate-200 @enderror">
                        <button type="button" onclick="togglePwd('pwdNew')" tabindex="-1"
                                class="absolute right-3 top-1/2 -translate-y-1/2
                                       text-slate-400 hover:text-slate-600 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268
                                         2.943 9.542 7-1.274 4.057-5.064 7-9.542
                                         7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ── Konfirmasi Password ───────────────────────────── --}}
                <div>
                    <label for="pwdConfirm"
                           class="block text-sm font-medium text-slate-700 mb-1">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="pwdConfirm"
                               required placeholder="Ulangi password"
                               class="w-full rounded-xl border border-slate-200 px-3
                                      py-2.5 pr-10 text-sm transition focus:outline-none
                                      focus:ring-2 focus:ring-indigo-300">
                        <button type="button" onclick="togglePwd('pwdConfirm')" tabindex="-1"
                                class="absolute right-3 top-1/2 -translate-y-1/2
                                       text-slate-400 hover:text-slate-600 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268
                                         2.943 9.542 7-1.274 4.057-5.064 7-9.542
                                         7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

            </form>
        </div>

        {{-- Footer --}}
        <div class="flex gap-2 px-6 py-4 border-t border-slate-100
                    bg-slate-50/50 rounded-b-2xl shrink-0">
            <button type="button" onclick="closeModal('modalTambahUser')"
                    class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200
                           text-slate-600 text-sm font-medium hover:bg-white transition">
                Batal
            </button>
            <button type="submit" form="formTambahUser"
                    class="flex-1 px-4 py-2.5 rounded-xl bg-indigo-600 text-white
                           text-sm font-semibold hover:bg-indigo-700
                           active:scale-95 transition">
                Buat Akun
            </button>
        </div>

    </div>
</div>

{{-- ── Script: tampilkan/sembunyikan field Kelas sesuai role ─── --}}
@push('scripts')
<script>
(function () {
    'use strict';

    const kelasWrap   = document.getElementById('kelasFieldWrap');
    const roleInputs  = document.querySelectorAll('#formTambahUser input[name="role"]');
    const roleCards   = document.querySelectorAll('#formTambahUser .role-card');

    // ── Tampilkan / sembunyikan dropdown kelas ──────────────────
    function toggleKelas(role) {
        if (!kelasWrap) return;
        const show = role === 'siswa';
        kelasWrap.style.display  = show ? 'block' : 'none';
        kelasWrap.style.animation = show ? 'fadeSlideIn .2s ease' : '';
    }

    // ── Style kartu role (aktif / nonaktif) ─────────────────────
    function styleRoleCards(selectedRole) {
        roleCards.forEach(card => {
            const input  = card.querySelector('input[type="radio"]');
            const isActive = input && input.value === selectedRole;
            const icon   = card.querySelector('.rc-icon');
            const title  = card.querySelector('.rc-title');
            const sub    = card.querySelector('.rc-sub');
            const svg    = icon ? icon.querySelector('svg') : null;

            if (isActive) {
                card.classList.add('border-indigo-500', 'bg-indigo-50');
                card.classList.remove('border-slate-200', 'bg-white');
                if (title) { title.classList.add('text-indigo-700'); title.classList.remove('text-slate-600'); }
                if (sub)   { sub.classList.add('text-indigo-400');   sub.classList.remove('text-slate-400'); }
                if (icon)  { icon.classList.add('bg-indigo-100');     icon.classList.remove('bg-slate-100'); }
                if (svg)   { svg.classList.add('text-indigo-600');    svg.classList.remove('text-slate-500'); }
            } else {
                card.classList.remove('border-indigo-500', 'bg-indigo-50');
                card.classList.add('border-slate-200', 'bg-white');
                if (title) { title.classList.remove('text-indigo-700'); title.classList.add('text-slate-600'); }
                if (sub)   { sub.classList.remove('text-indigo-400');   sub.classList.add('text-slate-400'); }
                if (icon)  { icon.classList.remove('bg-indigo-100');     icon.classList.add('bg-slate-100'); }
                if (svg)   { svg.classList.remove('text-indigo-600');    svg.classList.add('text-slate-500'); }
            }
        });
    }

    // ── Klik kartu role ─────────────────────────────────────────
    roleCards.forEach(card => {
        card.addEventListener('click', () => {
            const input = card.querySelector('input[type="radio"]');
            if (!input) return;
            input.checked = true;
            toggleKelas(input.value);
            styleRoleCards(input.value);
        });
    });

    // ── Inisialisasi saat modal pertama kali dibuka ─────────────
    const checkedRole = document.querySelector('#formTambahUser input[name="role"]:checked');
    if (checkedRole) {
        toggleKelas(checkedRole.value);
        styleRoleCards(checkedRole.value);
    }

    // ── Jika ada old() error, buka modal & set role lama ───────
    @if($errors->any() && old('role'))
        const oldRole = '{{ old('role') }}';
        const oldInput = document.querySelector(`#formTambahUser input[name="role"][value="${oldRole}"]`);
        if (oldInput) { oldInput.checked = true; toggleKelas(oldRole); styleRoleCards(oldRole); }
    @endif
})();
</script>

<style>
@keyframes fadeSlideIn {
    from { opacity: 0; transform: translateY(-6px); }
    to   { opacity: 1; transform: translateY(0); }
}
</style>
@endpush