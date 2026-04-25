

<?php
    // Baca variabel dari controller — semua pakai ?? untuk safety
    $isWK          = isset($isWaliKelas) && (bool)$isWaliKelas;
    $kelasWali     = $kelasWaliData   ?? null;
    $jmlSiswa      = (int)($totalSiswaWali ?? 0);
    $rekapDash     = is_array($rekapDataDashboard ?? null) ? $rekapDataDashboard : [];
    $rekapBulan    = $rekapBulan      ?? now()->month;
    $rekapTahun    = $rekapTahun      ?? now()->year;
    $bulanNm       = now()->isoFormat('MMMM Y');
?>


<?php if($isWK): ?>
<?php
    /* ── Distribusi kehadiran dari rekapDataDashboard ──────────
       Hitung siswa berdasarkan % kehadiran bulan ini.
       Jika rekapDash kosong (belum ada absensi), tampilkan
       "Belum ada data" alih-alih angka 0 yang membingungkan.
    ────────────────────────────────────────────────────────── */
    $pctTinggi       = 0; // ≥ 80%
    $pctSedang       = 0; // 60–79%
    $pctRendah       = 0; // < 60%
    $adaDataAbsensi  = false;

    foreach ($rekapDash as $sid => $r) {
        if (!is_array($r)) continue;
        $tot = array_sum($r); // hadir+sakit+izin+alpha
        if ($tot <= 0) continue;

        $adaDataAbsensi = true;
        $hadir = (int)($r['hadir'] ?? 0);
        $pct   = round($hadir / $tot * 100);

        if ($pct >= 80)     $pctTinggi++;
        elseif ($pct >= 60) $pctSedang++;
        else                $pctRendah++;
    }

    $totalDenganData = $pctTinggi + $pctSedang + $pctRendah;
    $belumAbsensi    = max(0, $jmlSiswa - $totalDenganData);

    // Estimasi rata-rata kehadiran kelas (weighted)
    $avgPct   = $totalDenganData > 0
        ? round(($pctTinggi * 90 + $pctSedang * 70 + $pctRendah * 40) / $totalDenganData)
        : 0;
    $avgColor = $avgPct >= 80 ? '#059669' : ($avgPct >= 60 ? '#a16207' : '#b91c1c');
?>

