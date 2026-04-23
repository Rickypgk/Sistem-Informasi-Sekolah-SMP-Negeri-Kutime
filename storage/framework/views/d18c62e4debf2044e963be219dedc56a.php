

<?php
    $kelasWali  = $kelasWaliData ?? null;
    $jmlSiswa   = $totalSiswaWali ?? 0;
    $rekapDash  = $rekapDataDashboard ?? [];

    $pctTinggi  = 0; $pctSedang = 0; $pctRendah  = 0;
    foreach($rekapDash as $r) {
        $tot = array_sum($r);
        $pct = $tot > 0 ? round($r['hadir'] / $tot * 100) : 0;
        if($pct >= 80) $pctTinggi++;
        elseif($pct >= 60) $pctSedang++;
        else $pctRendah++;
    }
?>

<div class="bg-white dark:bg-slate-800 rounded-xl border border-amber-200 dark:border-amber-700/40 shadow-sm overflow-hidden">

    
    <div class="px-4 py-3 border-b border-amber-100 dark:border-amber-700/30"
         style="background:linear-gradient(135deg,#fffbeb,#fef3c7);">
        <div class="flex items-center gap-2">
            <span style="font-size:1rem;">⭐</span>
            <div>
                <p style="font-size:.75rem;font-weight:700;color:#92400e;">Wali Kelas</p>
                <?php if($kelasWali): ?>
                    <p style="font-size:.6rem;color:#a16207;"><?php echo e($kelasWali->nama); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="p-4 space-y-3">

        
        <div class="flex items-center justify-between">
            <p style="font-size:.65rem;color:#64748b;font-weight:600;">Total Siswa</p>
            <p style="font-size:.85rem;font-weight:800;color:#1e293b;"><?php echo e($jmlSiswa); ?></p>
        </div>

        
        <div>
            <p style="font-size:.6rem;font-weight:700;color:#64748b;text-transform:uppercase;
                      letter-spacing:.05em;margin-bottom:6px;">Distribusi Kehadiran</p>

            <div class="space-y-1.5">
                <?php
                    $distItems = [
                        ['label'=>'≥ 80% (Baik)',      'val'=>$pctTinggi, 'color'=>'#059669','bg'=>'#ecfdf5'],
                        ['label'=>'60–79% (Sedang)',   'val'=>$pctSedang, 'color'=>'#a16207','bg'=>'#fef9c3'],
                        ['label'=>'< 60% (Rendah)',    'val'=>$pctRendah, 'color'=>'#b91c1c','bg'=>'#fee2e2'],
                    ];
                ?>
                <?php $__currentLoopData = $distItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $di): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between px-2.5 py-1.5 rounded-lg"
                     style="background:<?php echo e($di['bg']); ?>">
                    <span style="font-size:.62rem;font-weight:600;color:<?php echo e($di['color']); ?>;">
                        <?php echo e($di['label']); ?>

                    </span>
                    <span style="font-size:.75rem;font-weight:800;color:<?php echo e($di['color']); ?>;">
                        <?php echo e($di['val']); ?> siswa
                    </span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="pt-1 border-t border-slate-100 dark:border-slate-700 space-y-1">
            <a href="<?php echo e(route('guru.absensi-siswa.rekap', ['kelas_id' => $kelasWali->id ?? ''])); ?>"
               style="display:flex;align-items:center;gap:6px;padding:6px 8px;border-radius:7px;
                      font-size:.65rem;font-weight:600;color:#4338ca;text-decoration:none;
                      background:#eef2ff;border:1px solid #c7d2fe;">
                <span>📋</span> Rekap Absensi Lengkap
            </a>
            <a href="<?php echo e(route('guru.wali-kelas')); ?>"
               style="display:flex;align-items:center;gap:6px;padding:6px 8px;border-radius:7px;
                      font-size:.65rem;font-weight:600;color:#92400e;text-decoration:none;
                      background:#fffbeb;border:1px solid #fde68a;">
                <span>👥</span> Data Kelas
            </a>
            <a href="<?php echo e(route('guru.absensi-siswa.index')); ?>"
               style="display:flex;align-items:center;gap:6px;padding:6px 8px;border-radius:7px;
                      font-size:.65rem;font-weight:600;color:#065f46;text-decoration:none;
                      background:#ecfdf5;border:1px solid #a7f3d0;">
                <span>✅</span> Catat Absensi Hari Ini
            </a>
        </div>

    </div>
</div><?php /**PATH C:\PA 3\smpn-kutime\resources\views/guru/dashboard/wali-kelas-summary.blade.php ENDPATH**/ ?>