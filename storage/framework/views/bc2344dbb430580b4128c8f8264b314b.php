<?php $__env->startSection('title', 'Dashboard Siswa'); ?>

<?php $__env->startSection('content'); ?>

<?php
    if (!isset($widgetPengumuman)) {
        $widgetPengumuman = \App\Models\Pengumuman::where('is_active', 1)
            ->where('show_di_dashboard', 1)
            ->whereIn('target_audience', ['siswa', 'semua'])
            ->latest()
            ->limit(4)
            ->get();
    }
?>


<div id="wdgModal-siswa"
     onclick="if(event.target===this)wdgTutup('siswa')"
     class="fixed inset-0 z-[9999] hidden items-center justify-center p-4"
     style="background:rgba(0,0,0,.65);backdrop-filter:blur(6px)">
    <div class="relative w-full max-w-2xl max-h-[92vh] overflow-y-auto
                bg-white dark:bg-slate-800 rounded-3xl shadow-2xl
                border border-slate-200 dark:border-slate-700">
        <button onclick="wdgTutup('siswa')"
                class="absolute top-4 right-4 z-10 w-9 h-9 flex items-center justify-center
                       bg-slate-100 hover:bg-red-100 dark:bg-slate-700 dark:hover:bg-red-900/40
                       text-slate-500 hover:text-red-500 rounded-2xl transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <div id="wdgModalKonten-siswa" class="p-6 sm:p-8"></div>
    </div>
</div>

