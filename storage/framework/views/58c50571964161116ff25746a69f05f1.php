<?php $__env->startSection('title', 'Absensi Siswa'); ?>

<?php $__env->startPush('styles'); ?>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
* { box-sizing: border-box; }
body { background: #f1f5fb; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; color: #1e293b; }

.as-topbar { display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:10px; }
.as-title { font-size:16px;font-weight:800;color:#0f172a;display:flex;align-items:center;gap:9px;margin:0;letter-spacing:-.3px; }
.as-title i { color:#6366f1;font-size:17px; }
.as-date-pill { background:#e0e7ff;color:#4338ca;border-radius:20px;padding:4px 12px;font-size:11px;font-weight:700;display:flex;align-items:center;gap:5px; }

.as-filter-card { background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:14px 16px;margin-bottom:14px;box-shadow:0 1px 4px rgba(0,0,0,.04);display:flex;align-items:flex-end;gap:10px;flex-wrap:wrap; }
.as-filter-group { display:flex;flex-direction:column;gap:4px; }
.as-filter-group label { font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em; }
.as-filter-group select,
.as-filter-group input[type="date"] { border:1.5px solid #e2e8f0;border-radius:8px;padding:7px 10px;font-size:12px;font-family:inherit;color:#1e293b;background:#f8fafc;outline:none;transition:border .15s;min-width:160px; }
.as-filter-group select:focus,
.as-filter-group input:focus { border-color:#6366f1;background:#fff; }
.as-btn-load { background:#6366f1;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:12px;font-weight:700;font-family:inherit;cursor:pointer;display:flex;align-items:center;gap:6px;transition:background .15s;white-space:nowrap; }
.as-btn-load:hover { background:#4f46e5; }
.as-btn-rekap { background:#f1f5f9;color:#475569;border:1.5px solid #e2e8f0;border-radius:8px;padding:8px 14px;font-size:12px;font-weight:600;font-family:inherit;cursor:pointer;display:flex;align-items:center;gap:6px;text-decoration:none;transition:background .15s; }
.as-btn-rekap:hover { background:#e2e8f0; }

.as-stats { display:flex;gap:8px;margin-bottom:14px;flex-wrap:wrap; }
.as-stat { background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:10px 14px;flex:1;min-width:80px;display:flex;align-items:center;gap:10px; }
.as-stat-icon { width:34px;height:34px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0; }
.as-stat-val { font-size:18px;font-weight:800;line-height:1;font-family:'JetBrains Mono',monospace; }
.as-stat-lbl { font-size:10px;color:#94a3b8;font-weight:600;margin-top:1px; }

.as-card { background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,.05); }
.as-card-head { padding:12px 16px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;background:#fafbff; }
.as-card-title { font-size:13px;font-weight:700;color:#1e293b;display:flex;align-items:center;gap:7px; }
.as-card-title .kelas-chip { background:#e0e7ff;color:#4338ca;border-radius:6px;padding:2px 8px;font-size:11px;font-weight:700; }

.as-bulk-actions { display:flex;align-items:center;gap:6px;flex-wrap:wrap; }
.as-bulk-btn { border:1.5px solid;border-radius:7px;padding:5px 10px;font-size:11px;font-weight:700;cursor:pointer;font-family:inherit;display:flex;align-items:center;gap:4px;transition:all .15s;background:transparent; }
.as-bulk-hadir { border-color:#16a34a;color:#16a34a; } .as-bulk-hadir:hover { background:#16a34a;color:#fff; }
.as-bulk-alpha { border-color:#dc2626;color:#dc2626; } .as-bulk-alpha:hover { background:#dc2626;color:#fff; }

.as-siswa-list { padding:6px 0; }
.as-siswa-row { display:flex;align-items:center;gap:12px;padding:10px 16px;transition:background .1s;border-bottom:1px solid #f8fafc; }
.as-siswa-row:last-child { border-bottom:none; }
.as-siswa-row:hover { background:#fafbff; }

.as-no { width:26px;height:26px;border-radius:6px;background:#f1f5f9;color:#94a3b8;font-size:10px;font-weight:700;text-align:center;line-height:26px;flex-shrink:0; }
.as-av { width:36px;height:36px;border-radius:9px;background:#e0e7ff;color:#6366f1;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;flex-shrink:0; }
.as-av img { width:36px;height:36px;border-radius:9px;object-fit:cover; }
.as-info { flex:1;min-width:0; }
.as-nama { font-size:13.5px;font-weight:700;color:#1e293b;line-height:1.3;white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }

.as-status-group { display:flex;gap:5px;flex-shrink:0; }
.as-st-btn { width:54px;height:32px;border-radius:7px;border:1.5px solid;font-size:10.5px;font-weight:700;cursor:pointer;font-family:inherit;transition:all .15s;background:transparent;display:flex;align-items:center;justify-content:center; }
.as-st-btn[data-s="hadir"] { border-color:#bbf7d0;color:#15803d; }
.as-st-btn[data-s="hadir"]:hover,.as-st-btn[data-s="hadir"].active { background:#16a34a;border-color:#16a34a;color:#fff; }
.as-st-btn[data-s="sakit"] { border-color:#fde68a;color:#b45309; }
.as-st-btn[data-s="sakit"]:hover,.as-st-btn[data-s="sakit"].active { background:#d97706;border-color:#d97706;color:#fff; }
.as-st-btn[data-s="izin"]  { border-color:#bae6fd;color:#0369a1; }
.as-st-btn[data-s="izin"]:hover,.as-st-btn[data-s="izin"].active { background:#0284c7;border-color:#0284c7;color:#fff; }
.as-st-btn[data-s="alpha"] { border-color:#fecaca;color:#b91c1c; }
.as-st-btn[data-s="alpha"]:hover,.as-st-btn[data-s="alpha"].active { background:#dc2626;border-color:#dc2626;color:#fff; }

.as-ket { width:140px;border:1.5px solid #e2e8f0;border-radius:7px;padding:6px 10px;font-size:11.5px;font-family:inherit;color:#475569;background:#f8fafc;outline:none;transition:border .15s;flex-shrink:0; }
.as-ket:focus { border-color:#6366f1;background:#fff; }

.as-save-bar { position:sticky;bottom:0;left:0;right:0;background:#fff;border-top:1.5px solid #e0e7ff;padding:12px 16px;display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;box-shadow:0 -4px 20px rgba(99,102,241,.08);z-index:10; }
.as-save-info { font-size:12px;color:#64748b; }
.as-save-info strong { color:#1e293b; }
.as-btn-save { background:#6366f1;color:#fff;border:none;border-radius:9px;padding:10px 24px;font-size:13px;font-weight:700;font-family:inherit;cursor:pointer;display:flex;align-items:center;gap:7px;transition:background .15s;box-shadow:0 4px 14px rgba(99,102,241,.3); }
.as-btn-save:hover { background:#4f46e5; }
.as-btn-save:disabled { background:#a5b4fc;cursor:not-allowed;box-shadow:none; }

.as-saved-badge { background:#dcfce7;color:#15803d;border-radius:7px;padding:4px 10px;font-size:11px;font-weight:700;display:inline-flex;align-items:center;gap:4px; }

.as-empty { padding:60px 20px;text-align:center;color:#94a3b8; }
.as-empty i { font-size:40px;display:block;margin-bottom:12px;color:#cbd5e1; }
.as-empty h3 { font-size:14px;font-weight:700;color:#64748b;margin:0 0 6px; }
.as-empty p  { font-size:12px;margin:0;line-height:1.6; }

.as-toast { position:fixed;bottom:80px;right:20px;z-index:9999;background:#0f172a;color:#fff;border-radius:10px;padding:12px 18px;font-size:12px;font-weight:600;display:flex;align-items:center;gap:9px;box-shadow:0 8px 30px rgba(0,0,0,.18);transform:translateY(60px);opacity:0;transition:all .3s cubic-bezier(.34,1.56,.64,1);pointer-events:none;max-width:320px; }
.as-toast.show { transform:translateY(0);opacity:1; }
.as-toast i { font-size:15px;flex-shrink:0; }
.as-toast.success i { color:#4ade80; }
.as-toast.error   i { color:#f87171; }

.as-progress-wrap { padding:8px 16px;background:#fafbff;border-bottom:1px solid #f1f5f9; }
.as-progress-bar { height:5px;border-radius:10px;background:#e2e8f0;overflow:hidden; }
.as-progress-fill { height:100%;border-radius:10px;background:linear-gradient(90deg,#6366f1,#818cf8);transition:width .3s ease; }
.as-progress-label { font-size:10px;color:#94a3b8;margin-top:4px;font-weight:600; }

.as-alert-error { background:#fef2f2;border:1px solid #fecaca;border-radius:9px;padding:10px 14px;margin-bottom:12px;font-size:12px;color:#b91c1c;display:flex;align-items:flex-start;gap:8px; }
.as-alert-error i { flex-shrink:0;margin-top:1px; }

@media (max-width:640px) {
    .as-ket { display:none; }
    .as-st-btn { width:48px;font-size:9.5px; }
    .as-filter-card { gap:8px; }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $kelasList     = $kelasList     ?? collect();
    $kelasId       = $kelasId       ?? null;
    $tanggal       = $tanggal       ?? date('Y-m-d');
    $hariIni       = $hariIni       ?? '';
    $siswaList     = $siswaList     ?? collect();
    $absensiHari   = $absensiHari   ?? collect();
    $sudahDisimpan = $sudahDisimpan ?? false;
    $ringkasan     = $ringkasan     ?? [];

    $selectedKelas = $kelasList->firstWhere('id', $kelasId);
?>

<div class="container-fluid px-0">

<div class="as-topbar">
    <h5 class="as-title">
        <i class="bi bi-clipboard2-pulse-fill"></i>
        Absensi Siswa
    </h5>
    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
        <div class="as-date-pill">
            <i class="bi bi-calendar3"></i>
            <?php echo e($hariIni ?: \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM Y')); ?>

        </div>
        <a href="<?php echo e(route('guru.absensi-siswa.rekap')); ?>" class="as-btn-rekap">
            <i class="bi bi-bar-chart-line-fill"></i> Rekap Bulanan
        </a>
    </div>
</div>

<?php if(session('success')): ?>
    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:9px;padding:9px 14px;margin-bottom:12px;font-size:12px;color:#15803d;display:flex;align-items:center;gap:7px;">
        <i class="bi bi-check-circle-fill"></i><?php echo e(session('success')); ?>

    </div>
<?php endif; ?>

<div id="errorContainer" style="display:none;" class="as-alert-error">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <div id="errorMessage"></div>
</div>

<form method="GET" action="<?php echo e(route('guru.absensi-siswa.index')); ?>" class="as-filter-card" id="filterForm">
    <div class="as-filter-group">
        <label><i class="bi bi-calendar3 me-1"></i>Tanggal Absensi</label>
        <input type="date" name="tanggal" value="<?php echo e($tanggal); ?>"
               max="<?php echo e(date('Y-m-d')); ?>" onchange="this.form.submit()">
    </div>

    <div class="as-filter-group">
        <label><i class="bi bi-mortarboard me-1"></i>Pilih Kelas</label>
        <select name="kelas_id" onchange="this.form.submit()">
            <option value="">— Pilih Kelas —</option>
            <?php $__currentLoopData = $kelasList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($kelas->id); ?>" <?php echo e($kelasId == $kelas->id ? 'selected' : ''); ?>>
                    <?php echo e($kelas->nama); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <button type="submit" class="as-btn-load">
        <i class="bi bi-arrow-clockwise"></i> Muat Siswa
    </button>
</form>

<?php if($kelasId && $siswaList->count()): ?>
    <div class="as-stats">
        <div class="as-stat">
            <div class="as-stat-icon" style="background:#ede9fe;">
                <i class="bi bi-people-fill" style="color:#7c3aed;"></i>
            </div>
            <div>
                <div class="as-stat-val" style="color:#7c3aed;"><?php echo e($siswaList->count()); ?></div>
                <div class="as-stat-lbl">Total Siswa</div>
            </div>
        </div>
        <div class="as-stat">
            <div class="as-stat-icon" style="background:#dcfce7;">
                <i class="bi bi-check2-circle" style="color:#16a34a;"></i>
            </div>
            <div>
                <div class="as-stat-val" style="color:#16a34a;"><?php echo e($ringkasan['hadir'] ?? 0); ?></div>
                <div class="as-stat-lbl">Hadir Bulan Ini</div>
            </div>
        </div>
        <div class="as-stat">
            <div class="as-stat-icon" style="background:#fef9c3;">
                <i class="bi bi-thermometer-half" style="color:#ca8a04;"></i>
            </div>
            <div>
                <div class="as-stat-val" style="color:#ca8a04;"><?php echo e($ringkasan['sakit'] ?? 0); ?></div>
                <div class="as-stat-lbl">Sakit Bulan Ini</div>
            </div>
        </div>
        <div class="as-stat">
            <div class="as-stat-icon" style="background:#e0f2fe;">
                <i class="bi bi-envelope-check" style="color:#0284c7;"></i>
            </div>
            <div>
                <div class="as-stat-val" style="color:#0284c7;"><?php echo e($ringkasan['izin'] ?? 0); ?></div>
                <div class="as-stat-lbl">Izin Bulan Ini</div>
            </div>
        </div>
        <div class="as-stat">
            <div class="as-stat-icon" style="background:#fee2e2;">
                <i class="bi bi-x-circle" style="color:#dc2626;"></i>
            </div>
            <div>
                <div class="as-stat-val" style="color:#dc2626;"><?php echo e($ringkasan['alpha'] ?? 0); ?></div>
                <div class="as-stat-lbl">Alpha Bulan Ini</div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="as-card">

    <div class="as-card-head">
        <div class="as-card-title">
            <i class="bi bi-list-check" style="color:#6366f1;"></i>
            Daftar Siswa
            <?php if($selectedKelas): ?>
                <span class="kelas-chip"><?php echo e($selectedKelas->nama); ?></span>
            <?php endif; ?>
            <?php if($sudahDisimpan): ?>
                <span class="as-saved-badge">
                    <i class="bi bi-check-circle-fill"></i> Sudah Dicatat
                </span>
            <?php endif; ?>
        </div>

        <?php if($siswaList->count() > 0): ?>
            <div class="as-bulk-actions">
                <span style="font-size:11px;color:#94a3b8;font-weight:600;">Tandai Semua:</span>
                <button type="button" class="as-bulk-btn as-bulk-hadir" onclick="tandaiSemua('hadir')">
                    <i class="bi bi-check2-all"></i> Hadir
                </button>
                <button type="button" class="as-bulk-btn as-bulk-alpha" onclick="tandaiSemua('alpha')">
                    <i class="bi bi-x-lg"></i> Alpha
                </button>
            </div>
        <?php endif; ?>
    </div>

    <?php if($siswaList->count() > 0): ?>
        <?php $pct = $siswaList->count() > 0 ? round($absensiHari->count() / $siswaList->count() * 100) : 0; ?>
        <div class="as-progress-wrap">
            <div class="as-progress-bar">
                <div class="as-progress-fill" id="progressFill" style="width:<?php echo e($pct); ?>%;"></div>
            </div>
            <div class="as-progress-label" id="progressLabel">
                <?php echo e($absensiHari->count()); ?> / <?php echo e($siswaList->count()); ?> siswa sudah diisi (<?php echo e($pct); ?>%)
            </div>
        </div>
    <?php endif; ?>

    <?php if(!$kelasId): ?>
        <div class="as-empty">
            <i class="bi bi-mortarboard"></i>
            <h3>Pilih Kelas Terlebih Dahulu</h3>
            <p>Pilih kelas dan tanggal di atas untuk mulai mengisi absensi siswa.</p>
        </div>

    <?php elseif($siswaList->count() === 0): ?>
        <div class="as-empty">
            <i class="bi bi-person-x"></i>
            <h3>Belum Ada Siswa</h3>
            <p>Kelas ini belum memiliki siswa yang terdaftar.</p>
        </div>

    <?php else: ?>
        <div class="as-siswa-list">
            <?php $__currentLoopData = $siswaList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $existing    = $absensiHari->get($siswa->id);
                    $statusSaved = $existing ? $existing->status : null;
                    $ketSaved    = $existing ? ($existing->keterangan ?? '') : '';
                    $namaTampil  = $siswa->nama ?: ($siswa->user?->name ?? '—');
                    $fotoUrl     = ($siswa->user && $siswa->user->photo)
                                   ? Storage::url($siswa->user->photo) : null;
                    $inisial     = strtoupper(mb_substr($namaTampil, 0, 1));
                ?>

                <div class="as-siswa-row" id="row-<?php echo e($siswa->id); ?>">
                    <span class="as-no"><?php echo e($i + 1); ?></span>

                    <div class="as-av">
                        <?php if($fotoUrl): ?>
                            <img src="<?php echo e($fotoUrl); ?>" alt="">
                        <?php else: ?>
                            <?php echo e($inisial); ?>

                        <?php endif; ?>
                    </div>

                    <div class="as-info">
                        <div class="as-nama"><?php echo e($namaTampil); ?></div>
                    </div>

                    <div class="as-status-group" data-siswa-id="<?php echo e($siswa->id); ?>">
                        <?php $__currentLoopData = ['hadir'=>'Hadir','sakit'=>'Sakit','izin'=>'Izin','alpha'=>'Alpha']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button type="button"
                                class="as-st-btn <?php echo e($statusSaved === $s ? 'active' : ''); ?>"
                                data-s="<?php echo e($s); ?>"
                                data-siswa="<?php echo e($siswa->id); ?>"
                                onclick="pilihStatus(this, <?php echo e($siswa->id); ?>, '<?php echo e($s); ?>')">
                                <?php echo e($label); ?>

                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <input type="text"
                           class="as-ket"
                           id="ket-<?php echo e($siswa->id); ?>"
                           placeholder="Keterangan..."
                           value="<?php echo e($ketSaved); ?>"
                           maxlength="100">
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="as-save-bar">
            <div class="as-save-info">
                <span>
                    <strong id="filledCount"><?php echo e($absensiHari->count()); ?></strong>
                    dari <strong><?php echo e($siswaList->count()); ?></strong> siswa sudah diisi
                </span>
            </div>
            <div style="display:flex;gap:8px;align-items:center;">
                <?php if($sudahDisimpan): ?>
                    <span style="font-size:11px;color:#16a34a;font-weight:600;">
                        <i class="bi bi-clock-history"></i>
                        Terakhir: <?php echo e($absensiHari->first()?->updated_at?->format('H:i') ?? ''); ?>

                    </span>
                <?php endif; ?>
                <button type="button" class="as-btn-save" id="saveBtn" onclick="simpanAbsensi()">
                    <i class="bi bi-cloud-upload-fill"></i>
                    <?php echo e($sudahDisimpan ? 'Perbarui Absensi' : 'Simpan Absensi'); ?>

                </button>
            </div>
        </div>
    <?php endif; ?>

</div>
</div>

<div class="as-toast" id="asToast"><i id="asTi"></i><span id="asTm"></span></div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// (script tetap sama seperti sebelumnya, tidak ada perubahan di sini)
const AS_STATUS = {};
const KELAS_ID  = <?php echo e($kelasId ? (int)$kelasId : 'null'); ?>;
const TANGGAL   = "<?php echo e($tanggal); ?>";
const TOTAL     = <?php echo e($siswaList->count()); ?>;

const STORE_URL = "<?php echo e(route('guru.absensi-siswa.store')); ?>";
const CSRF      = () => document.querySelector('meta[name="csrf-token"]').content;

<?php $__currentLoopData = $absensiHari; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sid => $abs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    AS_STATUS[<?php echo e((int)$sid); ?>] = "<?php echo e($abs->status); ?>";
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

function pilihStatus(btn, siswaId, status) {
    const group = btn.closest('.as-status-group');
    group.querySelectorAll('.as-st-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    AS_STATUS[siswaId] = status;
    updateProgress();

    const row = document.getElementById(`row-${siswaId}`);
    if (row) {
        const colors = {hadir:'#f0fdf4',sakit:'#fefce8',izin:'#f0f9ff',alpha:'#fef2f2'};
        row.style.background = colors[status] || '#fafbff';
        setTimeout(() => { row.style.background = ''; }, 600);
    }
}

function tandaiSemua(status) {
    document.querySelectorAll('.as-status-group').forEach(group => {
        const sid = parseInt(group.dataset.siswaId);
        group.querySelectorAll('.as-st-btn').forEach(b => {
            b.classList.toggle('active', b.dataset.s === status);
        });
        AS_STATUS[sid] = status;
    });
    updateProgress();
    asToast(`Semua siswa ditandai: ${status}`, 'success');
}

function updateProgress() {
    const filled = Object.keys(AS_STATUS).length;
    const pct    = TOTAL > 0 ? Math.round(filled / TOTAL * 100) : 0;
    const fill   = document.getElementById('progressFill');
    const lbl    = document.getElementById('progressLabel');
    const cnt    = document.getElementById('filledCount');
    if (fill) fill.style.width = pct + '%';
    if (lbl)  lbl.textContent  = `${filled} / ${TOTAL} siswa sudah diisi (${pct}%)`;
    if (cnt)  cnt.textContent  = filled;
}

async function simpanAbsensi() {
    const errBox = document.getElementById('errorContainer');
    if (errBox) errBox.style.display = 'none';

    const filled = Object.keys(AS_STATUS).length;
    if (filled === 0) {
        asToast('Belum ada status yang dipilih', 'error');
        return;
    }
    if (filled < TOTAL) {
        const ok = confirm(`Baru ${filled} dari ${TOTAL} siswa yang diisi. Lanjutkan simpan?`);
        if (!ok) return;
    }

    const btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> <span style="opacity:.7">Menyimpan…</span>';

    const absensiArr = [];
    document.querySelectorAll('.as-status-group').forEach(group => {
        const sid    = parseInt(group.dataset.siswaId);
        const status = AS_STATUS[sid];
        if (!status) return;
        const ket = document.getElementById(`ket-${sid}`)?.value ?? '';
        absensiArr.push({ siswa_id: sid, status, keterangan: ket });
    });

    const payload = {
        kelas_id : KELAS_ID,
        tanggal  : TANGGAL,
        absensi  : absensiArr,
    };

    try {
        const res = await fetch(STORE_URL, {
            method  : 'POST',
            headers : {
                'Content-Type' : 'application/json',
                'X-CSRF-TOKEN' : CSRF(),
                'Accept'       : 'application/json',
            },
            body: JSON.stringify(payload),
        });

        let data;
        try {
            data = await res.json();
        } catch {
            const text = await res.text();
            showError('Server error: ' + text.substring(0, 200));
            resetBtn();
            return;
        }

        if (res.ok && data.success) {
            asToast(`✓ ${data.total} absensi berhasil disimpan`, 'success');
            btn.innerHTML = '<i class="bi bi-cloud-check-fill"></i> Tersimpan';
            btn.style.background = '#16a34a';
            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-cloud-upload-fill"></i> Perbarui Absensi';
                btn.style.background = '';
            }, 3000);
        } else {
            let msg = data.message || 'Gagal menyimpan';
            if (data.errors) {
                msg = Object.values(data.errors).flat().join('<br>');
            }
            showError(msg);
            asToast('Gagal menyimpan, lihat keterangan error', 'error');
            resetBtn();
        }

    } catch (e) {
        showError('Gagal terhubung ke server: ' + e.message);
        asToast('Gagal terhubung ke server', 'error');
        resetBtn();
    }
}

function showError(msg) {
    const box = document.getElementById('errorContainer');
    const txt = document.getElementById('errorMessage');
    if (box && txt) {
        txt.innerHTML = msg;
        box.style.display = 'flex';
        box.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}

function resetBtn() {
    const btn = document.getElementById('saveBtn');
    btn.disabled = false;
    btn.style.background = '';
    btn.innerHTML = '<i class="bi bi-cloud-upload-fill"></i> Simpan Absensi';
}

let _tt;
function asToast(msg, type = 'success') {
    clearTimeout(_tt);
    const el = document.getElementById('asToast');
    document.getElementById('asTi').className =
        type === 'success' ? 'bi bi-check-circle-fill' : 'bi bi-exclamation-circle-fill';
    document.getElementById('asTm').textContent = msg;
    el.className = `as-toast ${type} show`;
    _tt = setTimeout(() => { el.className = `as-toast ${type}`; }, 3500);
}

document.addEventListener('DOMContentLoaded', updateProgress);
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PA 3\smpn-kutime\resources\views/guru/absensi-siswa/index.blade.php ENDPATH**/ ?>