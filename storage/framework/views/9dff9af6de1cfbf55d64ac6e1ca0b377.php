
<div id="modalTambahKelas"
    class="fixed inset-0 z-50 hidden items-center justify-center p-4"
    role="dialog" aria-modal="true">

    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
        onclick="closeModal('modalTambahKelas')"></div>

    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-lg
                max-h-[92vh] flex flex-col animate-modal">

        
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100
                    dark:border-slate-700 shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-indigo-100 dark:bg-indigo-900/40
                            flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9
                                 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1
                                 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100 leading-tight">
                        Tambah Kelas Baru
                    </h2>
                    <p class="text-[10px] text-slate-400 mt-0.5">
                        Tersinkron otomatis dengan Data Akademik
                    </p>
                </div>
            </div>
            <button onclick="closeModal('modalTambahKelas')"
                class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600
                           hover:bg-slate-100 dark:hover:bg-slate-700 transition shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        
        <div class="overflow-y-auto px-5 py-4 flex-1">
            <form id="formTambahKelas"
                action="<?php echo e(route('admin.kelas.store')); ?>"
                method="POST"
                class="space-y-3">
                <?php echo csrf_field(); ?>

                
                <div class="grid grid-cols-2 gap-3">
                    <div class="col-span-2 sm:col-span-1">
                        <label for="tambahKelasName"
                            class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Nama Kelas <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="tambahKelasName"
                            value="<?php echo e(old('name')); ?>" required
                            placeholder="mis. VII A"
                            class="w-full rounded-xl border px-3 py-2 text-xs transition
                                      focus:outline-none focus:ring-2 focus:ring-indigo-300
                                      dark:bg-slate-700 dark:text-slate-200
                                      <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php else: ?> border-slate-200 dark:border-slate-600 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-[10px] text-red-500 mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="tambahKelasGrade"
                            class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Tingkat <span class="text-red-500">*</span>
                        </label>
                        <select name="grade" id="tambahKelasGrade" required
                            class="w-full rounded-xl border px-3 py-2 text-xs transition
                                       focus:outline-none focus:ring-2 focus:ring-indigo-300
                                       bg-white dark:bg-slate-700 dark:text-slate-200
                                       <?php $__errorArgs = ['grade'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php else: ?> border-slate-200 dark:border-slate-600 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">— Pilih Tingkat —</option>
                            <option value="7" <?php echo e(old('grade') == '7' ? 'selected' : ''); ?>>VII (Kelas 7)</option>
                            <option value="8" <?php echo e(old('grade') == '8' ? 'selected' : ''); ?>>VIII (Kelas 8)</option>
                            <option value="9" <?php echo e(old('grade') == '9' ? 'selected' : ''); ?>>IX (Kelas 9)</option>
                        </select>
                        <?php $__errorArgs = ['grade'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-[10px] text-red-500 mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="tambahKelasAcademicYear"
                            class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Tahun Ajaran <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="academic_year" id="tambahKelasAcademicYear"
                            value="<?php echo e(old('academic_year', date('Y').'/'.((int)date('Y')+1))); ?>"
                            required placeholder="2025/2026" maxlength="9"
                            class="w-full rounded-xl border px-3 py-2 text-xs transition
                                      focus:outline-none focus:ring-2 focus:ring-indigo-300
                                      dark:bg-slate-700 dark:text-slate-200
                                      <?php $__errorArgs = ['academic_year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php else: ?> border-slate-200 dark:border-slate-600 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <p class="text-[9px] text-slate-400 mt-0.5">Format: YYYY/YYYY</p>
                        <?php $__errorArgs = ['academic_year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-[10px] text-red-500 mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="tambahKelasSemester"
                            class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Semester <span class="text-red-500">*</span>
                        </label>
                        <select name="semester" id="tambahKelasSemester" required
                            class="w-full rounded-xl border px-3 py-2 text-xs transition
                                       focus:outline-none focus:ring-2 focus:ring-indigo-300
                                       bg-white dark:bg-slate-700 dark:text-slate-200
                                       <?php $__errorArgs = ['semester'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php else: ?> border-slate-200 dark:border-slate-600 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">— Pilih —</option>
                            <option value="1" <?php echo e(old('semester') == '1' ? 'selected' : ''); ?>>Semester 1 (Ganjil)</option>
                            <option value="2" <?php echo e(old('semester') == '2' ? 'selected' : ''); ?>>Semester 2 (Genap)</option>
                        </select>
                        <?php $__errorArgs = ['semester'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-[10px] text-red-500 mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                
                <div>
                    <label for="tambahKelasHomeroomTeacher"
                        class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                  uppercase tracking-wide mb-1">
                        Wali Kelas
                        <span class="normal-case font-normal text-slate-400">(opsional)</span>
                    </label>
                    <select name="homeroom_teacher_id" id="tambahKelasHomeroomTeacher"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                   px-3 py-2 text-xs transition
                                   focus:outline-none focus:ring-2 focus:ring-indigo-300
                                   bg-white dark:bg-slate-700 dark:text-slate-200">
                        <option value="">— Pilih wali kelas —</option>
                        <?php $__currentLoopData = $gurus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $guru): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($guru->id); ?>"
                            <?php echo e(old('homeroom_teacher_id') == $guru->id ? 'selected' : ''); ?>>
                            <?php echo e($guru->name); ?>

                            <?php if($guru->guru && $guru->guru->nip): ?> — NIP: <?php echo e($guru->guru->nip); ?> <?php endif; ?>
                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['homeroom_teacher_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-[10px] text-red-500 mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <p class="text-[9px] text-slate-400 mt-0.5">
                        Memilih wali kelas akan otomatis memperbarui data guru tersebut.
                    </p>
                </div>

                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="tambahKelasSection"
                            class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Jurusan / Seksi
                            <span class="normal-case font-normal text-slate-400">(opsional)</span>
                        </label>
                        <input type="text" name="section" id="tambahKelasSection"
                            value="<?php echo e(old('section')); ?>"
                            placeholder="mis. A, IPA, IPS" maxlength="10"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2 text-xs transition
                                      focus:outline-none focus:ring-2 focus:ring-indigo-300
                                      dark:bg-slate-700 dark:text-slate-200">
                    </div>

                    <div>
                        <label for="tambahKelasRoom"
                            class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Ruang Kelas
                            <span class="normal-case font-normal text-slate-400">(opsional)</span>
                        </label>
                        <input type="text" name="room" id="tambahKelasRoom"
                            value="<?php echo e(old('room')); ?>"
                            placeholder="mis. Lab Komputer" maxlength="50"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2 text-xs transition
                                      focus:outline-none focus:ring-2 focus:ring-indigo-300
                                      dark:bg-slate-700 dark:text-slate-200">
                    </div>
                </div>

                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="tambahKelasCapacity"
                            class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Kapasitas Siswa
                        </label>
                        <input type="number" name="capacity" id="tambahKelasCapacity"
                            value="<?php echo e(old('capacity', 30)); ?>" min="1" max="60"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2 text-xs transition
                                      focus:outline-none focus:ring-2 focus:ring-indigo-300
                                      dark:bg-slate-700 dark:text-slate-200">
                    </div>

                    <div class="flex items-end pb-2">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <div class="relative inline-flex items-center">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" id="tambahKelasIsActive"
                                    value="1" <?php echo e(old('is_active', true) ? 'checked' : ''); ?>

                                    class="sr-only peer">
                                <div class="w-8 h-4 rounded-full bg-slate-200 dark:bg-slate-600
                                            peer-checked:bg-indigo-500 transition-colors cursor-pointer
                                            relative">
                                    <div class="absolute top-0.5 left-0.5 w-3 h-3 rounded-full
                                                bg-white shadow transition-transform
                                                peer-checked:translate-x-4"></div>
                                </div>
                            </div>
                            <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                                Kelas Aktif
                            </span>
                        </label>
                    </div>
                </div>

            </form>
        </div>

        
        <div class="flex gap-2 px-5 py-3.5 border-t border-slate-100 dark:border-slate-700
                    bg-slate-50/50 dark:bg-slate-900/20 rounded-b-2xl shrink-0">
            <button type="button" onclick="closeModal('modalTambahKelas')"
                class="flex-1 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                           text-slate-600 dark:text-slate-400 text-xs font-medium
                           hover:bg-white dark:hover:bg-slate-700 transition">
                Batal
            </button>
            <button type="submit" form="formTambahKelas"
                class="flex-1 px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-semibold
                           hover:bg-indigo-700 active:scale-95 transition">
                Simpan Kelas
            </button>
        </div>

    </div>
</div><?php /**PATH C:\PA 3\smpn-kutime\resources\views/admin/kelas/_modal_tambah.blade.php ENDPATH**/ ?>