

<?php $__env->startSection('title', 'Jadwal Kelas ' . $studyGroup->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">

    
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?php echo e(route('admin.academic-planner.index')); ?>">Academic Planner</a>
            </li>
            <li class="breadcrumb-item active">Kelas <?php echo e($studyGroup->name); ?></li>
        </ol>
    </nav>

    
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 bg-primary text-white d-flex align-items-center justify-content-center"
                    style="width:60px;height:60px;font-size:1.8rem;font-weight:900;">
                    <?php echo e($studyGroup->name); ?>

                </div>
                <div class="card-body">
                    <div>
                        <h5 class="card-title mb-1"><?php echo e($studyGroup->name); ?></h5>
                        <div class="text-muted small">
                            <?php echo e($studyGroup->academic_year); ?> &bull; Semester <?php echo e($studyGroup->semester); ?>

                            <?php if($studyGroup->room): ?> &bull; Ruang <?php echo e($studyGroup->room); ?> <?php endif; ?>
                        </div>
                        <div class="text-muted small">
                            Wali Kelas:
                            <strong><?php echo e($studyGroup->homeroomTeacher?->name ?? 'Belum ditetapkan'); ?></strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addJadwalModal">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Jadwal
                </button>
                <a href="#" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editKelasModal">
                    <i class="bi bi-pencil me-1"></i>Edit Kelas
                </a>
                <button type="button" class="btn btn-sm btn-outline-danger"
                    onclick="deleteKelas(<?php echo e($studyGroup->id); ?>, '<?php echo e($studyGroup->name); ?>')">
                    <i class="bi bi-trash me-1"></i>Hapus Kelas
                </button>
            </div>
        </div>
    </div>

    
    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    
    <div class="row g-3">
        <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-calendar-day me-2 text-primary"></i><?php echo e($day); ?>

                    </h6>
                    <span class="badge bg-primary bg-opacity-10 text-primary">
                        <?php echo e($timetableByDay[$day]->count()); ?> sesi
                    </span>
                </div>
                <div class="card-body p-0">
                    <?php $__empty_1 = true; $__currentLoopData = $timetableByDay[$day]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="timetable-item">
                        <div class="d-flex justify-content-between align-items-start">
                            
                            <div class="d-flex align-items-start gap-3">
                                
                                <div class="timetable-color-strip"
                                    style="background:<?php echo e($tt->studySubject->color ?? '#3B82F6'); ?>"></div>

                                
                                <div class="timetable-info">
                                    <div class="timetable-subject">
                                        <?php echo e($tt->studySubject->name); ?>

                                    </div>
                                    <div class="timetable-time">
                                        <i class="bi bi-clock me-1"></i>
                                        <?php echo e(substr($tt->start_time,0,5)); ?> – <?php echo e(substr($tt->end_time,0,5)); ?>

                                    </div>
                                    <div class="timetable-teacher">
                                        <i class="bi bi-person me-1"></i><?php echo e($tt->teacher->name); ?>

                                        <?php if($tt->room): ?>
                                        &bull; <i class="bi bi-geo-alt me-1"></i><?php echo e($tt->room); ?>

                                        <?php endif; ?>
                                        <?php if($tt->session_type): ?>
                                        &bull; <i class="bi bi-book me-1"></i><?php echo e(ucfirst($tt->session_type)); ?>

                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="timetable-actions">
                                <a href="#" class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal" data-bs-target="#showJadwalModal<?php echo e($tt->id); ?>">
                                    <i class="bi bi-eye"></i> Show
                                </a>
                                <a href="#" class="btn btn-sm btn-outline-warning"
                                    data-bs-toggle="modal" data-bs-target="#editJadwalModal<?php echo e($tt->id); ?>">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    onclick="deleteTimetable(<?php echo e($tt->id); ?>)">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-calendar-x d-block mb-1 opacity-50"></i>
                        <small>Tidak ada jadwal</small>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-white border-0">
                    <button type="button" class="btn btn-sm btn-light w-100 text-primary" data-bs-toggle="modal" data-bs-target="#addJadwalModal">
                        <i class="bi bi-plus me-1"></i>Tambah Jadwal <?php echo e($day); ?>

                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

</div>

<!-- Modal Edit Kelas -->
<div class="modal fade" id="editKelasModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Kelas - <?php echo e($studyGroup->name); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('admin.academic-planner.study-group.update', $studyGroup->id)); ?>" method="POST">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Kelas <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="<?php echo e(old('name', $studyGroup->name)); ?>"
                            placeholder="Contoh: VII-A" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            maxlength="50" required>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tingkatan <span class="text-danger">*</span></label>
                                <select name="grade" class="form-select <?php $__errorArgs = ['grade'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <option value="">-- Pilih Tingkatan --</option>
                                    <option value="7" <?php echo e(old('grade', $studyGroup->grade) == 7 ? 'selected' : ''); ?>>VII (Kelas 7)</option>
                                    <option value="8" <?php echo e(old('grade', $studyGroup->grade) == 8 ? 'selected' : ''); ?>>VIII (Kelas 8)</option>
                                    <option value="9" <?php echo e(old('grade', $studyGroup->grade) == 9 ? 'selected' : ''); ?>>IX (Kelas 9)</option>
                                </select>
                                <?php $__errorArgs = ['grade'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Jurusan</label>
                                <input type="text" name="section" value="<?php echo e(old('section', $studyGroup->section)); ?>"
                                    placeholder="Contoh: A, B, C" class="form-control <?php $__errorArgs = ['section'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    maxlength="10">
                                <?php $__errorArgs = ['section'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Wali Kelas</label>
                                <select name="homeroom_teacher_id" class="form-select <?php $__errorArgs = ['homeroom_teacher_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">-- Pilih Wali Kelas --</option>
                                    <?php $__currentLoopData = \App\Models\User::where('role', 'guru')->where('is_active', true)->orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($teacher->id); ?>" <?php echo e(old('homeroom_teacher_id', $studyGroup->homeroom_teacher_id) == $teacher->id ? 'selected' : ''); ?>>
                                        <?php echo e($teacher->name); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['homeroom_teacher_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Ruang Kelas</label>
                                <input type="text" name="room" value="<?php echo e(old('room', $studyGroup->room)); ?>"
                                    placeholder="Contoh: Lab Komputer" class="form-control <?php $__errorArgs = ['room'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    maxlength="50">
                                <?php $__errorArgs = ['room'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tahun Ajaran <span class="text-danger">*</span></label>
                        <input type="text" name="academic_year" value="<?php echo e(old('academic_year', $studyGroup->academic_year)); ?>"
                            placeholder="Contoh: 2024/2025" class="form-control <?php $__errorArgs = ['academic_year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            maxlength="9" pattern="[0-9]{4}/[0-9]{4}" required>
                        <?php $__errorArgs = ['academic_year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="form-text text-muted">Format: YYYY/YYYY (contoh: 2024/2025)</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Semester <span class="text-danger">*</span></label>
                                <select name="semester" class="form-select <?php $__errorArgs = ['semester'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <option value="">-- Pilih Semester --</option>
                                    <option value="1" <?php echo e(old('semester', $studyGroup->semester) == '1' ? 'selected' : ''); ?>>Semester 1 (Ganjil)</option>
                                    <option value="2" <?php echo e(old('semester', $studyGroup->semester) == '2' ? 'selected' : ''); ?>>Semester 2 (Genap)</option>
                                </select>
                                <?php $__errorArgs = ['semester'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="is_active_edit" name="is_active" value="1"
                                        <?php echo e(old('is_active', $studyGroup->is_active) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="is_active_edit">
                                        Aktif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-check-lg me-1"></i>Update Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Jadwal -->
<div class="modal fade" id="addJadwalModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Jadwal Pelajaran - Kelas <?php echo e($studyGroup->name); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('admin.academic-planner.timetables.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="study_group_id" value="<?php echo e($studyGroup->id); ?>">
                <input type="hidden" name="ajax_request" value="1">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Mata Pelajaran <span class="text-danger">*</span></label>
                                <select name="study_subject_id" class="form-select" required>
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    <?php
                                    $subjects = \App\Models\StudySubject::where('is_active', true)->orderBy('name')->get();
                                    ?>
                                    <?php if($subjects->count() > 0): ?>
                                    <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($subject->id); ?>"><?php echo e($subject->name); ?> (<?php echo e($subject->code); ?>)</option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                    <option value="">Tidak ada mata pelajaran tersedia</option>
                                    <?php endif; ?>
                                </select>
                                <?php if($subjects->count() == 0): ?>
                                <small class="text-danger">
                                    Tidak ada mata pelajaran aktif.
                                    <a href="<?php echo e(route('academic-planner.study-subjects.index')); ?>" class="text-decoration-none" target="_blank">
                                        Tambah mata pelajaran terlebih dahulu
                                    </a>
                                </small>
                                <?php endif; ?>
                            </div>

                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Guru <span class="text-danger">*</span></label>
                                <select name="teacher_id" class="form-select" required>
                                    <option value="">-- Pilih Guru --</option>
                                    <?php $__currentLoopData = \App\Models\User::where('role', 'guru')->where('is_active', true)->orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($teacher->id); ?>"><?php echo e($teacher->name); ?> (<?php echo e($teacher->username); ?>)</option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Hari <span class="text-danger">*</span></label>
                                <select name="day_of_week" class="form-select" required>
                                    <option value="">-- Pilih Hari --</option>
                                    <?php $__currentLoopData = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($day); ?>"><?php echo e($day); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Jam Mulai <span class="text-danger">*</span></label>
                                    <input type="time" name="start_time" value="07:00" class="form-control" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Jam Selesai <span class="text-danger">*</span></label>
                                    <input type="time" name="end_time" value="08:30" class="form-control" required>
                                </div>
                            </div>

                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Ruang</label>
                                <input type="text" name="room" placeholder="Contoh: Lab Fisika" class="form-control" maxlength="50">
                            </div>

                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Sesi <span class="text-danger">*</span></label>
                                <select name="session_type" class="form-select" required>
                                    <option value="">-- Pilih Sesi --</option>
                                    <option value="teori">Teori</option>
                                    <option value="praktikum">Praktikum</option>
                                </select>
                            </div>

                            
                            <div class="row mb-3">
                                <div class="col-7">
                                    <label class="form-label fw-semibold">Tahun Ajaran <span class="text-danger">*</span></label>
                                    <input type="text" name="academic_year" value="2025/2026" placeholder="2025/2026" class="form-control" maxlength="9" required>
                                </div>
                                <div class="col-5">
                                    <label class="form-label fw-semibold">Semester <span class="text-danger">*</span></label>
                                    <select name="semester" class="form-select" required>
                                        <option value="1">Semester 1</option>
                                        <option value="2">Semester 2</option>
                                    </select>
                                </div>
                            </div>

                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Catatan</label>
                                <textarea name="notes" rows="2" maxlength="500" class="form-control" placeholder="Catatan tambahan (opsional)"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Jadwal Dinamis -->
<?php $__currentLoopData = $timetables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timetable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="editJadwalModal<?php echo e($timetable->id); ?>" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Jadwal Pelajaran - Kelas <?php echo e($studyGroup->name); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('admin.academic-planner.timetables.update', $timetable->id)); ?>" method="POST" target="editFrame<?php echo e($timetable->id); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="study_group_id" value="<?php echo e($studyGroup->id); ?>">
                <input type="hidden" name="ajax_request" value="1">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Mata Pelajaran <span class="text-danger">*</span></label>
                                <select name="study_subject_id" class="form-select" required>
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    <?php
                                    $subjects = \App\Models\StudySubject::where('is_active', true)->orderBy('name')->get();
                                    // Debug: uncomment line below to see count
                                    // \Log::info('Total subjects found: ' . $subjects->count());
                                    ?>
                                    <?php if($subjects->count() > 0): ?>
                                    <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($subject->id); ?>" <?php echo e($timetable->study_subject_id == $subject->id ? 'selected' : ''); ?>>
                                        <?php echo e($subject->name); ?> (<?php echo e($subject->code); ?>)
                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                    <option value="">Tidak ada mata pelajaran tersedia</option>
                                    <?php endif; ?>
                                </select>
                                <?php if($subjects->count() == 0): ?>
                                <small class="text-danger">
                                    Tidak ada mata pelajaran aktif.
                                    <a href="<?php echo e(route('academic-planner.study-subjects.index')); ?>" class="text-decoration-none" target="_blank">
                                        Tambah mata pelajaran terlebih dahulu
                                    </a>
                                </small>
                                <?php endif; ?>
                            </div>

                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Guru <span class="text-danger">*</span></label>
                                <select name="teacher_id" class="form-select" required>
                                    <option value="">-- Pilih Guru --</option>
                                    <?php $__currentLoopData = \App\Models\User::whereIn('role', ['kepala_sekolah', 'guru'])->where('is_active', true)->orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($teacher->id); ?>" <?php echo e($timetable->teacher_id == $teacher->id ? 'selected' : ''); ?>>
                                        <?php echo e($teacher->name); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Hari <span class="text-danger">*</span></label>
                                <select name="day_of_week" class="form-select" required>
                                    <option value="">-- Pilih Hari --</option>
                                    <?php $__currentLoopData = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($day); ?>" <?php echo e($timetable->day_of_week == $day ? 'selected' : ''); ?>><?php echo e($day); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Jam Mulai <span class="text-danger">*</span></label>
                                    <input type="time" name="start_time" value="<?php echo e($timetable->start_time); ?>" class="form-control" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Jam Selesai <span class="text-danger">*</span></label>
                                    <input type="time" name="end_time" value="<?php echo e($timetable->end_time); ?>" class="form-control" required>
                                </div>
                            </div>

                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Ruang</label>
                                <input type="text" name="room" value="<?php echo e($timetable->room); ?>" placeholder="Contoh: Lab Fisika" class="form-control" maxlength="50">
                            </div>

                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Sesi <span class="text-danger">*</span></label>
                                <select name="session_type" class="form-select" required>
                                    <option value="">-- Pilih Sesi --</option>
                                    <option value="teori" <?php echo e($timetable->session_type == 'teori' ? 'selected' : ''); ?>>Teori</option>
                                    <option value="praktikum" <?php echo e($timetable->session_type == 'praktikum' ? 'selected' : ''); ?>>Praktikum</option>
                                </select>
                            </div>

                            
                            <div class="row mb-3">
                                <div class="col-7">
                                    <label class="form-label fw-semibold">Tahun Ajaran <span class="text-danger">*</span></label>
                                    <input type="text" name="academic_year" value="<?php echo e($timetable->academic_year ?? '2025/2026'); ?>" placeholder="2025/2026" class="form-control" maxlength="9" required>
                                </div>
                                <div class="col-5">
                                    <label class="form-label fw-semibold">Semester <span class="text-danger">*</span></label>
                                    <select name="semester" class="form-select" required>
                                        <option value="1" <?php echo e($timetable->semester == 1 ? 'selected' : ''); ?>>Semester 1</option>
                                        <option value="2" <?php echo e($timetable->semester == 2 ? 'selected' : ''); ?>>Semester 2</option>
                                    </select>
                                </div>
                            </div>

                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Catatan</label>
                                <textarea name="notes" rows="2" maxlength="500" class="form-control" placeholder="Catatan tambahan (opsional)"><?php echo e($timetable->notes ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning" style="pointer-events: auto !important; cursor: pointer !important;">
                        <i class="bi bi-check-lg me-1"></i>Update Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<!-- Modal Show Jadwal Dinamis -->
<?php $__currentLoopData = $timetables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timetable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="showJadwalModal<?php echo e($timetable->id); ?>" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Jadwal Pelajaran - Kelas <?php echo e($studyGroup->name); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="120"><strong>Mata Pelajaran:</strong></td>
                                <td><?php echo e($timetable->studySubject->name); ?> (<?php echo e($timetable->studySubject->code); ?>)</td>
                            </tr>
                            <tr>
                                <td><strong>Guru:</strong></td>
                                <td><?php echo e($timetable->teacher->name); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Hari:</strong></td>
                                <td><?php echo e($timetable->day_of_week); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Waktu:</strong></td>
                                <td><?php echo e(substr($timetable->start_time,0,5)); ?> - <?php echo e(substr($timetable->end_time,0,5)); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="120"><strong>Ruang:</strong></td>
                                <td><?php echo e($timetable->room ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Sesi:</strong></td>
                                <td>
                                    <span class="badge bg-<?php echo e($timetable->session_type == 'praktikum' ? 'success' : 'primary'); ?>">
                                        <?php echo e(ucfirst($timetable->session_type ?? 'Teori')); ?>

                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tahun Ajaran:</strong></td>
                                <td><?php echo e($timetable->academic_year); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Semester:</strong></td>
                                <td><?php echo e($timetable->semester); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <?php if($timetable->notes): ?>
                <div class="mt-3">
                    <strong>Catatan:</strong>
                    <p class="text-muted"><?php echo e($timetable->notes); ?></p>
                </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Custom timetable styling */
    .timetable-item {
        border-bottom: 1px solid #e9ecef;
        padding: 1rem;
        transition: all 0.2s ease;
    }

    .timetable-item:hover {
        background-color: #f8f9fa;
    }

    .timetable-color-strip {
        width: 6px;
        height: 45px;
        border-radius: 2px;
        flex-shrink: 0;
    }

    .timetable-info {
        flex-grow: 1;
        min-width: 0;
    }

    .timetable-subject {
        font-weight: 600;
        color: #212529;
        margin-bottom: 0.25rem;
        font-size: 0.95rem;
    }

    .timetable-time {
        color: #6c757d;
        font-size: 0.8rem;
        margin-bottom: 0.25rem;
    }

    .timetable-teacher {
        color: #6c757d;
        font-size: 0.8rem;
    }

    .timetable-actions {
        flex-shrink: 0;
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .timetable-actions .btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.8rem;
        border-radius: 0.375rem;
        min-width: 80px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.25rem;
        transition: all 0.2s ease;
    }

    .timetable-actions .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Modal overlay effects */
    .modal.show {
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        z-index: 1050;
    }

    .modal-backdrop.show {
        background-color: rgba(0, 0, 0, 0.7) !important;
        z-index: 1040;
    }

    .modal-content {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border: none;
        z-index: 1060;
    }

    .modal-dialog {
        animation: modalSlideIn 0.3s ease-out;
        z-index: 1055;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translate(0, -50px) scale(0.95);
        }

        to {
            opacity: 1;
            transform: translate(0, 0) scale(1);
        }
    }

    /* Ensure submit buttons are always clickable */
    .modal-footer button[type="submit"] {
        pointer-events: auto !important;
        cursor: pointer !important;
        position: relative !important;
        z-index: 1070 !important;
    }

    .modal-footer button[type="submit"]:disabled {
        cursor: not-allowed !important;
        opacity: 0.65 !important;
    }

    /* Remove any overlay that might block clicks */
    .modal * {
        pointer-events: auto;
    }

    .modal-backdrop {
        pointer-events: none;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Refresh halaman setelah submit modal
    document.addEventListener('DOMContentLoaded', function() {
        console.log('JavaScript loaded');

        // Listen for postMessage from iframe
        window.addEventListener('message', function(event) {
            console.log('PostMessage received:', event.data);

            if (event.data.success) {
                // Find and close the modal
                const modals = document.querySelectorAll('.modal.show');
                modals.forEach(modal => {
                    const bootstrapModal = bootstrap.Modal.getInstance(modal);
                    if (bootstrapModal) {
                        bootstrapModal.hide();
                    }
                });

                // Show success message
                showAlert(event.data.message || 'Jadwal berhasil diperbarui.', 'success');

                // Refresh page after 1 second
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else if (event.data.error) {
                showAlert(event.data.message || 'Terjadi kesalahan.', 'danger');
            }
        });

        // Modal tambah jadwal
        const addForm = document.querySelector('#addJadwalModal form');
        if (addForm) {
            console.log('Add form found');
            addForm.addEventListener('submit', function(e) {
                console.log('Add form submit');
                e.preventDefault();
                submitFormAjax(this, 'addJadwalModal');
            });
        }

        // Modal edit jadwal (dinamis) - menggunakan iframe
        <?php $__currentLoopData = $timetables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timetable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        const editForm {
            {
                $timetable - > id
            }
        } = document.querySelector('#editJadwalModal<?php echo e($timetable->id); ?> form');
        const editFrame {
            {
                $timetable - > id
            }
        } = document.querySelector('#editFrame<?php echo e($timetable->id); ?>');

        if (editForm {
                {
                    $timetable - > id
                }
            } && editFrame {
                {
                    $timetable - > id
                }
            }) {
            console.log('Edit form <?php echo e($timetable->id); ?> found with iframe');

            // Simple form submit without complex event handling
            editForm {
                {
                    $timetable - > id
                }
            }.addEventListener('submit', function(e) {
                console.log('Form <?php echo e($timetable->id); ?> submitting...');

                // Show loading state
                const submitBtn = editForm {
                    {
                        $timetable - > id
                    }
                }.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Menyimpan...';
                }

                // Simple iframe monitoring
                setTimeout(function() {
                    try {
                        const iframeContent = editFrame {
                            {
                                $timetable - > id
                            }
                        }.contentDocument.body.innerText;
                        console.log('Iframe content:', iframeContent);

                        if (iframeContent.includes('berhasil') || iframeContent.includes('success')) {
                            showAlert('Jadwal berhasil diperbarui!', 'success');
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            showAlert('Terjadi kesalahan. Periksa kembali data.', 'danger');
                            if (submitBtn) {
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Update Jadwal';
                            }
                        }
                    } catch (e) {
                        console.error('Error:', e);
                        showAlert('Terjadi kesalahan teknis.', 'danger');
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Update Jadwal';
                        }
                    }
                }, 2000); // Wait 2 seconds for response
            });
        } else {
            console.error('Edit form <?php echo e($timetable->id); ?> or iframe not found');
        }
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    });

    function submitFormAjax(form, modalId) {
        console.log('submitFormAjax called for', modalId);

        // Client-side validation
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
                console.log('Field validation failed:', field.name);
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            showAlert('Harap lengkapi semua field yang wajib diisi.', 'danger');
            return;
        }

        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        console.log('Submit button:', submitBtn);
        console.log('Form action:', form.action);
        console.log('Form method:', form.method);

        // Log form data
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }

        // Show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Menyimpan...';
        console.log('Button disabled and loading shown');

        // Try fetch with fallback
        fetch(form.action, {
                method: form.method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                console.log('Response received:', response.status, response.statusText);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Data received:', data);
                if (data.success) {
                    // Close modal
                    const modalElement = document.getElementById(modalId);
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) {
                        modal.hide();
                        console.log('Modal closed');
                    }

                    // Show success message
                    showAlert(data.message, 'success');
                    console.log('Success alert shown');

                    // Refresh page after 1 second
                    setTimeout(() => {
                        console.log('Refreshing page');
                        location.reload();
                    }, 1000);
                } else {
                    // Show errors
                    if (data.errors) {
                        let errorHtml = '<ul class="mb-0 ps-3">';
                        for (const [field, message] of Object.entries(data.errors)) {
                            errorHtml += `<li>${message}</li>`;
                        }
                        errorHtml += '</ul>';
                        showAlert(errorHtml, 'danger');
                    } else {
                        showAlert(data.message, 'danger');
                    }
                    console.log('Error alert shown');
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);

                // Fallback: try normal form submission
                console.log('Trying fallback submission...');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;

                // Remove event listener and submit normally
                form.removeEventListener('submit', arguments.callee);
                form.submit();
            })
            .finally(() => {
                // Only reset button if not using fallback
                if (submitBtn.disabled) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    console.log('Button reset');
                }
            });
    }

    function showAlert(message, type) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        // Insert alert at the top of the content
        const container = document.querySelector('.container');
        container.insertAdjacentHTML('afterbegin', alertHtml);

        // Auto remove after 5 seconds
        setTimeout(() => {
            const alert = container.querySelector('.alert');
            if (alert) {
                alert.remove();
            }
        }, 5000);
    }

    // Function untuk delete timetable
    function deleteTimetable(id) {
        if (confirm('Hapus jadwal ini?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/academic-planner/timetables/${id}`;

            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
            }

            // Add method override
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            // Submit form
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Function untuk delete kelas
    function deleteKelas(id, name) {
        if (confirm(`Hapus kelas ${name}? Semua jadwalnya akan ikut terhapus.`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/academic-planner/${id}/delete-study-group`;

            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
            }

            // Add method override
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            // Submit form
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
<?php $__env->stopPush(); ?>

<!-- Hidden iframes for form submission -->
<?php $__currentLoopData = $timetables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timetable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<iframe id="editFrame<?php echo e($timetable->id); ?>" name="editFrame<?php echo e($timetable->id); ?>" style="display:none;"></iframe>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PA 3\smpn-kutime\resources\views/admin/academic-planner/show-study-group.blade.php ENDPATH**/ ?>