<?php $__env->startSection('title', 'Daftar Siswa Wali Kelas'); ?>

<?php $__env->startSection('content'); ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">
                Siswa Wali Kelas
            </h1>
            <?php if($kelas): ?>
                <p class="text-slate-600 dark:text-slate-400 mt-1">
                    <?php echo e($kelas->nama); ?> • <?php echo e($kelas->tingkat); ?> • <?php echo e($kelas->tahun_ajaran); ?>

                </p>
            <?php else: ?>
                <p class="text-slate-500 dark:text-slate-400 mt-1 italic">
                    Anda belum ditugaskan sebagai wali kelas.
                </p>
            <?php endif; ?>
        </div>

        <div class="flex items-center gap-3">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">
                <?php echo e($siswa->count()); ?> Siswa
            </span>
        </div>
    </div>

    <?php if(!$kelas || $siswa->isEmpty()): ?>
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-10 text-center">
            <div class="text-6xl mb-4 text-slate-300 dark:text-slate-600">👩‍🎓👨‍🎓</div>
            <h3 class="text-xl font-semibold text-slate-700 dark:text-slate-300 mb-2">
                <?php if(!$kelas): ?> Belum Menjadi Wali Kelas <?php else: ?> Kelas Masih Kosong <?php endif; ?>
            </h3>
            <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto">
                <?php if(!$kelas): ?> Hubungi admin untuk ditugaskan sebagai wali kelas.
                <?php else: ?> Kelas ini belum memiliki siswa yang terdaftar. <?php endif; ?>
            </p>
        </div>
    <?php else: ?>
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 dark:bg-slate-700/30">
                        <tr>
                            <th class="px-6 py-4 font-medium text-slate-600 dark:text-slate-300 w-14">#</th>
                            <th class="px-6 py-4 font-medium text-slate-600 dark:text-slate-300">Foto</th>
                            <th class="px-6 py-4 font-medium text-slate-600 dark:text-slate-300">Nama Lengkap</th>
                            <th class="px-6 py-4 font-medium text-slate-600 dark:text-slate-300">NIS/NIK</th>
                            <th class="px-6 py-4 font-medium text-slate-600 dark:text-slate-300 text-center">JK</th>
                            <th class="px-6 py-4 font-medium text-slate-600 dark:text-slate-300 text-center">Tgl Lahir</th>
                            <th class="px-6 py-4 font-medium text-slate-600 dark:text-slate-300 text-center">Terlambat Bulan Ini</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        <?php $__currentLoopData = $siswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/20 transition-colors">
                                <td class="px-6 py-4 text-slate-500 dark:text-slate-400"><?php echo e($index + 1); ?></td>
                                <td class="px-6 py-4">
                                    <?php if($s->foto): ?>
                                        <img src="<?php echo e($s->foto); ?>" alt="<?php echo e($s->nama_tampil); ?>" class="w-10 h-10 rounded-full object-cover border-2 border-indigo-100 dark:border-indigo-900/40">
                                    <?php else: ?>
                                        <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-indigo-700 dark:text-indigo-300 font-bold text-lg">
                                            <?php echo e($s->inisial); ?>

                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-800 dark:text-slate-100">
                                    <?php echo e($s->nama_tampil); ?>

                                </td>
                                <td class="px-6 py-4 text-slate-600 dark:text-slate-300 font-mono text-sm">
                                    <?php echo e($s->nis ?? $s->nik ?? '—'); ?>

                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php if($s->jk === 'L'): ?>
                                        <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">L</span>
                                    <?php elseif($s->jk === 'P'): ?>
                                        <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-pink-100 text-pink-700 dark:bg-pink-900/40 dark:text-pink-300">P</span>
                                    <?php else: ?>
                                        <span class="text-slate-400">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-center text-slate-600 dark:text-slate-300">
                                    <?php echo e($s->tanggal_lahir ? $s->tanggal_lahir->format('d/m/Y') : '—'); ?>

                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php if($s->terlambat_count > 3): ?>
                                        <span class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300 shadow-sm">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Terlambat <?php echo e($s->terlambat_count); ?>x
                                        </span>
                                    <?php else: ?>
                                        <span class="text-slate-500 dark:text-slate-400 text-xs">
                                            <?php echo e($s->terlambat_count); ?>x
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PA 3\smpn-kutime\resources\views/guru/wali-kelas/index.blade.php ENDPATH**/ ?>