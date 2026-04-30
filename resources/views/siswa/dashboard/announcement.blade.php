{{--
| resources/views/siswa/dashboard/announcement.blade.php
| Partial: Widget Pengumuman untuk Dashboard Siswa
| Usage: @include('siswa.dashboard.announcement', ['widgetPengumuman' => $widgetPengumuman])
--}}

{{-- ══ MODAL PENGUMUMAN ══════════════════════════════════════════════════════ --}}
<div id="annModal"
     onclick="if(event.target===this)annClose()"
     class="fixed inset-0 z-[9999] hidden items-center justify-center p-4"
     style="background:rgba(0,0,0,.6);backdrop-filter:blur(8px)">
    <div class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto
                bg-white dark:bg-slate-800 rounded-3xl shadow-2xl
                border border-slate-200 dark:border-slate-700 animate-ann-modal">
        <button onclick="annClose()"
                class="absolute top-4 right-4 z-10 w-8 h-8 flex items-center justify-center
                       bg-slate-100 hover:bg-red-100 dark:bg-slate-700 dark:hover:bg-red-900/40
                       text-slate-400 hover:text-red-500 rounded-xl transition-all text-sm">✕</button>
        <div id="annModalBody" class="p-6 sm:p-8"></div>
    </div>
</div>

{{-- ══ WIDGET CARD ═══════════════════════════════════════════════════════════ --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
            dark:border-slate-700 shadow-sm overflow-hidden">

    {{-- Header --}}
    <div class="flex items-center justify-between px-4 py-3.5 border-b
                border-slate-100 dark:border-slate-700/60
                bg-gradient-to-r from-sky-50 to-blue-50
                dark:from-sky-900/10 dark:to-blue-900/10">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-sky-500 to-blue-600
                        flex items-center justify-center shadow-sm text-base">📢</div>
            <div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Pengumuman</h3>
                <p class="text-[10px] text-slate-400 leading-none mt-0.5">
                    {{ $widgetPengumuman->count() }} terbaru
                </p>
            </div>
        </div>
        @if(Route::has('siswa.pengumuman'))
        <a href="{{ route('siswa.pengumuman') }}"
           class="text-[11px] font-semibold text-sky-600 hover:text-sky-700
                  dark:text-sky-400 dark:hover:text-sky-300
                  flex items-center gap-0.5 transition-colors">
            Semua
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        @endif
    </div>

    @if($widgetPengumuman->isEmpty())
    <div class="flex flex-col items-center justify-center py-10 text-center px-4">
        <div class="text-4xl mb-2">📭</div>
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">Belum ada pengumuman</p>
        <p class="text-[10px] text-slate-400 mt-0.5">Cek kembali nanti ya.</p>
    </div>
    @else
    <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
        @foreach($widgetPengumuman as $item)
        @php
            $fileUrl = $item->file_path ? asset('storage/' . $item->file_path) : '';
            $annData = [
                'judul'         => (string)($item->judul ?? ''),
                'isi'           => (string)($item->isi ?? ''),
                'tipe'          => (string)($item->tipe_konten ?? 'teks'),
                'tipeIcon'      => (string)($item->tipeIcon()),
                'audience'      => (string)($item->audienceLabel()),
                'audienceColor' => (string)($item->audienceBadgeColor()),
                'fileUrl'       => $fileUrl,
                'fileName'      => (string)($item->file_name ?? ''),
                'fileExt'       => (string)($item->fileExtension() ?? ''),
                'linkUrl'       => (string)($item->link_url ?? ''),
                'linkLabel'     => (string)($item->link_label ?? 'Kunjungi Link'),
                'tanggal'       => $item->created_at->isoFormat('D MMMM Y, HH:mm'),
                'diffHumans'    => $item->created_at->diffForHumans(),
                'creator'       => (string)(optional($item->creator)->name ?? 'Admin'),
                'tglSelesai'    => $item->tanggal_selesai
                                    ? $item->tanggal_selesai->isoFormat('D MMM Y, HH:mm')
                                    : '',
            ];
            $annJson = json_encode($annData, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE);
            $isNew   = $item->created_at->gt(now()->subHours(24));
        @endphp
        <button type="button"
                onclick='annOpen({{ $annJson }})'
                class="group w-full text-left px-4 py-3.5
                       hover:bg-sky-50/60 dark:hover:bg-sky-900/10
                       transition-colors focus:outline-none">
            <div class="flex items-start gap-3">
                {{-- Icon --}}
                <div class="shrink-0 w-9 h-9 rounded-xl flex items-center justify-center text-base mt-0.5
                    {{ $item->tipe_konten === 'dokumen'
                        ? 'bg-indigo-100 dark:bg-indigo-900/30'
                        : ($item->tipe_konten === 'link'
                            ? 'bg-sky-100 dark:bg-sky-900/30'
                            : ($item->tipe_konten === 'gambar'
                                ? 'bg-rose-100 dark:bg-rose-900/30'
                                : 'bg-emerald-100 dark:bg-emerald-900/30')) }}">
                    {{ $item->tipeIcon() }}
                </div>
                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-1.5 flex-wrap mb-0.5">
                        <p class="text-xs font-semibold text-slate-800 dark:text-slate-100
                                  group-hover:text-sky-600 dark:group-hover:text-sky-400
                                  transition-colors leading-snug line-clamp-1">
                            {{ $item->judul }}
                        </p>
                        @if($isNew)
                        <span class="shrink-0 inline-flex items-center px-1.5 py-0.5 rounded-full
                                     text-[9px] font-bold bg-emerald-100 text-emerald-700
                                     dark:bg-emerald-900/40 dark:text-emerald-400">BARU</span>
                        @endif
                    </div>
                    @if($item->isi)
                    <p class="text-[11px] text-slate-400 line-clamp-1 leading-relaxed">
                        {{ strip_tags($item->isi) }}
                    </p>
                    @endif
                    <p class="text-[10px] text-slate-400 mt-1">
                        {{ $item->created_at->diffForHumans() }}
                    </p>
                </div>
                {{-- Arrow --}}
                <div class="shrink-0 self-center text-slate-300 dark:text-slate-600
                            group-hover:text-sky-400 group-hover:translate-x-0.5 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </div>
        </button>
        @endforeach
    </div>

    @if(Route::has('siswa.pengumuman'))
    <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-700/50">
        <a href="{{ route('siswa.pengumuman') }}"
           class="flex items-center justify-center gap-1.5 text-[11px] font-semibold
                  text-sky-600 hover:text-sky-700 dark:text-sky-400
                  transition-colors py-1 hover:underline">
            Lihat semua pengumuman
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
    @endif
    @endif
</div>

@once
@push('styles')
<style>
.animate-ann-modal { animation: annPop .22s cubic-bezier(.34,1.56,.64,1); }
@keyframes annPop { from { opacity:0; transform:scale(.92) translateY(12px); } to { opacity:1; transform:none; } }
</style>
@endpush

@push('scripts')
<script>
(function(){
    'use strict';
    window.annOpen = function(d) {
        document.getElementById('annModalBody').innerHTML = annBuildHtml(d);
        var el = document.getElementById('annModal');
        el.classList.remove('hidden'); el.classList.add('flex');
        document.body.style.overflow = 'hidden';
    };
    window.annClose = function() {
        var el = document.getElementById('annModal');
        el.classList.add('hidden'); el.classList.remove('flex');
        document.body.style.overflow = '';
    };
    document.addEventListener('keydown', function(ev){ if(ev.key==='Escape') annClose(); });

    function annBuildHtml(d) {
        var h = '';
        h += '<div class="flex items-start gap-4 mb-5 pr-10">';
        h += '<div class="text-3xl shrink-0 mt-0.5 leading-none">'+d.tipeIcon+'</div>';
        h += '<div class="flex-1 min-w-0">';
        h += '<h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 leading-snug break-words">'+e(d.judul)+'</h2>';
        h += '<div class="flex gap-2 mt-2 flex-wrap">';
        h += '<span class="px-2.5 py-1 rounded-full text-xs font-semibold '+d.audienceColor+'">'+e(d.audience)+'</span>';
        h += '<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300 capitalize">'+e(d.tipe)+'</span>';
        h += '</div></div></div>';
        h += '<div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-400 mb-5 pb-5 border-b border-slate-200 dark:border-slate-700">';
        h += '<span>📅 '+e(d.tanggal)+'</span><span>👤 '+e(d.creator)+'</span><span>🕐 '+e(d.diffHumans)+'</span></div>';
        if(d.tipe==='gambar'&&d.fileUrl){
            h += '<div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-600 mb-5 bg-slate-50 dark:bg-slate-900 flex items-center justify-center min-h-[120px]">';
            h += '<img src="'+d.fileUrl+'" alt="'+e(d.judul)+'" class="w-full max-h-[420px] object-contain block" onerror="this.closest(\'div\').innerHTML=\'<div class=\\\"p-8 text-center\\\"><div class=\\\"text-5xl mb-3\\\">🖼️</div><p class=\\\"text-sm text-slate-400\\\">Gambar tidak dapat dimuat.</p></div>\'">';
            h += '</div>';
        }
        if(d.isi&&d.isi.trim()){
            var isHtml = /<[a-z][\s\S]*>/i.test(d.isi);
            h += isHtml
                ? '<div class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed mb-5 prose prose-sm dark:prose-invert max-w-none">'+sanitize(d.isi)+'</div>'
                : '<div class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed mb-5 whitespace-pre-line">'+e(d.isi)+'</div>';
        }
        if(d.tipe==='dokumen'&&d.fileUrl){
            h += '<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 p-4 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl border border-indigo-200 dark:border-indigo-700 mb-5">';
            h += '<div class="flex items-center gap-3"><div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-800 rounded-xl flex items-center justify-center text-2xl">📄</div>';
            h += '<div><p class="text-sm font-bold text-indigo-700 dark:text-indigo-300">'+e(d.fileExt||'FILE')+' Dokumen</p><p class="text-xs text-slate-400 max-w-[220px] truncate">'+e(d.fileName)+'</p></div></div>';
            h += '<a href="'+d.fileUrl+'" target="_blank" download onclick="event.stopPropagation()" class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl no-underline">⬇️ Unduh</a></div>';
        }
        if(d.tipe==='link'&&d.linkUrl){
            h += '<div class="p-4 bg-sky-50 dark:bg-sky-900/30 rounded-2xl border border-sky-200 dark:border-sky-700 mb-5">';
            h += '<p class="text-xs text-slate-500 mb-3 font-medium">🔗 Tautan Pengumuman</p>';
            h += '<a href="'+d.linkUrl+'" target="_blank" rel="noopener noreferrer" onclick="event.stopPropagation()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold rounded-xl no-underline">🔗 '+e(d.linkLabel||'Kunjungi Link')+'</a>';
            h += '<p class="text-xs text-slate-400 mt-2 break-all">'+e(d.linkUrl)+'</p></div>';
        }
        if(d.tglSelesai){
            h += '<div class="flex items-center gap-3 px-4 py-3 bg-amber-50 dark:bg-amber-900/20 rounded-2xl border border-amber-200 dark:border-amber-700 mb-4"><span class="text-xl">⏰</span><p class="text-xs text-amber-700 dark:text-amber-300 font-medium">Berakhir: <strong>'+e(d.tglSelesai)+'</strong></p></div>';
        }
        h += '<div class="flex justify-end pt-2"><button onclick="annClose()" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-sm font-semibold rounded-2xl transition-colors">Tutup</button></div>';
        return h;
    }
    function e(v){if(v==null)return '';return String(v).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');}
    function sanitize(h){return(h||'').replace(/<script[\s\S]*?<\/script>/gi,'').replace(/<iframe[\s\S]*?<\/iframe>/gi,'').replace(/\bon\w+\s*=\s*["'][^"']*["']/gi,'').replace(/javascript\s*:/gi,'#');}
})();
</script>
@endpush
@endonce