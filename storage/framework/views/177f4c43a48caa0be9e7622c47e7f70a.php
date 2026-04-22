<?php $__env->startSection('title', 'Data Diri Guru'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl space-y-6">

    
    <?php if(session('success')): ?>
        <div class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error') || $errors->any()): ?>
        <div class="px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
            <?php echo e(session('error')); ?>

            <?php if($errors->any()): ?>
                <ul class="list-disc list-inside mt-1">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    
    <div class="bg-white rounded-xl shadow border border-slate-200 p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-slate-800">Profil Guru</h2>
            <a href="<?php echo e(route('guru.profil.edit')); ?>"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-indigo-600 text-white
                      text-sm font-medium hover:bg-indigo-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Edit Profil
            </a>
        </div>

        <div class="flex flex-col sm:flex-row gap-8 items-start">
            
            <div class="shrink-0">
                <?php if($user->photo): ?>
                    <img src="<?php echo e(Storage::url($user->photo)); ?>" alt="Foto Profil Guru"
                         class="w-40 h-[213px] object-cover border-2 border-slate-300 rounded-xl shadow-md">
                <?php else: ?>
                    <div class="w-40 h-[213px] rounded-xl bg-indigo-100 flex items-center justify-center
                                text-indigo-700 text-6xl font-bold shadow-md">
                        <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                    </div>
                <?php endif; ?>
            </div>

            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-5 flex-1">
                <div>
                    <p class="text-sm text-slate-500 mb-1">Nama Akun</p>
                    <p class="font-medium text-slate-800 text-base"><?php echo e($user->name); ?></p>
                </div>

                <div>
                    <p class="text-sm text-slate-500 mb-1">Email</p>
                    <p class="text-slate-800 text-base"><?php echo e($user->email); ?></p>
                </div>

                <div>
                    <p class="text-sm text-slate-500 mb-1">NIP</p>
                    <p class="text-slate-800 text-base"><?php echo e($user->guru?->nip ?? '—'); ?></p>
                </div>

                <div>
                    <p class="text-sm text-slate-500 mb-1">Wali Kelas</p>
                    <?php if($user->guru?->kelas): ?>
                        <p class="text-slate-800 text-base font-medium">
                            <?php echo e($user->guru->kelas->nama); ?>

                            <span class="text-sm text-slate-500 ml-2">
                                (<?php echo e($user->guru->kelas->tingkat); ?> • <?php echo e($user->guru->kelas->tahun_ajaran); ?>)
                            </span>
                        </p>
                    <?php else: ?>
                        <p class="text-slate-500 text-base italic">— Belum menjadi wali kelas —</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <?php $g = $user->guru; ?>

    <div class="bg-white rounded-xl shadow border border-slate-200 p-6">
        <h3 class="text-base font-semibold text-slate-700 mb-4 pb-2 border-b border-slate-100">
            Data Pribadi
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
            <?php if (isset($component)) { $__componentOriginal586adc030ce79f3eb05517f56f18465d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal586adc030ce79f3eb05517f56f18465d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.profil-field','data' => ['label' => 'Nama Lengkap','value' => $g?->nama ?? '—']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('profil-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Nama Lengkap','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($g?->nama ?? '—')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal586adc030ce79f3eb05517f56f18465d)): ?>