<div class="space-y-6">

    
    <div class="bg-gradient-to-br from-sky-500 via-blue-600 to-indigo-600
                rounded-3xl p-6 text-white relative overflow-hidden">
        <div class="relative">
            <h2 class="text-xl font-bold">
                Selamat datang, <?php echo e(auth()->user()->name ?? 'Siswa'); ?>! 👋
            </h2>
            <p class="text-sky-200 text-sm mt-1"><?php echo e(now()->isoFormat('dddd, D MMMM Y')); ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
                <p class="text-sm text-slate-500 dark:text-slate-400">Konten dashboard siswa...</p>
            </div>
        </div>

        
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">

                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-700/50">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-sky-500 to-blue-600
                                    flex items-center justify-center text-white text-sm shadow-sm">📢</div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Pengumuman</h3>
                            <p class="text-xs text-slate-400"><?php echo e($widgetPengumuman->count()); ?> pengumuman</p>
                        </div>
                    </div>
                    <?php if(Route::has('siswa.pengumuman')): ?>
                    <a href="<?php echo e(route('siswa.pengumuman')); ?>"
                       class="text-xs font-semibold text-sky-600 hover:text-sky-700 flex items-center gap-1">
                        Lihat semua
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <?php endif; ?>
                </div>

                <?php if($widgetPengumuman->isEmpty()): ?>
                    <div class="px-5 py-10 text-center">
                        <div class="text-4xl mb-3">📭</div>
                        <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Belum ada pengumuman.</p>
                    </div>
                <?php else: ?>
                    <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        <?php $__currentLoopData = $widgetPengumuman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $wFileUrl = $item->file_path ? asset('storage/' . $item->file_path) : '';
                            $wData = [
                                'judul'         => (string)($item->judul ?? ''),
                                'isi'           => (string)($item->isi ?? ''),
                                'tipe'          => (string)($item->tipe_konten ?? 'teks'),
                                'tipeIcon'      => (string)($item->tipeIcon()),
                                'audience'      => (string)($item->audienceLabel()),
                                'audienceColor' => (string)($item->audienceBadgeColor()),
                                'fileUrl'       => $wFileUrl,
                                'fileName'      => (string)($item->file_name ?? ''),
                                'fileExt'       => (string)($item->fileExtension() ?? ''),
                                'linkUrl'       => (string)($item->link_url ?? ''),
                                'linkLabel'     => (string)($item->link_label ?? 'Kunjungi Link'),
                                'tanggal'       => $item->created_at->isoFormat('D MMMM Y, HH:mm'),
                                'diffHumans'    => $item->created_at->diffForHumans(),
                                'creator'       => (string)(optional($item->creator)->name ?? 'Admin'),
                                'tglSelesai'    => $item->tanggal_selesai ? $item->tanggal_selesai->isoFormat('D MMM Y, HH:mm') : '',
                                'widgetRole'    => 'siswa',
                            ];
                            $wJson = json_encode($wData, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE);
                        ?>

                        <button type="button"
                                onclick='wdgBuka(<?php echo e($wJson); ?>)'
                                class="group w-full text-left hover:bg-slate-50 dark:hover:bg-slate-700/40
                                       transition-colors focus:outline-none overflow-hidden">

                            <?php if($item->tipe_konten === 'gambar' && $item->file_path): ?>
                            <div class="w-full h-28 overflow-hidden bg-slate-100 dark:bg-slate-700">
                                <img src="<?php echo e(asset('storage/' . $item->file_path)); ?>"
                                     alt="<?php echo e($item->judul); ?>" loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                     onerror="this.closest('div').innerHTML='<div class=\'w-full h-28 flex flex-col items-center justify-center text-slate-400 text-xs gap-1\'><span class=\'text-2xl\'>🖼️</span><span>Tidak tersedia</span></div>'">
                            </div>
                            <?php endif; ?>

                            <div class="flex items-start gap-3 px-5 py-3.5">
                                <?php if($item->tipe_konten !== 'gambar'): ?>
                                <div class="shrink-0 w-9 h-9 rounded-xl flex items-center justify-center text-base mt-0.5
                                    <?php if($item->tipe_konten === 'dokumen'): ?> bg-indigo-100 dark:bg-indigo-900/40
                                    <?php elseif($item->tipe_konten === 'link'): ?> bg-sky-100 dark:bg-sky-900/40
                                    <?php else: ?> bg-emerald-100 dark:bg-emerald-900/40 <?php endif; ?>">
                                    <?php echo e($item->tipeIcon()); ?>

                                </div>
                                <?php endif; ?>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 line-clamp-1
                                              group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors">
                                        <?php echo e($item->judul); ?>

                                    </p>
                                    <?php if($item->isi && $item->tipe_konten !== 'gambar'): ?>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-1">
                                        <?php echo e(strip_tags($item->isi)); ?>

                                    </p>
                                    <?php endif; ?>
                                    <?php if($item->tipe_konten === 'dokumen' && $item->file_path): ?>
                                    <p class="text-xs font-bold text-indigo-500 mt-0.5">📄 <?php echo e($item->fileExtension() ?: 'FILE'); ?></p>
                                    <?php endif; ?>
                                    <?php if($item->tipe_konten === 'link' && $item->link_url): ?>
                                    <p class="text-xs text-sky-500 mt-0.5 truncate"><?php echo e($item->link_url); ?></p>
                                    <?php endif; ?>
                                    <span class="text-xs text-slate-400 mt-1 block">
                                        <?php echo e($item->created_at->diffForHumans()); ?>

                                    </span>
                                </div>
                                <div class="shrink-0 self-center text-slate-300 dark:text-slate-600
                                            group-hover:text-sky-400 group-hover:translate-x-0.5 transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                        </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php if(Route::has('siswa.pengumuman')): ?>
                    <div class="px-5 py-3 border-t border-slate-100 dark:border-slate-700/50">
                        <a href="<?php echo e(route('siswa.pengumuman')); ?>"
                           class="flex items-center justify-center gap-1.5 text-xs font-semibold
                                  text-sky-600 hover:text-sky-700 transition-colors py-1">
                            Lihat semua pengumuman
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';
    window.wdgBuka = function (d) {
        var role = d.widgetRole || 'siswa';
        var k = document.getElementById('wdgModalKonten-' + role);
        var o = document.getElementById('wdgModal-' + role);
        if (!k || !o) return;
        k.innerHTML = wdgHtml(d);
        o.classList.remove('hidden'); o.classList.add('flex');
        document.body.style.overflow = 'hidden';
    };
    window.wdgTutup = function (role) {
        var o = document.getElementById('wdgModal-' + (role||'siswa'));
        if (!o) return;
        o.classList.add('hidden'); o.classList.remove('flex');
        document.body.style.overflow = '';
    };
    document.addEventListener('keydown', function(ev){ if(ev.key==='Escape') wdgTutup('siswa'); });

    function wdgHtml(d) {
        var h = '';
        h += '<div class="flex items-start gap-4 mb-5 pr-10"><div class="text-3xl shrink-0 mt-0.5">' + d.tipeIcon + '</div><div class="flex-1 min-w-0">';
        h += '<h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 leading-snug break-words">' + e(d.judul) + '</h2>';
        h += '<div class="flex gap-2 mt-2 flex-wrap"><span class="px-2.5 py-1 rounded-full text-xs font-semibold ' + d.audienceColor + '">' + e(d.audience) + '</span>';
        h += '<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300 capitalize">' + e(d.tipe) + '</span></div></div></div>';
        h += '<div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-400 mb-5 pb-5 border-b border-slate-200 dark:border-slate-700">';
        h += '<span>📅 ' + e(d.tanggal) + '</span><span>👤 ' + e(d.creator) + '</span><span>🕐 ' + e(d.diffHumans) + '</span></div>';
        if (d.tipe==='gambar') {
            if (d.fileUrl) {
                h += '<div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-600 mb-5 bg-slate-50 dark:bg-slate-900 flex items-center justify-center min-h-[100px]">';
                h += '<img src="'+d.fileUrl+'" alt="'+e(d.judul)+'" class="w-full max-h-[420px] object-contain block" onerror="this.closest(\'div\').innerHTML=\'<div class=\\\"p-8 text-center\\\"><div class=\\\"text-5xl mb-3\\\">🖼️</div><p class=\\\"text-sm text-slate-400\\\">Gambar tidak dapat dimuat.</p></div>\'">';
                h += '</div>';
            } else { h += '<div class="p-8 mb-5 bg-slate-50 rounded-2xl text-center"><div class="text-4xl mb-2">🖼️</div><p class="text-sm text-slate-400">Tidak ada gambar.</p></div>'; }
        }
        if (d.isi && d.isi.trim()) {
            var t = /<[a-z][\s\S]*>/i.test(d.isi);
            h += t ? '<div class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed mb-5 prose prose-sm dark:prose-invert max-w-none">'+s(d.isi)+'</div>'
                   : '<div class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed mb-5 whitespace-pre-line">'+e(d.isi)+'</div>';
        }
        if (d.tipe==='dokumen') {
            if (d.fileUrl) {
                h += '<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 p-4 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl border border-indigo-200 dark:border-indigo-700 mb-5">';
                h += '<div class="flex items-center gap-3"><div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-2xl">📄</div>';
                h += '<div><p class="text-sm font-bold text-indigo-700">'+e(d.fileExt||'FILE')+' Dokumen</p><p class="text-xs text-slate-400 max-w-[200px] truncate">'+e(d.fileName)+'</p></div></div>';
                h += '<a href="'+d.fileUrl+'" target="_blank" download onclick="event.stopPropagation()" class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl no-underline">⬇️ Unduh</a></div>';
            } else { h += '<div class="p-8 mb-5 bg-slate-50 rounded-2xl text-center"><div class="text-4xl mb-2">📄</div><p class="text-sm text-slate-400">Tidak ada dokumen.</p></div>'; }
        }
        if (d.tipe==='link') {
            if (d.linkUrl) {
                h += '<div class="p-4 bg-sky-50 dark:bg-sky-900/30 rounded-2xl border border-sky-200 dark:border-sky-700 mb-5">';
                h += '<p class="text-xs text-slate-500 mb-3 font-medium">🔗 Tautan Pengumuman</p>';
                h += '<a href="'+d.linkUrl+'" target="_blank" rel="noopener noreferrer" onclick="event.stopPropagation()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold rounded-xl no-underline">🔗 '+e(d.linkLabel||'Kunjungi Link')+'</a>';
                h += '<p class="text-xs text-slate-400 mt-2 break-all">'+e(d.linkUrl)+'</p></div>';
            } else { h += '<div class="p-8 mb-5 bg-slate-50 rounded-2xl text-center"><div class="text-4xl mb-2">🔗</div><p class="text-sm text-slate-400">Tidak ada tautan.</p></div>'; }
        }
        if (d.tglSelesai) {
            h += '<div class="flex items-center gap-3 px-4 py-3 bg-amber-50 dark:bg-amber-900/20 rounded-2xl border border-amber-200 dark:border-amber-700 mb-4"><span class="text-xl">⏰</span><p class="text-xs text-amber-700 dark:text-amber-300 font-medium">Berakhir: <strong>'+e(d.tglSelesai)+'</strong></p></div>';
        }
        h += '<div class="flex justify-end pt-2"><button onclick="wdgTutup(\'siswa\')" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-sm font-semibold rounded-2xl">Tutup</button></div>';
        return h;
    }
    function e(v){if(v==null)return '';return String(v).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');}
    function s(h){return(h||'').replace(/<script[\s\S]*?<\/script>/gi,'').replace(/<iframe[\s\S]*?<\/iframe>/gi,'').replace(/\bon\w+=["'][^"']*["']/gi,'').replace(/javascript:/gi,'#');}
})();
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PA 3\smpn-kutime\resources\views/siswa/dashboard.blade.php ENDPATH**/ ?>