<?php $__env->startSection('title', 'Data Diri Guru'); ?>

<?php $__env->startSection('content'); ?>
<?php $g = $user->guru; ?>


<?php if(session('success')): ?>
    <div class="px-3 py-2 bg-green-50 border border-green-200 text-green-700 rounded-lg text-xs mb-4">
        <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>
<?php if(session('error') || $errors->any()): ?>
    <div class="px-3 py-2 bg-red-50 border border-red-200 text-red-700 rounded-lg text-xs mb-4">
        <?php echo e(session('error')); ?>

        <?php if($errors->any()): ?>
            <ul class="list-disc list-inside mt-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($err); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        <?php endif; ?>
    </div>
<?php endif; ?>


<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

    
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Profil Akun</h3>
            <button onclick="bukaModal('modalAkun')"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-600
                           text-xs font-semibold hover:bg-indigo-100 transition">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Edit
            </button>
        </div>

        <div class="flex items-center gap-3">
            <?php if($user->photo): ?>
                <img src="<?php echo e(Storage::url($user->photo)); ?>" alt="Foto"
                     class="w-14 h-[74px] object-cover rounded-lg border border-slate-200 shrink-0">
            <?php else: ?>
                <div class="w-14 h-[74px] rounded-lg bg-indigo-100 flex items-center justify-center
                            text-indigo-600 text-2xl font-bold shrink-0">
                    <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                </div>
            <?php endif; ?>
            <div class="min-w-0 space-y-1.5">
                <div>
                    <p class="text-[10px] text-slate-400 font-medium">Nama Akun</p>
                    <p class="text-sm font-semibold text-slate-800 truncate"><?php echo e($user->name); ?></p>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 font-medium">Email</p>
                    <p class="text-xs text-slate-600 truncate"><?php echo e($user->email); ?></p>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 font-medium">NIP</p>
                    <p class="text-xs text-slate-600"><?php echo e($g?->nip ?? '—'); ?></p>
                </div>
            </div>
        </div>

        <?php if($g?->kelas): ?>
            <div class="mt-3 pt-3 border-t border-slate-100">
                <p class="text-[10px] text-slate-400 font-medium mb-0.5">Wali Kelas</p>
                <p class="text-xs font-semibold text-indigo-700"><?php echo e($g->kelas->nama); ?>

                    <span class="text-slate-400 font-normal">· <?php echo e($g->kelas->tingkat); ?> · <?php echo e($g->kelas->tahun_ajaran); ?></span>
                </p>
            </div>
        <?php else: ?>
            <div class="mt-3 pt-3 border-t border-slate-100">
                <p class="text-xs text-slate-400 italic">Belum menjadi wali kelas</p>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Identitas & Tugas</h3>
            <button onclick="bukaModal('modalIdentitas')"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-600
                           text-xs font-semibold hover:bg-indigo-100 transition">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Edit
            </button>
        </div>
        <div class="grid grid-cols-2 gap-x-4 gap-y-3">
            <?php $__currentLoopData = [
                ['NIP', $g?->nip ?? '—'],
                ['Nama Lengkap', $g?->nama ?? '—'],
                ['Jenis Kelamin', $g?->jk === 'L' ? 'Laki-laki' : ($g?->jk === 'P' ? 'Perempuan' : '—')],
                ['Tempat Lahir', $g?->tempat_lahir ?? '—'],
                ['Tanggal Lahir', $g?->tanggal_lahir?->translatedFormat('d F Y') ?? '—'],
                ['Pendidikan', $g?->pendidikan_terakhir ?? '—'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$lbl, $val]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div>
                <p class="text-[10px] text-slate-400 font-medium"><?php echo e($lbl); ?></p>
                <p class="text-xs text-slate-700 font-medium mt-0.5 truncate" title="<?php echo e($val); ?>"><?php echo e($val); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Data Pribadi</h3>
            <button onclick="bukaModal('modalPribadi')"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-600
                           text-xs font-semibold hover:bg-indigo-100 transition">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Edit
            </button>
        </div>
        <div class="grid grid-cols-2 gap-x-4 gap-y-3">
            <?php $__currentLoopData = [
                ['Nama Lengkap', $g?->nama ?? '—'],
                ['Jenis Kelamin', $g?->jk === 'L' ? 'Laki-laki' : ($g?->jk === 'P' ? 'Perempuan' : '—')],
                ['Tempat Lahir', $g?->tempat_lahir ?? '—'],
                ['Tanggal Lahir', $g?->tanggal_lahir?->translatedFormat('d F Y') ?? '—'],
                ['Pendidikan Terakhir', $g?->pendidikan_terakhir ?? '—'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$lbl, $val]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div>
                <p class="text-[10px] text-slate-400 font-medium"><?php echo e($lbl); ?></p>
                <p class="text-xs text-slate-700 font-medium mt-0.5 truncate" title="<?php echo e($val); ?>"><?php echo e($val); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Data Kepegawaian</h3>
            <button onclick="bukaModal('modalKepegawaian')"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-600
                           text-xs font-semibold hover:bg-indigo-100 transition">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Edit
            </button>
        </div>
        <div class="grid grid-cols-2 gap-x-4 gap-y-3">
            <?php $__currentLoopData = [
                ['Status Pegawai', $g?->status_pegawai ?? '—'],
                ['Pangkat / Gol.', $g?->pangkat_gol_ruang ?? '—'],
                ['No. SK Pertama', $g?->no_sk_pertama ?? '—'],
                ['No. SK Terakhir', $g?->no_sk_terakhir ?? '—'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$lbl, $val]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div>
                <p class="text-[10px] text-slate-400 font-medium"><?php echo e($lbl); ?></p>
                <p class="text-xs text-slate-700 font-medium mt-0.5 truncate" title="<?php echo e($val); ?>"><?php echo e($val); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

</div>



<div id="modalAkun" onclick="if(event.target===this)tutupModal('modalAkun')"
     class="fixed inset-0 z-[999] hidden items-center justify-center p-4"
     style="background:rgba(0,0,0,.45);backdrop-filter:blur(4px)">
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-100 bg-slate-50">
            <h4 class="text-sm font-bold text-slate-700">Edit Profil Akun</h4>
            <button onclick="tutupModal('modalAkun')" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-slate-200 text-slate-400 hover:text-slate-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="<?php echo e(route('guru.profil.update')); ?>" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <input type="hidden" name="_section" value="akun">

            
            <div class="flex items-center gap-4">
                <div id="akun_photoDropZone"
                     class="shrink-0 w-16 h-[85px] border-2 border-dashed border-slate-300 rounded-xl
                            flex items-center justify-center overflow-hidden cursor-pointer
                            hover:border-indigo-400 transition-colors relative"
                     onclick="document.getElementById('akun_photoInput').click()">
                    <div id="akun_photoPreviewWrap" class="w-full h-full">
                        <?php if($user->photo): ?>
                            <img id="akun_photoPreview" src="<?php echo e(Storage::url($user->photo)); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-2xl">📸</div>
                        <?php endif; ?>
                    </div>
                    <input type="file" name="photo" id="akun_photoInput" accept="image/*" class="hidden">
                </div>
                <div class="text-xs text-slate-500 leading-relaxed">
                    Klik foto untuk ganti.<br>
                    Maks <strong>2 MB</strong> · JPG, PNG, WebP<br>
                    Rasio <strong>3:4</strong> disarankan
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Akun <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Email <span class="text-red-400">*</span></label>
                    <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Password Baru</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" autocomplete="new-password"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" autocomplete="new-password"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-1">
                <button type="button" onclick="tutupModal('modalAkun')"
                        class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600 text-xs font-semibold hover:bg-slate-50 transition">Batal</button>
                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>



<div id="modalIdentitas" onclick="if(event.target===this)tutupModal('modalIdentitas')"
     class="fixed inset-0 z-[999] hidden items-center justify-center p-4"
     style="background:rgba(0,0,0,.45);backdrop-filter:blur(4px)">
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-100 bg-slate-50">
            <h4 class="text-sm font-bold text-slate-700">Edit Identitas & Tugas</h4>
            <button onclick="tutupModal('modalIdentitas')" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-slate-200 text-slate-400 hover:text-slate-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="<?php echo e(route('guru.profil.update')); ?>" method="POST" class="p-5 space-y-3">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <input type="hidden" name="_section" value="identitas">

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">NIP</label>
                <input type="text" name="nip" value="<?php echo e(old('nip', $g?->nip)); ?>" maxlength="30"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Wali Kelas</label>
                <select name="wali_kelas"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="">— Tidak Menjadi Wali Kelas —</option>
                    <?php $__currentLoopData = $kelasList ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($kelas->id); ?>"
                            <?php echo e(old('wali_kelas', $g?->kelas_id) == $kelas->id ? 'selected' : ''); ?>>
                            <?php echo e($kelas->nama); ?> · <?php echo e($kelas->tingkat); ?> · <?php echo e($kelas->tahun_ajaran); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <p class="text-[10px] text-slate-400 mt-1">Hanya satu kelas yang diperbolehkan.</p>
            </div>

            <div class="flex justify-end gap-2 pt-1">
                <button type="button" onclick="tutupModal('modalIdentitas')"
                        class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600 text-xs font-semibold hover:bg-slate-50 transition">Batal</button>
                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>



<div id="modalPribadi" onclick="if(event.target===this)tutupModal('modalPribadi')"
     class="fixed inset-0 z-[999] hidden items-center justify-center p-4"
     style="background:rgba(0,0,0,.45);backdrop-filter:blur(4px)">
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-100 bg-slate-50">
            <h4 class="text-sm font-bold text-slate-700">Edit Data Pribadi</h4>
            <button onclick="tutupModal('modalPribadi')" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-slate-200 text-slate-400 hover:text-slate-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="<?php echo e(route('guru.profil.update')); ?>" method="POST" class="p-5 space-y-3">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <input type="hidden" name="_section" value="pribadi">

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Lengkap</label>
                <input type="text" name="nama" value="<?php echo e(old('nama', $g?->nama)); ?>"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis Kelamin</label>
                    <select name="jk" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">— Pilih —</option>
                        <option value="L" <?php echo e(old('jk', $g?->jk) === 'L' ? 'selected' : ''); ?>>Laki-laki</option>
                        <option value="P" <?php echo e(old('jk', $g?->jk) === 'P' ? 'selected' : ''); ?>>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="<?php echo e(old('tempat_lahir', $g?->tempat_lahir)); ?>"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="<?php echo e(old('tanggal_lahir', $g?->tanggal_lahir?->format('Y-m-d'))); ?>"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Pendidikan Terakhir</label>
                    <select name="pendidikan_terakhir" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">— Pilih —</option>
                        <?php $__currentLoopData = ['SMA/SMK','D1','D2','D3','D4','S1','S2','S3']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pend): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($pend); ?>" <?php echo e(old('pendidikan_terakhir', $g?->pendidikan_terakhir) === $pend ? 'selected' : ''); ?>><?php echo e($pend); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-1">
                <button type="button" onclick="tutupModal('modalPribadi')"
                        class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600 text-xs font-semibold hover:bg-slate-50 transition">Batal</button>
                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>



<div id="modalKepegawaian" onclick="if(event.target===this)tutupModal('modalKepegawaian')"
     class="fixed inset-0 z-[999] hidden items-center justify-center p-4"
     style="background:rgba(0,0,0,.45);backdrop-filter:blur(4px)">
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-100 bg-slate-50">
            <h4 class="text-sm font-bold text-slate-700">Edit Data Kepegawaian</h4>
            <button onclick="tutupModal('modalKepegawaian')" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-slate-200 text-slate-400 hover:text-slate-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="<?php echo e(route('guru.profil.update')); ?>" method="POST" class="p-5 space-y-3">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <input type="hidden" name="_section" value="kepegawaian">

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Status Pegawai</label>
                    <select name="status_pegawai" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">— Pilih —</option>
                        <?php $__currentLoopData = ['PNS','PPPK','Honorer','Kontrak','GTT','Lainnya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($sp); ?>" <?php echo e(old('status_pegawai', $g?->status_pegawai) === $sp ? 'selected' : ''); ?>><?php echo e($sp); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Pangkat / Gol. Ruang</label>
                    <input type="text" name="pangkat_gol_ruang" value="<?php echo e(old('pangkat_gol_ruang', $g?->pangkat_gol_ruang)); ?>"
                           placeholder="Contoh: Penata Muda / III-a" maxlength="100"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">No. SK Pertama</label>
                    <input type="text" name="no_sk_pertama" value="<?php echo e(old('no_sk_pertama', $g?->no_sk_pertama)); ?>"
                           maxlength="150"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">No. SK Terakhir</label>
                    <input type="text" name="no_sk_terakhir" value="<?php echo e(old('no_sk_terakhir', $g?->no_sk_terakhir)); ?>"
                           maxlength="150"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-1">
                <button type="button" onclick="tutupModal('modalKepegawaian')"
                        class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600 text-xs font-semibold hover:bg-slate-50 transition">Batal</button>
                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function bukaModal(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.remove('hidden');
    el.classList.add('flex');
    document.body.style.overflow = 'hidden';
}
function tutupModal(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.add('hidden');
    el.classList.remove('flex');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') {
    ['modalAkun','modalIdentitas','modalPribadi','modalKepegawaian'].forEach(tutupModal);
}});

// Preview foto di modal akun
(function() {
    const input = document.getElementById('akun_photoInput');
    const wrap  = document.getElementById('akun_photoPreviewWrap');
    if (!input || !wrap) return;
    input.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        if (!file.type.startsWith('image/')) { alert('Hanya file gambar.'); return; }
        if (file.size > 2 * 1024 * 1024) { alert('Maksimal 2 MB.'); return; }
        const reader = new FileReader();
        reader.onload = e => { wrap.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`; };
        reader.readAsDataURL(file);
    });
})();

// Auto-buka modal jika ada error validasi dari session
<?php if(session('_section')): ?>
    bukaModal('modal<?php echo e(ucfirst(session('_section'))); ?>');
<?php endif; ?>
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PA 3\smpn-kutime\resources\views/guru/profil.blade.php ENDPATH**/ ?>