<?php $attributes = $__attributesOriginal586adc030ce79f3eb05517f56f18465d; ?>
<?php unset($__attributesOriginal586adc030ce79f3eb05517f56f18465d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal586adc030ce79f3eb05517f56f18465d)): ?>
<?php $component = $__componentOriginal586adc030ce79f3eb05517f56f18465d; ?>
<?php unset($__componentOriginal586adc030ce79f3eb05517f56f18465d); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal586adc030ce79f3eb05517f56f18465d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal586adc030ce79f3eb05517f56f18465d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.profil-field','data' => ['label' => 'Jenis Kelamin','value' => $g?->jk === 'L' ? 'Laki-laki' : ($g?->jk === 'P' ? 'Perempuan' : '—')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('profil-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Jenis Kelamin','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($g?->jk === 'L' ? 'Laki-laki' : ($g?->jk === 'P' ? 'Perempuan' : '—'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal586adc030ce79f3eb05517f56f18465d)): ?>
<?php $attributes = $__attributesOriginal586adc030ce79f3eb05517f56f18465d; ?>
<?php unset($__attributesOriginal586adc030ce79f3eb05517f56f18465d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal586adc030ce79f3eb05517f56f18465d)): ?>
<?php $component = $__componentOriginal586adc030ce79f3eb05517f56f18465d; ?>
<?php unset($__componentOriginal586adc030ce79f3eb05517f56f18465d); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal586adc030ce79f3eb05517f56f18465d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal586adc030ce79f3eb05517f56f18465d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.profil-field','data' => ['label' => 'Tempat Lahir','value' => $g?->tempat_lahir ?? '—']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('profil-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Tempat Lahir','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($g?->tempat_lahir ?? '—')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal586adc030ce79f3eb05517f56f18465d)): ?>
<?php $attributes = $__attributesOriginal586adc030ce79f3eb05517f56f18465d; ?>
<?php unset($__attributesOriginal586adc030ce79f3eb05517f56f18465d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal586adc030ce79f3eb05517f56f18465d)): ?>
<?php $component = $__componentOriginal586adc030ce79f3eb05517f56f18465d; ?>
<?php unset($__componentOriginal586adc030ce79f3eb05517f56f18465d); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal586adc030ce79f3eb05517f56f18465d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal586adc030ce79f3eb05517f56f18465d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.profil-field','data' => ['label' => 'Tanggal Lahir','value' => $g?->tanggal_lahir?->translatedFormat('d F Y') ?? '—']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('profil-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Tanggal Lahir','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($g?->tanggal_lahir?->translatedFormat('d F Y') ?? '—')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal586adc030ce79f3eb05517f56f18465d)): ?>
<?php $attributes = $__attributesOriginal586adc030ce79f3eb05517f56f18465d; ?>
<?php unset($__attributesOriginal586adc030ce79f3eb05517f56f18465d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal586adc030ce79f3eb05517f56f18465d)): ?>
<?php $component = $__componentOriginal586adc030ce79f3eb05517f56f18465d; ?>
<?php unset($__componentOriginal586adc030ce79f3eb05517f56f18465d); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal586adc030ce79f3eb05517f56f18465d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal586adc030ce79f3eb05517f56f18465d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.profil-field','data' => ['label' => 'Pendidikan Terakhir','value' => $g?->pendidikan_terakhir ?? '—']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('profil-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Pendidikan Terakhir','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($g?->pendidikan_terakhir ?? '—')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal586adc030ce79f3eb05517f56f18465d)): ?>
<?php $attributes = $__attributesOriginal586adc030ce79f3eb05517f56f18465d; ?>
<?php unset($__attributesOriginal586adc030ce79f3eb05517f56f18465d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal586adc030ce79f3eb05517f56f18465d)): ?>
<?php $component = $__componentOriginal586adc030ce79f3eb05517f56f18465d; ?>
<?php unset($__componentOriginal586adc030ce79f3eb05517f56f18465d); ?>
<?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow border border-slate-200 p-6">
        <h3 class="text-base font-semibold text-slate-700 mb-4 pb-2 border-b border-slate-100">
            Data Kepegawaian
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
            <?php if (isset($component)) { $__componentOriginal586adc030ce79f3eb05517f56f18465d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal586adc030ce79f3eb05517f56f18465d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.profil-field','data' => ['label' => 'Status Pegawai','value' => $g?->status_pegawai ?? '—']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('profil-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Status Pegawai','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($g?->status_pegawai ?? '—')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal586adc030ce79f3eb05517f56f18465d)): ?>
<?php $attributes = $__attributesOriginal586adc030ce79f3eb05517f56f18465d; ?>
<?php unset($__attributesOriginal586adc030ce79f3eb05517f56f18465d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal586adc030ce79f3eb05517f56f18465d)): ?>
<?php $component = $__componentOriginal586adc030ce79f3eb05517f56f18465d; ?>
<?php unset($__componentOriginal586adc030ce79f3eb05517f56f18465d); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal586adc030ce79f3eb05517f56f18465d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal586adc030ce79f3eb05517f56f18465d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.profil-field','data' => ['label' => 'Pangkat / Gol. Ruang','value' => $g?->pangkat_gol_ruang ?? '—']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('profil-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Pangkat / Gol. Ruang','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($g?->pangkat_gol_ruang ?? '—')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal586adc030ce79f3eb05517f56f18465d)): ?>
<?php $attributes = $__attributesOriginal586adc030ce79f3eb05517f56f18465d; ?>
<?php unset($__attributesOriginal586adc030ce79f3eb05517f56f18465d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal586adc030ce79f3eb05517f56f18465d)): ?>
<?php $component = $__componentOriginal586adc030ce79f3eb05517f56f18465d; ?>
<?php unset($__componentOriginal586adc030ce79f3eb05517f56f18465d); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal586adc030ce79f3eb05517f56f18465d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal586adc030ce79f3eb05517f56f18465d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.profil-field','data' => ['label' => 'No. SK Pertama','value' => $g?->no_sk_pertama ?? '—']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('profil-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'No. SK Pertama','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($g?->no_sk_pertama ?? '—')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal586adc030ce79f3eb05517f56f18465d)): ?>
<?php $attributes = $__attributesOriginal586adc030ce79f3eb05517f56f18465d; ?>
<?php unset($__attributesOriginal586adc030ce79f3eb05517f56f18465d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal586adc030ce79f3eb05517f56f18465d)): ?>
<?php $component = $__componentOriginal586adc030ce79f3eb05517f56f18465d; ?>
<?php unset($__componentOriginal586adc030ce79f3eb05517f56f18465d); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal586adc030ce79f3eb05517f56f18465d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal586adc030ce79f3eb05517f56f18465d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.profil-field','data' => ['label' => 'No. SK Terakhir','value' => $g?->no_sk_terakhir ?? '—']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('profil-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'No. SK Terakhir','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($g?->no_sk_terakhir ?? '—')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal586adc030ce79f3eb05517f56f18465d)): ?>
<?php $attributes = $__attributesOriginal586adc030ce79f3eb05517f56f18465d; ?>
<?php unset($__attributesOriginal586adc030ce79f3eb05517f56f18465d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal586adc030ce79f3eb05517f56f18465d)): ?>
<?php $component = $__componentOriginal586adc030ce79f3eb05517f56f18465d; ?>
<?php unset($__componentOriginal586adc030ce79f3eb05517f56f18465d); ?>
<?php endif; ?>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PA 3\smpn-kutime\resources\views/guru/profil.blade.php ENDPATH**/ ?>