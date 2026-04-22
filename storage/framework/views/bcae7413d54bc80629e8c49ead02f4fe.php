<?php $__env->startSection('title', 'Data Diri Siswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl space-y-6">

    
    <div class="bg-white rounded-xl shadow border border-slate-200 p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-slate-800">Profil Siswa</h2>
            <a href="<?php echo e(route('siswa.profil.edit')); ?>"
               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Edit Profil
            </a>
        </div>

        
        <div class="flex flex-col sm:flex-row gap-6 items-start">
            <div class="shrink-0">
                <?php if($user->photo): ?>
                    <img src="<?php echo e(Storage::url($user->photo)); ?>" alt="Foto Profil"
                         class="w-32 h-44 object-cover border-2 border-slate-300 rounded-lg shadow-md">
                <?php else: ?>
                    <div class="w-32 h-44 rounded-lg bg-indigo-100 flex items-center justify-center
                                text-indigo-600 text-5xl font-bold shadow-md">
                        <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                    </div>
                <?php endif; ?>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 flex-1">
                <div>
                    <p class="text-sm text-slate-500 mb-1">Nama Akun</p>
                    <p class="font-medium text-slate-800 text-base"><?php echo e($user->name); ?></p>
                </div>
                <div>
                    <p class="text-sm text-slate-500 mb-1">Email</p>
                    <p class="text-slate-800 text-base"><?php echo e($user->email); ?></p>
                </div>
                <div>
                    <p class="text-sm text-slate-500 mb-1">NIS / NIDN</p>
                    <p class="text-slate-800 text-base"><?php echo e($user->siswa?->nidn ?? '-'); ?></p>
                </div>
                <div>
                    <p class="text-sm text-slate-500 mb-1">Kelas</p>
                    <p class="text-slate-800 text-base"><?php echo e($user->siswa?->kelas?->nama ?? '-'); ?></p>
                </div>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-xl shadow border border-slate-200 p-6">
        <h3 class="text-base font-semibold text-slate-700 mb-4 pb-2 border-b border-slate-100">
            Data Pribadi
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
            <?php $s = $user->siswa; ?>

            <?php if (isset($component)) { $__componentOriginal586adc030ce79f3eb05517f56f18465d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal586adc030ce79f3eb05517f56f18465d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.profil-field','data' => ['label' => 'Nama Lengkap','value' => $s?->nama]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('profil-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Nama Lengkap','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($s?->nama)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.profil-field','data' => ['label' => 'NIK','value' => $s?->nik]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('profil-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'NIK','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($s?->nik)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.profil-field','data' => ['label' => 'Jenis Kelamin','value' => $s?->jk === 'L' ? 'Laki-laki' : ($s?->jk === 'P' ? 'Perempuan' : null)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('profil-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Jenis Kelamin','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($s?->jk === 'L' ? 'Laki-laki' : ($s?->jk === 'P' ? 'Perempuan' : null))]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.profil-field','data' => ['label' => 'Tempat Lahir','value' => $s?->tempat_lahir]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('profil-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Tempat Lahir','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($s?->tempat_lahir)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.profil-field','data' => ['label' => 'Tanggal Lahir','value' => $s?->tgl_lahir?->translatedFormat('d F Y')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('profil-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Tanggal Lahir','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($s?->tgl_lahir?->translatedFormat('d F Y'))]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.profil-field','data' => ['label' => 'Agama','value' => $s?->agama]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('profil-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Agama','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($s?->agama)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.profil-field','data' => ['label' => 'No. Telepon','value' => $s?->no_telp]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('profil-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'No. Telepon','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($s?->no_telp)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.profil-field','data' => ['label' => 'SKHUN','value' => $s?->shkun]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('profil-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'SKHUN','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($s?->shkun)]); ?>
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
            Alamat
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
            <div class="sm:col-span-2">
                <p class="text-xs text-slate-500 mb-0.5">Alamat</p>
                <p class="text-slate-800"><?php echo e($s?->alamat ?? '-'); ?></p>
            </div>
            <?php if (isset($component)) { $__componentOriginal586adc030ce79f3eb05517f56f18465d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal586adc030ce79f3eb05517f56f18465d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.profil-field','data' => ['label' => 'RT','value' => $s?->rt]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('profil-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'RT','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($s?->rt)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.profil-field','data' => ['label' => 'RW','value' => $s?->rw]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('profil-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'RW','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($s?->rw)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.profil-field','data' => ['label' => 'Dusun','value' => $s?->dusun]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('profil-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Dusun','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($s?->dusun)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.profil-field','data' => ['label' => 'Kecamatan','value' => $s?->kecamatan]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('profil-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Kecamatan','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($s?->kecamatan)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.profil-field','data' => ['label' => 'Kode Pos','value' => $s?->kode_pos]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('profil-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Kode Pos','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($s?->kode_pos)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.profil-field','data' => ['label' => 'Jenis Tinggal','value' => $s?->jenis_tinggal]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('profil-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Jenis Tinggal','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($s?->jenis_tinggal)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.profil-field','data' => ['label' => 'Transportasi','value' => $s?->jalan_transportasi]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('profil-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Transportasi','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($s?->jalan_transportasi)]); ?>
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
            Informasi Bantuan
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
            <div>
                <p class="text-xs text-slate-500 mb-0.5">Penerima KPS</p>
                <?php if($s?->penerima_kps === 'Ya'): ?>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                 bg-green-100 text-green-700">Ya</span>
                <?php else: ?>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                 bg-slate-100 text-slate-600">Tidak</span>
                <?php endif; ?>
            </div>
            <?php if (isset($component)) { $__componentOriginal586adc030ce79f3eb05517f56f18465d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal586adc030ce79f3eb05517f56f18465d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.profil-field','data' => ['label' => 'No. KPS','value' => $s?->no_kps]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('profil-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'No. KPS','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($s?->no_kps)]); ?>
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PA 3\smpn-kutime\resources\views/siswa/profil.blade.php ENDPATH**/ ?>