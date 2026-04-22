
<?php
    $jadwalHariIni = $jadwalHariIni ?? collect();
    $hariIni = now()->isoFormat('dddd');
?>

<div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">

    <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span style="font-size:1rem;">🗓️</span>
            <div>
                <p class="font-semibold text-slate-800 dark:text-slate-100" style="font-size:.8rem;">
                    Jadwal Mengajar Hari Ini
                </p>
                <p class="text-slate-400" style="font-size:.6rem;"><?php echo e($hariIni); ?>, <?php echo e(now()->isoFormat('D MMMM Y')); ?></p>
            </div>
        </div>
        <a href="<?php echo e(route('guru.jadwal-mengajar.index')); ?>"
           style="font-size:.6rem;font-weight:700;color:#4f46e5;text-decoration:none;
                  background:#eef2ff;border:1px solid #c7d2fe;border-radius:6px;padding:3px 8px;">
            Semua Jadwal
        </a>
    </div>

    <?php if($jadwalHariIni->isEmpty()): ?>
        <div style="padding:32px;text-align:center;color:#94a3b8;">
            <p style="font-size:2rem;margin-bottom:6px;">😊</p>
            <p style="font-size:.7rem;font-weight:600;color:#64748b;">Tidak ada jadwal mengajar hari ini</p>
            <p style="font-size:.6rem;color:#94a3b8;margin-top:2px;">Selamat beristirahat!</p>
        </div>
    <?php else: ?>
        <div>
            <?php $__currentLoopData = $jadwalHariIni; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $colors = ['#4f46e5','#059669','#2563eb','#d97706','#dc2626','#7c3aed','#0891b2'];
                $stripColor = $colors[$idx % count($colors)];
                $now = now();
                $jamMulai = $jadwal->jam_mulai ?? null;
                $jamSelesai = $jadwal->jam_selesai ?? null;
                $isCurrent = false;
                if($jamMulai && $jamSelesai) {
                    $mulai   = \Carbon\Carbon::parse($now->format('Y-m-d') . ' ' . $jamMulai);
                    $selesai = \Carbon\Carbon::parse($now->format('Y-m-d') . ' ' . $jamSelesai);
                    $isCurrent = $now->between($mulai, $selesai);
                }
            ?>
            <div style="display:flex;align-items:center;gap:10px;
                         padding:10px 14px;border-bottom:1px solid #f1f5f9;
                         <?php echo e($isCurrent ? 'background:#f0fdf4;' : ''); ?>"
                 onmouseover="this.style.background='#f8fafc'"
                 onmouseout="this.style.background='<?php echo e($isCurrent ? '#f0fdf4' : 'transparent'); ?>'">

                
                <div style="width:3px;height:36px;border-radius:2px;
                             background:<?php echo e($stripColor); ?>;flex-shrink:0;"></div>

                
                <div style="min-width:68px;flex-shrink:0;text-align:center;
                             background:<?php echo e($isCurrent ? '#dcfce7' : '#f8fafc'); ?>;
                             border:1px solid <?php echo e($isCurrent ? '#a7f3d0' : '#e2e8f0'); ?>;
                             border-radius:7px;padding:4px 6px;">
                    <?php if($jamMulai && $jamSelesai): ?>
                        <p style="font-size:.62rem;font-weight:800;color:<?php echo e($isCurrent ? '#059669' : '#1e293b'); ?>;
                                   line-height:1.2;"><?php echo e(substr($jamMulai,0,5)); ?></p>
                        <p style="font-size:.55rem;color:#94a3b8;line-height:1.2;"><?php echo e(substr($jamSelesai,0,5)); ?></p>
                    <?php else: ?>
                        <p style="font-size:.62rem;color:#94a3b8;">—</p>
                    <?php endif; ?>
                </div>

                
                <div class="flex-1 min-w-0">
                    <div style="display:flex;align-items:center;gap:5px;flex-wrap:wrap;">
                        <p style="font-size:.72rem;font-weight:700;color:#1e293b;
                                   line-height:1.2;white-space:nowrap;overflow:hidden;
                                   text-overflow:ellipsis;max-width:160px;">
                            <?php echo e($jadwal->mataPelajaran->nama ?? $jadwal->mata_pelajaran ?? '—'); ?>

                        </p>
                        <?php if($isCurrent): ?>
                            <span style="font-size:.5rem;font-weight:800;background:#dcfce7;
                                          color:#059669;border:1px solid #a7f3d0;border-radius:4px;
                                          padding:1px 5px;flex-shrink:0;">SEDANG BERLANGSUNG</span>
                        <?php endif; ?>
                    </div>
                    <p style="font-size:.6rem;color:#64748b;margin-top:1px;">
                        <?php echo e($jadwal->kelas->nama ?? $jadwal->nama_kelas ?? '—'); ?>

                        <?php if($jadwal->ruangan ?? null): ?>
                            &nbsp;·&nbsp; <?php echo e($jadwal->ruangan); ?>

                        <?php endif; ?>
                    </p>
                </div>

                
                <?php if($isCurrent || true): ?>
                <a href="<?php echo e(route('guru.absensi-siswa.index', ['jadwal_id' => $jadwal->id ?? '', 'kelas_id' => $jadwal->kelas_id ?? ''])); ?>"
                   style="flex-shrink:0;display:inline-flex;align-items:center;gap:4px;
                          padding:5px 10px;border-radius:7px;font-size:.6rem;font-weight:700;
                          text-decoration:none;
                          <?php echo e($isCurrent
                              ? 'background:#4f46e5;color:#fff;'
                              : 'background:#f8fafc;color:#64748b;border:1px solid #e2e8f0;'); ?>">
                    ✅ Absen
                </a>
                <?php endif; ?>

            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div style="padding:8px 14px;border-top:1px solid #f1f5f9;background:#f8fafc;
                     display:flex;align-items:center;justify-content:space-between;">
            <p style="font-size:.6rem;color:#64748b;">
                <?php echo e($jadwalHariIni->count()); ?> sesi mengajar hari ini
            </p>
            <a href="<?php echo e(route('guru.jadwal-mengajar.index')); ?>"
               style="font-size:.6rem;font-weight:700;color:#4f46e5;text-decoration:none;">
                Jadwal lengkap →
            </a>
        </div>
    <?php endif; ?>

</div><?php /**PATH C:\PA 3\smpn-kutime\resources\views/guru/dashboard/jadwal-mengajar.blade.php ENDPATH**/ ?>