<div style="background:#fff;border-radius:12px;border:1.5px solid #fde68a;
             box-shadow:0 1px 6px rgba(245,158,11,.1);overflow:hidden;">

    
    <div style="background:linear-gradient(135deg,#fffbeb 0%,#fef3c7 100%);
                padding:10px 14px;border-bottom:1.5px solid #fde68a;">
        <div style="display:flex;align-items:center;gap:8px;">

            
            <div style="width:30px;height:30px;border-radius:9px;flex-shrink:0;
                        background:linear-gradient(135deg,#f59e0b,#d97706);
                        display:flex;align-items:center;justify-content:center;
                        font-size:.9rem;box-shadow:0 2px 6px rgba(245,158,11,.3);">
                ⭐
            </div>

            
            <div style="flex:1;min-width:0;">
                <p style="font-size:.72rem;font-weight:800;color:#92400e;
                           margin:0;line-height:1.2;letter-spacing:-.01em;">
                    Wali Kelas
                </p>
                <?php if($kelasWali): ?>
                    <p style="font-size:.58rem;color:#a16207;margin:0;line-height:1.35;">
                        <?php echo e($kelasWali->nama ?? $kelasWali->name ?? '—'); ?>

                        <?php if(!empty($kelasWali->tingkat)): ?>
                            <span style="opacity:.6;">&nbsp;·&nbsp;</span><?php echo e($kelasWali->tingkat); ?>

                        <?php endif; ?>
                    </p>
                <?php else: ?>
                    <p style="font-size:.58rem;color:#a16207;margin:0;">Kelas belum diset</p>
                <?php endif; ?>
            </div>

            
            <div style="background:rgba(217,119,6,.18);border:1px solid rgba(217,119,6,.35);
                        border-radius:9px;padding:4px 10px;text-align:center;flex-shrink:0;">
                <span style="font-size:.95rem;font-weight:800;color:#92400e;
                             display:block;line-height:1.1;"><?php echo e($jmlSiswa); ?></span>
                <span style="font-size:.5rem;color:#a16207;font-weight:600;
                             display:block;text-transform:uppercase;letter-spacing:.04em;">siswa</span>
            </div>

        </div>
    </div>

    
    <div style="padding:12px 14px;display:flex;flex-direction:column;gap:10px;">

        
        <div>
            <p style="font-size:.58rem;font-weight:700;color:#64748b;text-transform:uppercase;
                      letter-spacing:.06em;margin:0 0 6px;">
                Kehadiran <?php echo e($bulanNm); ?>

            </p>

            <?php if($jmlSiswa === 0): ?>
                
                <div style="padding:10px;text-align:center;background:#f8fafc;
                             border-radius:8px;border:1px solid #e2e8f0;">
                    <p style="font-size:.62rem;color:#94a3b8;margin:0;">
                        Belum ada siswa terdaftar di kelas ini
                    </p>
                </div>

            <?php elseif(!$adaDataAbsensi): ?>
                
                <div style="padding:8px 10px;border-radius:8px;background:#f8fafc;
                             border:1px solid #e2e8f0;margin-bottom:4px;">
                    <p style="font-size:.62rem;color:#94a3b8;margin:0;text-align:center;">
                        Belum ada data absensi bulan ini
                    </p>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;
                             padding:5px 9px;border-radius:7px;background:#f1f5f9;">
                    <span style="font-size:.62rem;font-weight:600;color:#64748b;">
                        Belum Absensi
                    </span>
                    <span style="font-size:.72rem;font-weight:800;color:#64748b;">
                        <?php echo e($jmlSiswa); ?>

                        <span style="font-size:.52rem;font-weight:600;">siswa</span>
                    </span>
                </div>

            <?php else: ?>
                
                <div style="display:flex;flex-direction:column;gap:4px;">

                    <?php if($pctTinggi > 0): ?>
                    <div style="display:flex;align-items:center;justify-content:space-between;
                                 padding:5px 9px;border-radius:7px;background:#ecfdf5;">
                        <span style="font-size:.6rem;font-weight:600;color:#059669;">≥ 80% Hadir</span>
                        <span style="font-size:.72rem;font-weight:800;color:#059669;">
                            <?php echo e($pctTinggi); ?>

                            <span style="font-size:.52rem;font-weight:600;">siswa</span>
                        </span>
                    </div>
                    <?php endif; ?>

                    <?php if($pctSedang > 0): ?>
                    <div style="display:flex;align-items:center;justify-content:space-between;
                                 padding:5px 9px;border-radius:7px;background:#fef9c3;">
                        <span style="font-size:.6rem;font-weight:600;color:#a16207;">60–79% Hadir</span>
                        <span style="font-size:.72rem;font-weight:800;color:#a16207;">
                            <?php echo e($pctSedang); ?>

                            <span style="font-size:.52rem;font-weight:600;">siswa</span>
                        </span>
                    </div>
                    <?php endif; ?>

                    <?php if($pctRendah > 0): ?>
                    <div style="display:flex;align-items:center;justify-content:space-between;
                                 padding:5px 9px;border-radius:7px;background:#fee2e2;">
                        <span style="font-size:.6rem;font-weight:600;color:#b91c1c;">&lt; 60% Hadir</span>
                        <span style="font-size:.72rem;font-weight:800;color:#b91c1c;">
                            <?php echo e($pctRendah); ?>

                            <span style="font-size:.52rem;font-weight:600;">siswa</span>
                        </span>
                    </div>
                    <?php endif; ?>

                    <?php if($belumAbsensi > 0): ?>
                    <div style="display:flex;align-items:center;justify-content:space-between;
                                 padding:5px 9px;border-radius:7px;background:#f1f5f9;">
                        <span style="font-size:.6rem;font-weight:600;color:#64748b;">Belum Absensi</span>
                        <span style="font-size:.72rem;font-weight:800;color:#64748b;">
                            <?php echo e($belumAbsensi); ?>

                            <span style="font-size:.52rem;font-weight:600;">siswa</span>
                        </span>
                    </div>
                    <?php endif; ?>

                </div>

                
                <?php if($avgPct > 0): ?>
                <div style="margin-top:6px;padding:7px 10px;background:#f8fafc;
                             border-radius:8px;border:1px solid #e2e8f0;">
                    <div style="display:flex;justify-content:space-between;
                                 align-items:center;margin-bottom:4px;">
                        <p style="font-size:.57rem;font-weight:600;color:#64748b;margin:0;">
                            Est. Rata-rata Kelas
                        </p>
                        <p style="font-size:.72rem;font-weight:800;
                                   color:<?php echo e($avgColor); ?>;margin:0;">
                            ~<?php echo e($avgPct); ?>%
                        </p>
                    </div>
                    <div style="height:5px;background:#e2e8f0;border-radius:99px;overflow:hidden;">
                        <div style="height:100%;width:<?php echo e($avgPct); ?>%;
                                     background:<?php echo e($avgColor); ?>;border-radius:99px;
                                     transition:width .4s;"></div>
                    </div>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        
        <div style="display:flex;flex-direction:column;gap:5px;
                     padding-top:8px;border-top:1px solid #f1f5f9;">

            <?php if(Route::has('guru.wali-kelas')): ?>
            <a href="<?php echo e(route('guru.wali-kelas')); ?>"
               style="display:flex;align-items:center;gap:6px;padding:6px 10px;
                      border-radius:8px;font-size:.63rem;font-weight:600;
                      color:#92400e;text-decoration:none;
                      background:#fffbeb;border:1px solid #fde68a;
                      transition:background .15s;"
               onmouseover="this.style.background='#fef3c7'"
               onmouseout="this.style.background='#fffbeb'">
                <span style="font-size:.8rem;">👥</span>
                Data Kelas Saya
            </a>
            <?php endif; ?>

            <?php if(Route::has('guru.absensi-siswa.rekap') && $kelasWali): ?>
            <a href="<?php echo e(route('guru.absensi-siswa.rekap', ['kelas_id' => $kelasWali->id])); ?>"
               style="display:flex;align-items:center;gap:6px;padding:6px 10px;
                      border-radius:8px;font-size:.63rem;font-weight:600;
                      color:#4338ca;text-decoration:none;
                      background:#eef2ff;border:1px solid #c7d2fe;
                      transition:background .15s;"
               onmouseover="this.style.background='#e0e7ff'"
               onmouseout="this.style.background='#eef2ff'">
                <span style="font-size:.8rem;">📊</span>
                Rekap Absensi Kelas
            </a>
            <?php endif; ?>

            <?php if(Route::has('guru.absensi-siswa.index')): ?>
            <a href="<?php echo e(route('guru.absensi-siswa.index', $kelasWali ? ['kelas_id' => $kelasWali->id] : [])); ?>"
               style="display:flex;align-items:center;gap:6px;padding:6px 10px;
                      border-radius:8px;font-size:.63rem;font-weight:600;
                      color:#065f46;text-decoration:none;
                      background:#ecfdf5;border:1px solid #a7f3d0;
                      transition:background .15s;"
               onmouseover="this.style.background='#d1fae5'"
               onmouseout="this.style.background='#ecfdf5'">
                <span style="font-size:.8rem;">✅</span>
                Catat Absensi Hari Ini
            </a>
            <?php endif; ?>

        </div>

    </div>
</div>

<?php endif; ?> <?php /**PATH C:\PA 3\smpn-kutime\resources\views/guru/dashboard/wali-kelas-summary.blade.php ENDPATH**/ ?>