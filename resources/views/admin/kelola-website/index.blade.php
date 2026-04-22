@extends('layouts.app')

@section('title', 'Kelola Website')

@section('content')

<div
    class="max-w-4xl mx-auto px-4 py-4"
    x-data="kelolaWebsite()"
    @close-galeri-modal.window="closeModal()"
>

    <div class="space-y-4">

        {{-- HEADER --}}
        <div>
            <h1 class="text-base font-semibold text-slate-900">
                Kelola Website Resmi Sekolah
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Atur tampilan halaman publik SMPN Kutime: Home, Berita, dan Galeri.
            </p>
        </div>


        {{-- TAB NAVIGATION --}}
        <div class="inline-flex rounded-lg border border-slate-200 bg-white p-0.5 text-xs font-medium text-slate-600 shadow-sm">

            {{-- TAB HOME --}}
            <button
                type="button"
                @click="tab = 'home'"
                :class="tab === 'home'
                    ? 'bg-indigo-600 text-white shadow-sm'
                    : 'text-slate-600 hover:bg-slate-50'"
                class="px-3 py-1.5 rounded-md flex items-center gap-1.5 transition-colors"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Home</span>
            </button>

            {{-- TAB BERITA --}}
            <button
                type="button"
                @click="tab = 'berita'"
                :class="tab === 'berita'
                    ? 'bg-indigo-600 text-white shadow-sm'
                    : 'text-slate-600 hover:bg-slate-50'"
                class="px-3 py-1.5 rounded-md flex items-center gap-1.5 transition-colors"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                <span>Berita</span>
            </button>

            {{-- TAB GALERI --}}
            <button
                type="button"
                @click="tab = 'galeri'"
                :class="tab === 'galeri'
                    ? 'bg-indigo-600 text-white shadow-sm'
                    : 'text-slate-600 hover:bg-slate-50'"
                class="px-3 py-1.5 rounded-md flex items-center gap-1.5 transition-colors"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Galeri</span>
            </button>

        </div>


        {{-- TAB CONTENT --}}
        <div>
            {{-- TAB HOME --}}
            @include('admin.kelola-website.tabs.home')

            {{-- TAB BERITA --}}
            @include('admin.kelola-website.tabs.berita')

            {{-- TAB GALERI --}}
            @include('admin.kelola-website.tabs.galeri')
        </div>

    </div>


    {{-- ============================================================
         MODAL OVERLAY GALERI (Tambah / Edit)
         ============================================================ --}}
    <div
        x-show="modalOpen"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        @keydown.escape.window="closeModal()"
    >
        {{-- Backdrop --}}
        <div
            class="absolute inset-0 bg-black/50 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="closeModal()"
        ></div>

        {{-- Panel --}}
        <div
            class="relative w-full max-w-2xl bg-white rounded-xl shadow-2xl border border-slate-200 flex flex-col max-h-[90vh]"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-2"
        >
            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-200 shrink-0">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-indigo-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-slate-900" x-text="modalTitle"></h2>
                </div>
                <button
                    type="button"
                    @click="closeModal()"
                    class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition"
                    title="Tutup"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Body — scrollable --}}
            <div class="overflow-y-auto flex-1 px-5 py-4">

                {{-- Loading state --}}
                <div x-show="modalLoading" class="flex items-center justify-center py-12">
                    <div class="flex items-center gap-3 text-slate-400 text-xs">
                        <svg class="w-5 h-5 animate-spin text-indigo-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Memuat form...
                    </div>
                </div>

                {{-- Form content (di-inject via AJAX) --}}
                <div x-show="!modalLoading" id="galeri-modal-content">
                    {{--
                        Konten form akan di-load di sini via fetch().
                        Saat ada validation error (redirect back), server merender
                        modal langsung terbuka lewat session flag.
                    --}}
                </div>

            </div>
        </div>
    </div>

</div>

<script>
function kelolaWebsite() {
    return {
        tab: '{{ request('tab', 'home') }}',
        modalOpen: false,
        modalLoading: false,
        modalTitle: 'Tambah Media Baru',

        /**
         * Buka modal.
         * @param {number|null} galeriId  — null = tambah baru, ada id = edit
         */
        openGaleriModal(galeriId = null) {
            this.modalOpen   = true;
            this.modalLoading = true;
            this.modalTitle  = galeriId ? 'Edit Media Galeri' : 'Tambah Media Baru';

            const url = galeriId
                ? `/admin/galeri/${galeriId}/edit-modal`
                : `/admin/galeri/create-modal`;

            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.text())
            .then(html => {
                document.getElementById('galeri-modal-content').innerHTML = html;
                this.modalLoading = false;

                // Reinit Alpine pada konten baru
                if (window.Alpine) {
                    Alpine.initTree(document.getElementById('galeri-modal-content'));
                }
            })
            .catch(() => {
                this.modalLoading = false;
                document.getElementById('galeri-modal-content').innerHTML =
                    '<p class="text-xs text-red-500">Gagal memuat form. Silakan coba lagi.</p>';
            });
        },

        closeModal() {
            this.modalOpen = false;
            // Bersihkan konten setelah animasi selesai
            setTimeout(() => {
                document.getElementById('galeri-modal-content').innerHTML = '';
            }, 200);
        },
    };
}
</script>

{{-- Auto-buka modal jika ada validation error dari form galeri --}}
@if($errors->any() && old('_galeri_modal'))
<script>
document.addEventListener('alpine:init', () => {
    // Tunggu Alpine siap lalu buka modal dengan konten yang sudah di-render server
    setTimeout(() => {
        const kw = Alpine.$data(document.querySelector('[x-data="kelolaWebsite()"]'));
        if (kw) {
            kw.tab = 'galeri';
            kw.modalOpen = true;
            kw.modalLoading = false;
            kw.modalTitle = '{{ old('_galeri_id') ? 'Edit Media Galeri' : 'Tambah Media Baru' }}';
        }
    }, 50);
});
</script>
@endif

@endsection