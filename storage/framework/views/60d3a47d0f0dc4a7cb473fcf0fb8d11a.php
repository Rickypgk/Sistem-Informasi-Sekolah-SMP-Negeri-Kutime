

<?php
    // Filter pengumuman yang masih aktif (belum kadaluarsa)
    // Harus diletakkan di paling atas agar variabel tersedia untuk header
    $pengumumanAktif = $widgetPengumuman->filter(function ($item) {
        return !$item->tanggal_selesai || $item->tanggal_selesai->isFuture();
    });
?>

<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">

    
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-700/60">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center text-white text-sm shadow-sm">
                📢
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Pengumuman</h3>
                <p class="text-xs text-slate-400 mt-0.5">
                    <?php echo e($pengumumanAktif->count()); ?> pengumuman aktif
                </p>
            </div>
        </div>
        <?php if(Route::has('guru.pengumuman')): ?>
        <a href="<?php echo e(route('guru.pengumuman')); ?>" class="text-sm font-semibold text-violet-600 hover:text-violet-700 flex items-center gap-1 transition-colors">
            Lihat semua
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        <?php endif; ?>
    </div>

    
    <?php if($pengumumanAktif->isEmpty()): ?>
        <div class="px-6 py-10 text-center">
            <div class="text-4xl mb-3 text-slate-300 dark:text-slate-600">📭</div>
            <p class="text-base text-slate-500 dark:text-slate-400 font-medium">Tidak ada pengumuman aktif saat ini.</p>
            <p class="text-sm text-slate-400 mt-2">
                Semua pengumuman telah selesai atau belum ada yang baru.
            </p>
        </div>
    <?php else: ?>
        <ul class="divide-y divide-slate-100 dark:divide-slate-700/50">
            <?php $__currentLoopData = $pengumumanAktif; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    // Siapkan data untuk modal
                    $wFileUrl = $item->file_path ? Storage::url($item->file_path) : '';
                    $wData = [
                        'judul'         => (string)($item->judul ?? 'Tanpa Judul'),
                        'isi'           => (string)($item->isi ?? ''),
                        'tipe'          => (string)($item->tipe_konten ?? 'teks'),
                        'tipeIcon'      => (string)($item->tipeIcon() ?? '📝'),
                        'audience'      => (string)($item->audienceLabel() ?? 'Semua'),
                        'audienceColor' => (string)($item->audienceBadgeColor() ?? 'bg-slate-100 text-slate-700'),
                        'fileUrl'       => $wFileUrl,
                        'fileName'      => (string)($item->file_name ?? ''),
                        'fileExt'       => (string)($item->fileExtension() ?? ''),
                        'linkUrl'       => (string)($item->link_url ?? ''),
                        'linkLabel'     => (string)($item->link_label ?? 'Buka Tautan'),
                        'tanggal'       => $item->created_at->isoFormat('D MMMM Y, HH:mm'),
                        'diffHumans'    => $item->created_at->diffForHumans(),
                        'creator'       => (string)(optional($item->creator)->name ?? 'Admin'),
                        'tglSelesai'    => $item->tanggal_selesai
                                            ? $item->tanggal_selesai->isoFormat('D MMM Y, HH:mm')
                                            : '',
                        'widgetRole'    => 'guru',
                    ];
                    $wJson = json_encode($wData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);

                    $ikBg = match($item->tipe_konten) {
                        'gambar'  => 'bg-rose-50 dark:bg-rose-900/20',
                        'dokumen' => 'bg-indigo-50 dark:bg-indigo-900/20',
                        'link'    => 'bg-sky-50 dark:bg-sky-900/20',
                        default   => 'bg-violet-50 dark:bg-violet-900/20',
                    };
                ?>

                <li>
                    <button type="button"
                            onclick='wdgBuka(<?php echo e($wJson); ?>)'
                            class="group w-full text-left transition-colors hover:bg-slate-50 dark:hover:bg-slate-700/30 focus:outline-none">

                        
                        <?php if($item->tipe_konten === 'gambar' && $item->file_path): ?>
                            <div class="w-full h-28 overflow-hidden bg-slate-100 dark:bg-slate-700">
                                <img src="<?php echo e($wFileUrl); ?>" alt="<?php echo e($item->judul); ?>" loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                     onerror="this.src='/images/placeholder-image.jpg'">
                            </div>
                        <?php endif; ?>

                        <div class="flex items-start gap-3 px-5 py-4">
                            
                            <?php if($item->tipe_konten !== 'gambar'): ?>
                                <div class="shrink-0 w-10 h-10 rounded-xl <?php echo e($ikBg); ?>

                                            flex items-center justify-center text-xl mt-0.5">
                                    <?php echo e($item->tipeIcon()); ?>

                                </div>
                            <?php endif; ?>

                            <div class="flex-1 min-w-0">
                                <p class="text-[13px] font-semibold text-slate-800 dark:text-slate-100 line-clamp-2 leading-snug group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">
                                    <?php echo e($item->judul); ?>

                                </p>

                                
                                <?php if($item->isi): ?>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 line-clamp-2">
                                        <?php echo e(strip_tags($item->isi)); ?>

                                    </p>
                                <?php endif; ?>

                                
                                <div class="flex flex-wrap gap-2 mt-2 text-[10px] text-slate-400">
                                    <span><?php echo e($item->created_at->diffForHumans()); ?></span>
                                    <?php if($item->tanggal_selesai): ?>
                                        <span>•</span>
                                        <span class="text-amber-600 dark:text-amber-400">
                                            Berakhir: <?php echo e($item->tanggal_selesai->diffForHumans()); ?>

                                        </span>
                                    <?php endif; ?>
                                    <span>•</span>
                                    <span><?php echo e(optional($item->creator)->name ?? 'Admin'); ?></span>
                                </div>
                            </div>

                            <div class="shrink-0 self-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-5 h-5 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </button>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>

        <?php if(Route::has('guru.pengumuman')): ?>
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700/60 text-center">
                <a href="<?php echo e(route('guru.pengumuman')); ?>" class="inline-flex items-center gap-2 text-sm font-semibold text-violet-600 hover:text-violet-700 transition-colors">
                    Lihat semua pengumuman
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div><?php /**PATH C:\PA 3\smpn-kutime\resources\views/guru/dashboard/announcements.blade.php ENDPATH**/ ?>