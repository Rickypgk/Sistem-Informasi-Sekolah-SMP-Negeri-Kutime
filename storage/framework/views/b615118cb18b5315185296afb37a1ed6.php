
<div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
    <div class="flex items-center justify-between mb-3">
        <div>
            <p class="font-semibold text-slate-800 dark:text-slate-100" style="font-size:.8rem;">
                📈 Tren Kehadiran 7 Hari Terakhir
            </p>
            <p class="text-slate-400" style="font-size:.6rem;">Perbandingan hadir vs tidak hadir</p>
        </div>
        <?php if(isset($totalSiswa) && $totalSiswa > 0): ?>
            <span style="font-size:.6rem;font-weight:600;color:#4f46e5;background:#eef2ff;
                         border:1px solid #c7d2fe;border-radius:6px;padding:3px 8px;">
                <?php echo e($totalSiswa); ?> siswa
            </span>
        <?php endif; ?>
    </div>
    <div id="kehadiranChart" style="min-height:200px;"></div>
</div><?php /**PATH C:\PA 3\smpn-kutime\resources\views/guru/dashboard/attendance-trend.blade.php ENDPATH**/ ?>