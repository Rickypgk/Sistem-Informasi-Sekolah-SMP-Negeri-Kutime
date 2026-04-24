
<?php
    $widgetPengumuman = $widgetPengumuman ?? collect();
?>

<div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">

    
    <div style="display:flex;align-items:center;justify-content:space-between;
                padding:10px 14px;border-bottom:1px solid #f1f5f9;">
        <div style="display:flex;align-items:center;gap:8px;">
            <div style="width:30px;height:30px;border-radius:9px;background:linear-gradient(135deg,#f59e0b,#ef4444);
                        display:flex;align-items:center;justify-content:center;font-size:.9rem;">
                📢
            </div>
            <p style="font-size:.78rem;font-weight:700;color:#1e293b;margin:0;">Pengumuman</p>
        </div>
        <?php if($widgetPengumuman->count() > 0): ?>
            <span style="font-size:.55rem;font-weight:700;background:#eef2ff;color:#4f46e5;
                         border:1px solid #c7d2fe;border-radius:99px;padding:2px 7px;">
                <?php echo e($widgetPengumuman->count()); ?>

            </span>
        <?php endif; ?>
    </div>

    <?php if($widgetPengumuman->isEmpty()): ?>
        <div style="padding:36px 16px;text-align:center;color:#94a3b8;">
            <div style="width:56px;height:56px;border-radius:16px;background:#f1f5f9;
                        display:flex;align-items:center;justify-content:center;
                        font-size:1.5rem;margin:0 auto 10px;">📭</div>
            <p style="font-size:.7rem;font-weight:600;color:#475569;margin:0 0 3px;">Belum ada pengumuman</p>
            <p style="font-size:.6rem;color:#94a3b8;margin:0;">Tidak ada pengumuman saat ini</p>
        </div>
    <?php else: ?>
        <div>
            <?php $__currentLoopData = $widgetPengumuman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $catMap = [
                    'penting'  => ['bg'=>'#fee2e2','color'=>'#b91c1c','label'=>'Penting'],
                    'info'     => ['bg'=>'#e0f2fe','color'=>'#0369a1','label'=>'Info'],
                    'umum'     => ['bg'=>'#f8fafc','color'=>'#475569','label'=>'Umum'],
                    'kegiatan' => ['bg'=>'#fef9c3','color'=>'#a16207','label'=>'Kegiatan'],
                    'libur'    => ['bg'=>'#ecfdf5','color'=>'#059669','label'=>'Libur'],
                ];
                $cat = $catMap[strtolower($p->kategori ?? '')] ?? ['bg'=>'#eef2ff','color'=>'#4f46e5','label'=>ucfirst($p->kategori ?? 'Umum')];

                // Resolve gambar — support berbagai nama kolom
                $gambar = $p->gambar ?? $p->image ?? $p->foto ?? $p->thumbnail ?? null;
                $gambarUrl = null;
                if ($gambar) {
                    // Jika sudah berupa URL penuh
                    if (str_starts_with($gambar, 'http')) {
                        $gambarUrl = $gambar;
                    } else {
                        $gambarUrl = asset('storage/' . $gambar);
                    }
                }

                // Data untuk modal
                $modalData = json_encode([
                    'judul'      => $p->judul,
                    'isi'        => $p->isi,
                    'tanggal'    => (string)($p->tanggal_tayang ?? $p->created_at),
                    'kategori'   => $p->kategori ?? 'Umum',
                    'gambarUrl'  => $gambarUrl,
                    'widgetRole' => 'guru',
                ], JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT);
            ?>

            <div style="border-bottom:1px solid #f1f5f9;cursor:pointer;transition:background .15s;"
                 onmouseover="this.style.background='#f8fafc'"
                 onmouseout="this.style.background='transparent'"
                 onclick="wdgBuka(<?php echo e($modalData); ?>)">

                
                <?php if($gambarUrl): ?>
                    <div style="width:100%;height:110px;overflow:hidden;background:#f1f5f9;">
                        <img src="<?php echo e($gambarUrl); ?>"
                             alt="<?php echo e($p->judul); ?>"
                             style="width:100%;height:100%;object-fit:cover;display:block;
                                    transition:transform .3s;"
                             onmouseover="this.style.transform='scale(1.03)'"
                             onmouseout="this.style.transform='scale(1)'"
                             onerror="this.parentElement.style.display='none'">
                    </div>
                <?php endif; ?>

                <div style="padding:10px 14px;">
                    
                    <div style="display:flex;align-items:center;justify-content:space-between;
                                 gap:6px;margin-bottom:4px;">
                        <span style="padding:2px 8px;border-radius:5px;font-size:.55rem;font-weight:700;
                                      background:<?php echo e($cat['bg']); ?>;color:<?php echo e($cat['color']); ?>;">
                            <?php echo e($cat['label']); ?>

                        </span>
                        <span style="font-size:.58rem;color:#94a3b8;white-space:nowrap;">
                            <?php echo e(\Carbon\Carbon::parse($p->tanggal_tayang ?? $p->created_at)->isoFormat('D MMM Y')); ?>

                        </span>
                    </div>

                    
                    <p style="font-size:.72rem;font-weight:700;color:#1e293b;line-height:1.35;
                               margin:0 0 4px;">
                        <?php echo e(Str::limit($p->judul, 60)); ?>

                    </p>

                    
                    <p style="font-size:.62rem;color:#64748b;line-height:1.45;margin:0;">
                        <?php echo e(Str::limit(strip_tags($p->isi), $gambarUrl ? 60 : 90)); ?>

                    </p>

                    
                    <?php if($gambarUrl): ?>
                        <p style="font-size:.55rem;color:#94a3b8;margin-top:5px;
                                   display:flex;align-items:center;gap:3px;">
                            🖼 Ada gambar &nbsp;·&nbsp; Klik untuk lihat
                        </p>
                    <?php else: ?>
                        <p style="font-size:.55rem;color:#94a3b8;margin-top:5px;">
                            Klik untuk baca selengkapnya →
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div style="padding:8px 14px;border-top:1px solid #f1f5f9;background:#f8fafc;text-align:center;">
            <a href="<?php echo e(route('guru.pengumuman')); ?>"
               style="font-size:.6rem;font-weight:700;color:#4f46e5;text-decoration:none;">
                Lihat semua pengumuman →
            </a>
        </div>
    <?php endif; ?>

</div><?php /**PATH C:\PA 3\smpn-kutime\resources\views/guru/dashboard/announcements.blade.php ENDPATH**/ ?>