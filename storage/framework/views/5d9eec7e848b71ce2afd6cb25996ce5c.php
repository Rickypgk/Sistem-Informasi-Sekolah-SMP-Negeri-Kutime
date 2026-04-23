
<?php
    $kpiCards = [
        [
            'icon'   => '👨‍🎓',
            'bg'     => 'linear-gradient(135deg,#eef2ff,#e0e7ff)',
            'val'    => $totalSiswa ?? 0,
            'fmt'    => 'int',
            'label'  => 'Total Siswa',
            'sub'    => 'Semua kelas diajar',
            'color'  => '#4f46e5',
            'border' => '#c7d2fe',
        ],
        [
            'icon'   => '✅',
            'bg'     => 'linear-gradient(135deg,#ecfdf5,#d1fae5)',
            'val'    => $kehadiranPct ?? 0,
            'fmt'    => 'pct',
            'label'  => 'Kehadiran Bulan Ini',
            'sub'    => now()->isoFormat('MMMM Y'),
            'color'  => '#059669',
            'border' => '#a7f3d0',
        ],
        [
            'icon'   => '⚠️',
            'bg'     => 'linear-gradient(135deg,#fef2f2,#fee2e2)',
            'val'    => $siswaRisiko ?? 0,
            'fmt'    => 'int',
            'label'  => 'Butuh Perhatian',
            'sub'    => 'Kehadiran < 75%',
            'color'  => '#dc2626',
            'border' => '#fecaca',
        ],
    ];
?>


<div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
    <?php $__currentLoopData = $kpiCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div style="background:#fff;border-radius:12px;border:1px solid <?php echo e($c['border']); ?>;
                padding:14px 16px;box-shadow:0 1px 4px rgba(0,0,0,.05);
                transition:box-shadow .15s;"
         onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,.1)'"
         onmouseout="this.style.boxShadow='0 1px 4px rgba(0,0,0,.05)'">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:38px;height:38px;border-radius:11px;flex-shrink:0;
                         display:flex;align-items:center;justify-content:center;
                         font-size:1.1rem;background:<?php echo e($c['bg']); ?>;">
                <?php echo e($c['icon']); ?>

            </div>
            <div style="flex:1;min-width:0;">
                <p style="font-size:1.35rem;font-weight:800;line-height:1.1;color:<?php echo e($c['color']); ?>;margin:0;">
                    <?php if($c['fmt'] === 'pct'): ?> <?php echo e(number_format($c['val'], 1)); ?>%
                    <?php else: ?> <?php echo e($c['val']); ?>

                    <?php endif; ?>
                </p>
                <p style="font-size:.65rem;font-weight:700;color:#1e293b;margin:2px 0 1px;">
                    <?php echo e($c['label']); ?>

                </p>
                <p style="font-size:.58rem;color:#94a3b8;margin:0;"><?php echo e($c['sub']); ?></p>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<?php if(isset($absensiHariIni)): ?>
<div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;
             padding:12px 14px;box-shadow:0 1px 4px rgba(0,0,0,.05);">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
        <div style="display:flex;align-items:center;gap:6px;">
            <span style="font-size:.85rem;">📋</span>
            <p style="font-size:.72rem;font-weight:700;color:#1e293b;margin:0;">Absensi Hari Ini</p>
            <span style="font-size:.58rem;color:#94a3b8;">
                — <?php echo e(now()->isoFormat('ddd, D MMM')); ?>

            </span>
        </div>
        <a href="<?php echo e(route('guru.absensi-siswa.index')); ?>"
           style="font-size:.6rem;font-weight:700;color:#4f46e5;text-decoration:none;
                  background:#eef2ff;border:1px solid #c7d2fe;border-radius:6px;
                  padding:3px 9px;white-space:nowrap;">
            Catat Absensi →
        </a>
    </div>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px;">
        <?php
            $abSt = [
                ['key'=>'hadir', 'label'=>'Hadir', 'bg'=>'#ecfdf5','color'=>'#059669','icon'=>'✅'],
                ['key'=>'sakit', 'label'=>'Sakit', 'bg'=>'#fef9c3','color'=>'#a16207','icon'=>'🤒'],
                ['key'=>'izin',  'label'=>'Izin',  'bg'=>'#e0f2fe','color'=>'#0369a1','icon'=>'📋'],
                ['key'=>'alpha', 'label'=>'Alpha', 'bg'=>'#fee2e2','color'=>'#b91c1c','icon'=>'❌'],
            ];
        ?>
        <?php $__currentLoopData = $abSt; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div style="border-radius:9px;padding:10px 8px;text-align:center;background:<?php echo e($st['bg']); ?>;">
            <p style="font-size:.75rem;margin-bottom:2px;"><?php echo e($st['icon']); ?></p>
            <p style="font-size:1.1rem;font-weight:800;color:<?php echo e($st['color']); ?>;line-height:1;margin:0 0 2px;">
                <?php echo e($absensiHariIni[$st['key']] ?? 0); ?>

            </p>
            <p style="font-size:.58rem;font-weight:700;color:<?php echo e($st['color']); ?>;opacity:.8;margin:0;">
                <?php echo e($st['label']); ?>

            </p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?><?php /**PATH C:\PA 3\smpn-kutime\resources\views/guru/dashboard/performance-summary.blade.php ENDPATH**/ ?>