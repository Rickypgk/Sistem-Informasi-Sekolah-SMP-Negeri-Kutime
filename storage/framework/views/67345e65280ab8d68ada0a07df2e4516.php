<?php $__env->startSection('title', 'Dashboard Admin'); ?>

<?php $__env->startSection('content'); ?>


<?php
    $widgetPengumuman ??= collect();
    $stats            ??= ['total_guru'=>0,'total_siswa'=>0,'total_kelas'=>0,'guru_hadir'=>0];
    $jadwalHariIni    ??= collect();
    $activityLogs     ??= collect();
    $absensiMinggu    ??= ['hadir'=>0,'sakit'=>0,'izin'=>0,'alpha'=>0,'telat'=>0];
    $guruUltah        ??= collect();
    $kelasTanpaWali   ??= 0;
?>

<div class="space-y-4">

    
    <div class="flex items-center justify-between flex-wrap gap-2">
        <div>
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100 leading-tight">
                👋 Selamat datang, <?php echo e(auth()->user()->name ?? 'Admin'); ?>!
            </h2>
            <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">
                <?php echo e(now()->isoFormat('dddd, D MMMM Y · HH:mm')); ?> WIB
            </p>
        </div>
        
        <div class="flex items-center gap-2 flex-wrap">
            <?php if(Route::has('admin.users.index')): ?>
            <a href="<?php echo e(route('admin.users.index')); ?>"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl
                      bg-indigo-600 text-white text-[10px] font-bold
                      hover:bg-indigo-700 transition shadow-sm">
                ➕ Tambah User
            </a>
            <?php endif; ?>
            <?php if(Route::has('admin.pengumuman.create')): ?>
            <a href="<?php echo e(route('admin.pengumuman.create')); ?>"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl
                      bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300
                      border border-slate-200 dark:border-slate-700
                      text-[10px] font-bold hover:bg-slate-50 transition shadow-sm">
                📢 Buat Pengumuman
            </a>
            <?php endif; ?>
        </div>
    </div>

    
    <?php echo $__env->make('admin.dashboard.stats', [
        'stats'          => $stats,
        'kelasTanpaWali' => $kelasTanpaWali,
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('admin.dashboard.absensi_minggu', [
        'absensiMinggu' => $absensiMinggu,
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        
        <?php echo $__env->make('admin.dashboard.schedule', [
            'jadwalHariIni' => $jadwalHariIni,
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <?php echo $__env->make('admin.dashboard.announcement', [
            'widgetPengumuman' => $widgetPengumuman,
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        
        <div class="lg:col-span-2">
            <?php echo $__env->make('admin.dashboard.activity_log', [
                'activityLogs' => $activityLogs,
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        
        <div class="lg:col-span-1 space-y-4">
            <?php echo $__env->make('admin.dashboard.ultah_guru', [
                'guruUltah' => $guruUltah,
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                        dark:border-slate-700 shadow-sm p-4">
                <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400
                           uppercase tracking-wider mb-3">
                    ⚡ Akses Cepat
                </p>
                <div class="grid grid-cols-2 gap-2">
                    <?php
                        $quickLinks = [
                            ['icon'=>'📋','label'=>'Absensi Guru', 'route'=>'admin.absensi-guru.index',  'color'=>'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300'],
                            ['icon'=>'📊','label'=>'Rekap Absensi','route'=>'admin.absensi-guru.rekap',  'color'=>'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300'],
                            ['icon'=>'👥','label'=>'Data Guru',    'route'=>'admin.users.index',          'color'=>'bg-violet-50 dark:bg-violet-900/20 text-violet-700 dark:text-violet-300'],
                            ['icon'=>'🏫','label'=>'Kelola Kelas', 'route'=>'admin.kelas.index',          'color'=>'bg-sky-50 dark:bg-sky-900/20 text-sky-700 dark:text-sky-300'],
                            ['icon'=>'📢','label'=>'Pengumuman',   'route'=>'admin.pengumuman',           'color'=>'bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300'],
                            ['icon'=>'🎓','label'=>'Data Siswa',   'route'=>'admin.users.index',          'color'=>'bg-pink-50 dark:bg-pink-900/20 text-pink-700 dark:text-pink-300'],
                        ];
                    ?>

                    <?php $__currentLoopData = $quickLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ql): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(Route::has($ql['route'])): ?>
                    <a href="<?php echo e(route($ql['route'])); ?>"
                       class="flex items-center gap-2 px-3 py-2.5 rounded-xl
                              <?php echo e($ql['color']); ?> hover:opacity-80
                              transition-opacity text-xs font-semibold">
                        <span><?php echo e($ql['icon']); ?></span>
                        <span class="leading-tight"><?php echo e($ql['label']); ?></span>
                    </a>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PA 3\smpn-kutime\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>