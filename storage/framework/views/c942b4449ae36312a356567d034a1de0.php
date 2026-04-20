<?php if($siswaBerisiko->isNotEmpty()): ?>
<div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
    <div class="px-5 py-3 border-b border-slate-100 dark:border-slate-700">
        <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100">
            Siswa Berisiko
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead class="bg-slate-50 dark:bg-slate-700/20">
                <tr>
                    <th class="px-5 py-3 text-left font-medium text-slate-600 dark:text-slate-300">Nama</th>
                    <th class="px-4 py-3 text-center font-medium text-slate-600 dark:text-slate-300">% Hadir</th>
                    <th class="px-4 py-3 text-center font-medium text-slate-600 dark:text-slate-300">Rata Nilai</th>
                    <th class="px-4 py-3 text-center font-medium text-slate-600 dark:text-slate-300">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/40">
                <?php $__currentLoopData = $siswaBerisiko; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/10 transition-colors">
                    <td class="px-5 py-3 font-medium text-slate-800 dark:text-slate-100">
                        <?php echo e($siswa->nama); ?>

                    </td>
                    <td class="px-4 py-3 text-center <?php echo e($siswa->kehadiran < 75 ? 'text-red-600 font-semibold' : 'text-green-600'); ?>">
                        <?php echo e(number_format($siswa->kehadiran ?? 0, 1)); ?>%
                    </td>
                    <td class="px-4 py-3 text-center <?php echo e($siswa->nilai_rata < 70 ? 'text-red-600 font-semibold' : 'text-green-600'); ?>">
                        <?php echo e(number_format($siswa->nilai_rata ?? 0, 1)); ?>

                    </td>
                    <td class="px-4 py-3 text-center">
                        <?php if($siswa->kehadiran < 75 || $siswa->nilai_rata < 70): ?>
                            <span class="inline-flex px-2.5 py-0.5 text-[10px] font-medium rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300">
                                Risiko
                            </span>
                        <?php else: ?>
                            <span class="inline-flex px-2.5 py-0.5 text-[10px] font-medium rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300">
                                Baik
                            </span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?><?php /**PATH C:\PA 3\smpn-kutime\resources\views/guru/dashboard/at-risk-students.blade.php ENDPATH**/ ?>