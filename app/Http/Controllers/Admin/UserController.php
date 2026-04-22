<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\StudyGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    // =========================================================================
    // INDEX — tampilkan daftar user
    // =========================================================================

    public function index(Request $request): View
    {
        $activeTab = $request->get('tab', 'guru');

        $gurus = User::whereIn('role', ['guru', 'kepala_sekolah'])
            ->with(['guru.studyGroup', 'guru.kelas'])
            ->latest()
            ->get();

        $siswas = User::where('role', 'siswa')
            ->with(['siswa.kelas', 'siswa.studyGroup'])
            ->latest()
            ->get();

        $kelasList    = $this->getKelasList();
        $semesterList = $this->getSemesterList();
        $tahunList    = $this->getTahunAjaranList();

        return view('admin.users.index', compact(
            'gurus', 'siswas', 'activeTab',
            'kelasList', 'semesterList', 'tahunList'
        ));
    }

    // =========================================================================
    // SHOW — data satu user (JSON, untuk modal detail)
    // =========================================================================

    public function show(User $user)
    {
        $user->load(['guru.studyGroup', 'guru.kelas', 'siswa.kelas', 'siswa.studyGroup']);

        $role    = $user->role;
        $profile = null;

        if ($role === 'siswa' && $user->siswa) {
            $profile = $user->siswa->toArray();

            // Ambil info kelas lengkap dari study_groups agar ada semester, room, dsb
            $sg = StudyGroup::find($user->siswa->kelas_id);
            if ($sg) {
                $profile['kelas_info'] = [
                    'id'           => $sg->id,
                    'name'         => $sg->name,
                    'grade'        => $sg->grade,
                    'section'      => $sg->section,
                    'room'         => $sg->room,
                    'academic_year'=> $user->siswa->kelas?->tahun_ajaran ?? null,
                ];
            } elseif ($user->siswa->kelas) {
                $profile['kelas_info'] = [
                    'id'           => $user->siswa->kelas->id,
                    'name'         => $user->siswa->kelas->nama,
                    'grade'        => $user->siswa->kelas->tingkat,
                    'section'      => $user->siswa->kelas->rombel,
                    'academic_year'=> $user->siswa->kelas->tahun_ajaran,
                ];
            }
        } elseif (in_array($role, ['guru', 'kepala_sekolah']) && $user->guru) {
            $profile = $user->guru->toArray();
            $kelas   = $user->guru->studyGroup ?? $user->guru->kelas;
            $profile['kelas'] = $kelas ? $kelas->toArray() : null;
        }

        return response()->json([
            'user'    => $user->only(['id', 'name', 'email', 'photo', 'created_at']),
            'profile' => $profile,
            'role'    => $role,
        ]);
    }

    // =========================================================================
    // STORE — tambah user manual
    // =========================================================================

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role'     => 'required|in:guru,siswa,admin,kepala_sekolah',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'role'     => $request->role,
                'password' => Hash::make($request->password),
            ]);

            if ($request->role === 'guru' || $request->role === 'kepala_sekolah') {
                $user->guru()->create([
                    'nama'     => $request->name,
                    'kelas_id' => null,
                ]);
            } elseif ($request->role === 'siswa') {
                // Resolusi kelas_id — harus merujuk ke tabel `kelas` (FK constraint)
                $kelasId = $this->resolveKelasIdForSiswa(
                    $request->kelas_id,
                    $request->tahun_ajaran ?? null,
                    $request->semester     ?? null
                );

                $user->siswa()->create([
                    'nama'     => $request->name,
                    'kelas_id' => $kelasId,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat user: ' . $e->getMessage())->withInput();
        }

        return redirect()
            ->route('admin.users.index', ['tab' => $user->role === 'kepala_sekolah' ? 'guru' : $user->role])
            ->with('success', 'User berhasil ditambahkan.');
    }

    // =========================================================================
    // EDIT — data user untuk modal edit (JSON)
    // =========================================================================

    public function edit(User $user)
    {
        $user->load(['guru.studyGroup', 'guru.kelas', 'siswa.kelas', 'siswa.studyGroup']);

        $kelasList    = $this->getKelasList();
        $semesterList = $this->getSemesterList();
        $tahunList    = $this->getTahunAjaranList();
        $profile      = $user->role === 'siswa' ? $user->siswa : $user->guru;

        // Tambahkan info semester & tahun_ajaran dari tabel kelas ke profile siswa
        if ($user->role === 'siswa' && $user->siswa && $user->siswa->kelas) {
            $kelasData = $user->siswa->kelas->toArray();
            if ($profile) {
                $profile = $profile->toArray();
                $profile['tahun_ajaran_kelas'] = $kelasData['tahun_ajaran'] ?? null;
                $profile['ruang_kelas']        = $user->siswa->studyGroup?->room ?? null;
            }
        }

        return response()->json([
            'user'         => $user,
            'profile'      => $profile,
            'role'         => $user->role,
            'kelasList'    => $kelasList,
            'semesterList' => $semesterList,
            'tahunList'    => $tahunList,
        ]);
    }

    // =========================================================================
    // UPDATE — simpan perubahan data user
    // =========================================================================

    public function update(Request $request, User $user): RedirectResponse
    {
        $validationRules = [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ];

        if ($user->role === 'siswa') {
            $validationRules['kelas_id'] = ['nullable'];
        }

        $request->validate($validationRules);

        DB::beginTransaction();
        try {
            $user->update([
                'name'  => $request->name,
                'email' => $request->email,
            ]);

            if ($user->role === 'guru' || $user->role === 'kepala_sekolah') {
                $this->updateGuruProfile($user, $request);
            } elseif ($user->role === 'siswa') {
                $this->updateSiswaProfile($user, $request);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            $errorMessage = 'Gagal memperbarui user: ' . $e->getMessage();
            if (str_contains($e->getMessage(), '1452') || str_contains($e->getMessage(), 'foreign key')) {
                $errorMessage = 'Gagal memperbarui data siswa. Kelas yang dipilih tidak valid. Silakan hubungi administrator.';
            }

            return back()->with('error', $errorMessage)->withInput();
        }

        $tab = in_array($user->role, ['guru', 'kepala_sekolah']) ? 'guru' : $user->role;

        return redirect()
            ->route('admin.users.index', ['tab' => $tab])
            ->with('success', 'Data user berhasil diperbarui.');
    }

    // =========================================================================
    // UPDATE PROFIL GURU & KEPALA SEKOLAH
    // =========================================================================

    private function updateGuruProfile(User $user, Request $request): void
    {
        $data = [
            'nama'                => $request->name,
            'nip'                 => $request->nip,
            'jk'                  => $request->jk,
            'tempat_lahir'        => $request->tempat_lahir,
            'tanggal_lahir'       => $request->tanggal_lahir ?: null,
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'status_pegawai'      => $request->status_pegawai,
            'pangkat_gol_ruang'   => $request->pangkat_gol_ruang,
            'no_sk_pertama'       => $request->no_sk_pertama,
            'no_sk_terakhir'      => $request->no_sk_terakhir,
            'kelas_id'            => $request->filled('kelas_id') ? $request->kelas_id : null,
        ];

        if ($user->guru) {
            $user->guru()->update($data);
        } else {
            $user->guru()->create($data);
        }
    }

    // =========================================================================
    // UPDATE PROFIL SISWA
    // Sekarang mendukung field: semester, tahun_ajaran, ruang dari form
    // =========================================================================

    private function updateSiswaProfile(User $user, Request $request): void
    {
        $kpsRaw = strtolower(trim((string) ($request->penerima_kps ?? '')));
        $isKps  = in_array($kpsRaw, ['ya', 'yes', '1', 'y', 'true'], true) ? 'Ya' : 'Tidak';

        // Resolusi kelas_id dengan mempertimbangkan semester & tahun_ajaran dari form
        $kelasId = $this->resolveKelasIdForSiswa(
            $request->kelas_id,
            $request->tahun_ajaran ?? null,
            $request->semester     ?? null
        );

        $data = [
            'nama'               => $request->name,
            'nidn'               => $request->nidn,
            'nik'                => $request->nik,
            'jk'                 => $request->jk,
            'tempat_lahir'       => $request->tempat_lahir,
            'tgl_lahir'          => $request->tgl_lahir ?: null,
            'agama'              => $request->agama,
            'no_telp'            => $request->no_telp,
            'shkun'              => $request->shkun,
            'kelas_id'           => $kelasId,
            'alamat'             => $request->alamat,
            'rt'                 => $request->rt,
            'rw'                 => $request->rw,
            'dusun'              => $request->dusun,
            'kecamatan'          => $request->kecamatan,
            'kode_pos'           => $request->kode_pos,
            'jenis_tinggal'      => $request->jenis_tinggal,
            'jalan_transportasi' => $request->jalan_transportasi,
            'penerima_kps'       => $isKps,
            'no_kps'             => $request->no_kps,
        ];

        if ($user->siswa) {
            $user->siswa()->update($data);
        } else {
            $user->siswa()->create($data);
        }

        // Jika semester/tahun_ajaran dikirim dari form, update di tabel kelas juga
        if ($kelasId && ($request->filled('tahun_ajaran') || $request->filled('semester'))) {
            $this->updateKelasMetadata(
                $kelasId,
                $request->tahun_ajaran ?? null,
                $request->semester     ?? null
            );
        }
    }

    // =========================================================================
    // HELPER: UPDATE METADATA KELAS (tahun_ajaran, semester) DI TABEL KELAS
    // Dipanggil saat form siswa mengirim data semester/tahun_ajaran
    // =========================================================================

    private function updateKelasMetadata(int $kelasId, ?string $tahunAjaran, ?string $semester): void
    {
        $updateData = [];

        if ($tahunAjaran) {
            $updateData['tahun_ajaran'] = $tahunAjaran;
        }

        // Jika tabel kelas punya kolom semester, update juga
        // Cek dulu apakah kolom ada agar tidak error
        try {
            if ($semester && \Schema::hasColumn('kelas', 'semester')) {
                $updateData['semester'] = $semester;
            }
        } catch (\Exception $e) {
            // Kolom semester tidak ada di tabel kelas, skip
        }

        if (!empty($updateData)) {
            Kelas::where('id', $kelasId)->update($updateData);
        }
    }

    // =========================================================================
    // RESET PASSWORD
    // =========================================================================

    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('admin.users.index', ['tab' => in_array($user->role, ['guru', 'kepala_sekolah']) ? 'guru' : $user->role])
            ->with('success', 'Password berhasil direset.');
    }

    // =========================================================================
    // HAPUS USER
    // =========================================================================

    public function destroy(User $user): RedirectResponse
    {
        $role = $user->role;
        $tab  = in_array($role, ['guru', 'kepala_sekolah']) ? 'guru' : $role;

        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index', ['tab' => $tab])
            ->with('success', 'User berhasil dihapus permanen.');
    }

    // =========================================================================
    // DOWNLOAD TEMPLATE IMPORT EXCEL
    // =========================================================================

    public function downloadTemplate(Request $request)
    {
        $role = $request->query('role')
            ?? ($request->has('guru')  ? 'guru'  : null)
            ?? ($request->has('siswa') ? 'siswa' : null);

        abort_unless(in_array($role, ['guru', 'siswa'], true), 404);

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        $config = $role === 'guru'
            ? $this->getGuruTemplateConfig()
            : $this->getSiswaTemplateConfig();

        $this->setupTemplateSheet($sheet, $config);

        $writer   = new Xlsx($spreadsheet);
        $filename = $config['filename'];

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control'       => 'max-age=0',
        ]);
    }

    // =========================================================================
    // KONFIGURASI TEMPLATE GURU
    // =========================================================================

    private function getGuruTemplateConfig(): array
    {
        return [
            'title'    => 'Import Guru',
            'filename' => 'template_import_guru.xlsx',
            'headers'  => [
                'nama', 'email', 'password', 'nip', 'jenis_kelamin (L/P)',
                'tempat_lahir', 'tanggal_lahir (dd/mm/yyyy)', 'pendidikan_terakhir',
                'status_pegawai', 'pangkat_gol_ruang', 'no_sk_pertama',
                'no_sk_terakhir', 'nama_kelas',
            ],
            'contohData' => [
                'Budi Santoso, S.Pd', 'budi@guru.sch.id', 'password123',
                '198501012010011001', 'L', 'Jakarta', '01/01/1985',
                'S1 Pendidikan Matematika', 'PNS', 'Penata Muda / III-a',
                '001/SK/2010', '002/SK/2023', '7A',
            ],
        ];
    }

    // =========================================================================
    // KONFIGURASI TEMPLATE SISWA
    // =========================================================================

    private function getSiswaTemplateConfig(): array
    {
        return [
            'title'    => 'Import Siswa',
            'filename' => 'template_import_siswa.xlsx',
            'headers'  => [
                'nama', 'email', 'password', 'nis_nipd', 'nik',
                'jenis_kelamin (L/P)', 'tempat_lahir', 'tanggal_lahir (dd/mm/yyyy)',
                'agama', 'no_telp', 'shkun', 'nama_kelas',
                'tahun_ajaran (contoh: 2025/2026)', 'semester (1/2)',
                'alamat', 'rt', 'rw', 'dusun', 'kecamatan', 'kode_pos',
                'jenis_tinggal', 'transportasi', 'penerima_kps (Ya/Tidak)', 'no_kps',
            ],
            'contohData' => [
                'Ani Rahayu', 'ani@siswa.sch.id', 'password123', '20240001',
                '3201010101010001', 'P', 'Bogor', '01/01/2010', 'Islam',
                '08123456789', '', '7A', '2025/2026', '1',
                'Jl. Merdeka No. 1', '001', '002', 'Cikaret', 'Cibinong',
                '16913', 'Bersama Orang Tua', 'Jalan kaki', 'Tidak', '',
            ],
        ];
    }

    // =========================================================================
    // SETUP SHEET DENGAN STYLING
    // =========================================================================

    private function setupTemplateSheet($sheet, array $config): void
    {
        $sheet->setTitle($config['title']);
        $headers    = $config['headers'];
        $contohData = $config['contohData'];
        $colCount   = count($headers);
        $lastCol    = Coordinate::stringFromColumnIndex($colCount);

        $sheet->fromArray($headers, null, 'A1');
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '3730A3']]],
        ]);

        $sheet->fromArray($contohData, null, 'A2');
        $sheet->getStyle("A2:{$lastCol}2")->applyFromArray([
            'font'      => ['italic' => true, 'color' => ['rgb' => '6B7280']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F3F4F6']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']]],
        ]);

        $sheet->setCellValue('A3', '← Data contoh di baris 2. Isi data mulai baris 3 ini. Hapus baris contoh sebelum import.');
        $sheet->mergeCells("A3:{$lastCol}3");
        $sheet->getStyle("A3:{$lastCol}3")->applyFromArray([
            'font'      => ['italic' => true, 'color' => ['rgb' => 'EF4444'], 'bold' => true],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEF2F2']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'FCA5A5']]],
        ]);

        foreach (range(1, $colCount) as $colIndex) {
            $sheet->getColumnDimensionByColumn($colIndex)->setAutoSize(true);
        }

        $sheet->getRowDimension(1)->setRowHeight(28);
        $sheet->getRowDimension(2)->setRowHeight(22);
        $sheet->getRowDimension(3)->setRowHeight(20);
        $sheet->freezePane('A3');
    }

    // =========================================================================
    // IMPORT EXCEL
    // =========================================================================

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'role'            => 'required|in:guru,siswa',
            'password_import' => 'required|string|min:5',
            'import_file'     => 'required|file|mimes:xlsx,xls',
        ]);

        $role         = $request->role;
        $passwordHash = Hash::make($request->password_import);
        $file         = $request->file('import_file');

        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet       = $spreadsheet->getActiveSheet();
            $rows        = $sheet->toArray(null, true, true, false);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
        }

        $headerRowIndex = null;
        foreach ($rows as $index => $row) {
            $cellA = strtolower(trim((string) ($row[0] ?? '')));
            if ($cellA === 'nama' || str_starts_with($cellA, 'nama')) {
                $headerRowIndex = $index;
                break;
            }
        }

        if ($headerRowIndex === null) {
            return back()->with('error', 'Format file tidak dikenali. Pastikan baris pertama berisi header "nama".');
        }

        $emailContohMarkers = ['@guru.sch.id', '@siswa.sch.id', '@sekolah.sch.id', 'contoh', 'example'];
        $dataRows = array_filter(
            array_slice($rows, $headerRowIndex + 1),
            function ($row) use ($emailContohMarkers) {
                $nama  = trim((string) ($row[0] ?? ''));
                $email = strtolower(trim((string) ($row[1] ?? '')));
                if (empty($nama) || empty($email)) return false;
                foreach ($emailContohMarkers as $marker) {
                    if (str_contains($email, $marker)) return false;
                }
                return true;
            }
        );

        if (empty($dataRows)) {
            return back()->with('error', 'Tidak ada data yang ditemukan. Isi data mulai baris ketiga.');
        }

        $importedCount = 0;
        $errors        = [];

        DB::beginTransaction();
        try {
            foreach ($dataRows as $rowIndex => $row) {
                $namaRaw  = trim((string) ($row[0] ?? ''));
                $emailRaw = trim((string) ($row[1] ?? ''));

                if (empty($namaRaw) || empty($emailRaw)) continue;

                if (!filter_var($emailRaw, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Baris " . ($rowIndex + 1) . ": Email \"{$emailRaw}\" tidak valid.";
                    continue;
                }

                if (User::where('email', $emailRaw)->exists()) {
                    $errors[] = "Baris " . ($rowIndex + 1) . ": Email {$emailRaw} sudah terdaftar.";
                    continue;
                }

                $user = User::create([
                    'name'     => $namaRaw,
                    'email'    => $emailRaw,
                    'password' => $passwordHash,
                    'role'     => $role,
                ]);

                if ($role === 'guru') {
                    $this->storeGuruProfile($user, $row);
                } else {
                    $this->storeSiswaProfile($user, $row);
                }

                $importedCount++;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Kesalahan sistem saat menyimpan data: ' . $e->getMessage());
        }

        if ($importedCount === 0) {
            return back()
                ->with('error', 'Tidak ada data yang berhasil diimpor.')
                ->with('import_errors', $errors);
        }

        return redirect()
            ->route('admin.users.index', ['tab' => $role])
            ->with('success', "Berhasil mengimpor {$importedCount} akun {$role}.")
            ->with('import_errors', $errors);
    }

    // =========================================================================
    // HELPER: SIMPAN PROFIL GURU (dari import)
    // Kolom: 0:nama 1:email 2:pwd 3:nip 4:jk 5:tempat 6:tgl
    //        7:pendidikan 8:status 9:pangkat 10:sk1 11:sk2 12:kelas
    // =========================================================================

    private function storeGuruProfile(User $user, array $row): void
    {
        $kelasId = $this->findKelasId($row[12] ?? null);

        $user->guru()->create([
            'nama'                => $user->name,
            'nip'                 => $this->cleanString($row[3]  ?? null),
            'jk'                  => $this->parseJk($row[4]      ?? null),
            'tempat_lahir'        => $this->cleanString($row[5]  ?? null),
            'tanggal_lahir'       => $this->parseDate($row[6]    ?? null),
            'pendidikan_terakhir' => $this->cleanString($row[7]  ?? null),
            'status_pegawai'      => $this->cleanString($row[8]  ?? null),
            'pangkat_gol_ruang'   => $this->cleanString($row[9]  ?? null),
            'no_sk_pertama'       => $this->cleanString($row[10] ?? null),
            'no_sk_terakhir'      => $this->cleanString($row[11] ?? null),
            'kelas_id'            => $kelasId,
        ]);
    }

    // =========================================================================
    // HELPER: SIMPAN PROFIL SISWA (dari import)
    // Kolom: 0:nama 1:email 2:pwd 3:nis 4:nik 5:jk 6:tempat 7:tgl
    //        8:agama 9:telp 10:shkun 11:kelas 12:tahun_ajaran 13:semester
    //        14:alamat 15:rt 16:rw 17:dusun 18:kecamatan 19:kodepos
    //        20:tinggal 21:transport 22:kps 23:nokps
    // =========================================================================

    private function storeSiswaProfile(User $user, array $row): void
    {
        $namaKelas   = $this->cleanString($row[11] ?? null);
        $tahunAjaran = $this->cleanString($row[12] ?? null);
        $semester    = $this->cleanString($row[13] ?? null);

        $kelasId = $this->findKelasId($namaKelas, $tahunAjaran, $semester);

        $kpsRaw = strtolower(trim((string) ($row[22] ?? '')));
        $isKps  = in_array($kpsRaw, ['ya', 'yes', '1', 'y', 'true'], true) ? 'Ya' : 'Tidak';

        $user->siswa()->create([
            'nama'               => $user->name,
            'nidn'               => $this->cleanString($row[3]  ?? null),
            'nik'                => $this->cleanString($row[4]  ?? null),
            'jk'                 => $this->parseJk($row[5]      ?? null),
            'tempat_lahir'       => $this->cleanString($row[6]  ?? null),
            'tgl_lahir'          => $this->parseDate($row[7]    ?? null),
            'agama'              => $this->cleanString($row[8]  ?? null),
            'no_telp'            => $this->cleanString($row[9]  ?? null),
            'shkun'              => $this->cleanString($row[10] ?? null),
            'kelas_id'           => $kelasId,
            'alamat'             => $this->cleanString($row[14] ?? null),
            'rt'                 => $this->cleanString($row[15] ?? null),
            'rw'                 => $this->cleanString($row[16] ?? null),
            'dusun'              => $this->cleanString($row[17] ?? null),
            'kecamatan'          => $this->cleanString($row[18] ?? null),
            'kode_pos'           => $this->cleanString($row[19] ?? null),
            'jenis_tinggal'      => $this->cleanString($row[20] ?? null),
            'jalan_transportasi' => $this->cleanString($row[21] ?? null),
            'penerima_kps'       => $isKps,
            'no_kps'             => $this->cleanString($row[23] ?? null),
        ]);
    }

    // =========================================================================
    // EXPORT EXCEL
    // =========================================================================

    public function exportExcel(Request $request)
    {
        $role = $request->query('role', 'guru');
        abort_unless(in_array($role, ['guru', 'siswa'], true), 404);

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle(ucfirst($role));

        if ($role === 'guru') {
            $filename = 'data_guru_' . date('Ymd_His') . '.xlsx';
            $headers  = [
                'No', 'Nama', 'Email', 'NIP', 'Jenis Kelamin',
                'Tempat Lahir', 'Tanggal Lahir', 'Pendidikan Terakhir',
                'Status Pegawai', 'Pangkat/Golongan', 'No SK Pertama',
                'No SK Terakhir', 'Wali Kelas',
            ];

            $data = User::where('role', 'guru')->with('guru.kelas')->latest()->get();
            $rows = [];

            foreach ($data as $i => $user) {
                $g      = $user->guru;
                $rows[] = [
                    $i + 1,
                    $g?->nama              ?? $user->name,
                    $user->email,
                    $g?->nip               ?? '-',
                    $g?->jk                ?? '-',
                    $g?->tempat_lahir      ?? '-',
                    $g?->tanggal_lahir ? $g->tanggal_lahir->format('d/m/Y') : '-',
                    $g?->pendidikan_terakhir ?? '-',
                    $g?->status_pegawai    ?? '-',
                    $g?->pangkat_gol_ruang ?? '-',
                    $g?->no_sk_pertama     ?? '-',
                    $g?->no_sk_terakhir    ?? '-',
                    $g?->kelas?->nama      ?? '-',
                ];
            }
        } else {
            $filename = 'data_siswa_' . date('Ymd_His') . '.xlsx';
            $headers  = [
                'No', 'Nama', 'Email', 'NIS/NIPD', 'NIK',
                'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir',
                'Agama', 'No Telp', 'SKHUN', 'Kelas',
                'Tahun Ajaran', 'Semester',
                'Alamat', 'RT', 'RW', 'Dusun', 'Kecamatan', 'Kode Pos',
                'Jenis Tinggal', 'Transportasi', 'Penerima KPS', 'No KPS',
            ];

            $data = User::where('role', 'siswa')->with('siswa.kelas')->latest()->get();
            $rows = [];

            foreach ($data as $i => $user) {
                $s      = $user->siswa;
                $rows[] = [
                    $i + 1,
                    $s?->nama               ?? $user->name,
                    $user->email,
                    $s?->nidn               ?? '-',
                    $s?->nik                ?? '-',
                    $s?->jk                 ?? '-',
                    $s?->tempat_lahir       ?? '-',
                    $s?->tgl_lahir ? $s->tgl_lahir->format('d/m/Y') : '-',
                    $s?->agama              ?? '-',
                    $s?->no_telp            ?? '-',
                    $s?->shkun              ?? '-',
                    $s?->kelas?->nama       ?? '-',
                    $s?->kelas?->tahun_ajaran ?? '-',
                    '-', // semester — tambahkan kolom jika ada di tabel kelas
                    $s?->alamat             ?? '-',
                    $s?->rt                 ?? '-',
                    $s?->rw                 ?? '-',
                    $s?->dusun              ?? '-',
                    $s?->kecamatan          ?? '-',
                    $s?->kode_pos           ?? '-',
                    $s?->jenis_tinggal      ?? '-',
                    $s?->jalan_transportasi ?? '-',
                    $s?->penerima_kps       ?? '-',
                    $s?->no_kps             ?? '-',
                ];
            }
        }

        $sheet->fromArray($headers, null, 'A1');
        if (!empty($rows)) {
            $sheet->fromArray($rows, null, 'A2');
        }

        $totalCols   = count($headers);
        $lastCol     = Coordinate::stringFromColumnIndex($totalCols);
        $lastDataRow = count($rows) + 1;

        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(26);

        if ($lastDataRow > 1) {
            for ($row = 2; $row <= $lastDataRow; $row++) {
                $bgColor = ($row % 2 === 0) ? 'F8F9FA' : 'FFFFFF';
                $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]],
                ]);
            }

            $sheet->getStyle("A1:{$lastCol}{$lastDataRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']],
                ],
            ]);
        }

        foreach (range(1, $totalCols) as $col) {
            $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
        }

        $sheet->freezePane('A2');
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(
            fn () => $writer->save('php://output'),
            $filename,
            [
                'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                'Cache-Control'       => 'max-age=0',
            ]
        );
    }

    // =========================================================================
    // EXPORT PDF
    // =========================================================================

    public function exportPdf(Request $request)
    {
        $role = $request->query('role', 'guru');
        abort_unless(in_array($role, ['guru', 'siswa'], true), 404);

        if ($role === 'guru') {
            $users    = User::where('role', 'guru')->with('guru.kelas')->latest()->get();
            $filename = 'data_guru_' . date('Ymd_His') . '.pdf';
            $view     = 'admin.users.exports.pdf_guru';
        } else {
            $users    = User::where('role', 'siswa')->with('siswa.kelas')->latest()->get();
            $filename = 'data_siswa_' . date('Ymd_His') . '.pdf';
            $view     = 'admin.users.exports.pdf_siswa';
        }

        $pdf = Pdf::loadView($view, compact('users', 'role'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont'          => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => false,
            ]);

        return $pdf->download($filename);
    }

    // =========================================================================
    // HELPER UTAMA: RESOLVE KELAS_ID UNTUK SISWA
    //
    // Masalah inti: siswas.kelas_id → FK ke tabel `kelas` (migration lama),
    // tapi UI mengirim ID dari `study_groups`.
    //
    // Solusi bertahap:
    // 1. ID langsung ada di tabel `kelas` → pakai
    // 2. ID ada di `study_groups` → cari/buat di tabel kelas
    // =========================================================================

    private function resolveKelasIdForSiswa(
        mixed $inputId,
        ?string $tahunAjaran = null,
        ?string $semester    = null
    ): ?int {
        if (empty($inputId) || $inputId === 'null' || $inputId === '') {
            return null;
        }

        $id = (int) $inputId;

        // 1. Cek apakah ID sudah ada di tabel `kelas`
        $existingKelas = Kelas::find($id);
        if ($existingKelas) {
            // Update tahun_ajaran jika dikirim dari form
            if ($tahunAjaran) {
                $existingKelas->update(['tahun_ajaran' => $tahunAjaran]);
            }
            return $id;
        }

        // 2. Cek di study_groups
        $studyGroup = StudyGroup::find($id);

        if (!$studyGroup) {
            Log::warning("resolveKelasIdForSiswa: ID {$id} tidak ditemukan di kelas maupun study_groups.");
            return null;
        }

        // 3. Coba cocokkan ke tabel kelas berdasarkan nama
        $kelasName = $studyGroup->name ?: ($studyGroup->grade . ($studyGroup->section ?? ''));
        $kelas     = Kelas::whereRaw('LOWER(TRIM(nama)) = ?', [strtolower(trim($kelasName))])->first();

        if ($kelas) {
            if ($tahunAjaran) {
                $kelas->update(['tahun_ajaran' => $tahunAjaran]);
            }
            return $kelas->id;
        }

        // 4. Buat entri baru di tabel kelas dengan ID yang sama agar FK konsisten
        $defaultTahun = $tahunAjaran ?? $this->getDefaultAcademicYear();

        $kelas = Kelas::create([
            'id'           => $studyGroup->id,
            'nama'         => $kelasName,
            'tingkat'      => (string) $studyGroup->grade,
            'rombel'       => $studyGroup->section ?? null,
            'tahun_ajaran' => $defaultTahun,
            'guru_id'      => $studyGroup->homeroom_teacher_id ?? null,
        ]);

        Log::info("resolveKelasIdForSiswa: Kelas baru id={$kelas->id} nama={$kelas->nama} dibuat dari study_group id={$studyGroup->id}");

        return $kelas->id;
    }

    // =========================================================================
    // HELPER: CARI KELAS BERDASARKAN NAMA (untuk import Excel)
    // =========================================================================

    private function findKelasId(
        ?string $namaKelas,
        ?string $tahunAjaran = null,
        ?string $semester    = null
    ): ?int {
        if (empty(trim((string) $namaKelas))) return null;

        $nama = strtolower(trim($namaKelas));

        // Cari di tabel kelas dulu
        $query = Kelas::whereRaw('LOWER(TRIM(nama)) = ?', [$nama]);
        if ($tahunAjaran) {
            $query->where('tahun_ajaran', $tahunAjaran);
        }
        $kelas = $query->first();

        if (!$kelas) {
            // Coba tanpa filter tahun_ajaran
            $kelas = Kelas::whereRaw('LOWER(TRIM(nama)) = ?', [$nama])->first();
        }

        if ($kelas) {
            // Update tahun_ajaran jika dikirim
            if ($tahunAjaran) {
                $kelas->update(['tahun_ajaran' => $tahunAjaran]);
            }
            return $kelas->id;
        }

        // Fallback: cari di study_groups
        $sg = StudyGroup::whereRaw('LOWER(TRIM(name)) = ?', [$nama])->first();
        if ($sg) {
            return $this->resolveKelasIdForSiswa($sg->id, $tahunAjaran, $semester);
        }

        Log::warning("findKelasId: Kelas '{$namaKelas}' tidak ditemukan.");
        return null;
    }

    // =========================================================================
    // HELPER: TAHUN AJARAN DEFAULT
    // =========================================================================

    private function getDefaultAcademicYear(): string
    {
        $bulan = now()->month;
        return $bulan >= 7
            ? now()->year . '/' . (now()->year + 1)
            : (now()->year - 1) . '/' . now()->year;
    }

    // =========================================================================
    // HELPER: DAFTAR SEMESTER
    // =========================================================================

    private function getSemesterList(): array
    {
        return [
            '1' => 'Semester 1 (Ganjil)',
            '2' => 'Semester 2 (Genap)',
        ];
    }

    // =========================================================================
    // HELPER: DAFTAR TAHUN AJARAN (5 tahun ke belakang + 1 ke depan)
    // =========================================================================

    private function getTahunAjaranList(): array
    {
        $bulan       = now()->month;
        $tahunMulai  = $bulan >= 7 ? now()->year : now()->year - 1;
        $list        = [];

        // Dari 3 tahun lalu hingga 1 tahun depan
        for ($i = -3; $i <= 1; $i++) {
            $from      = $tahunMulai + $i;
            $to        = $from + 1;
            $key       = "{$from}/{$to}";
            $list[$key] = $key;
        }

        return array_reverse($list, true); // terbaru di atas
    }

    // =========================================================================
    // HELPER: AMBIL DAFTAR KELAS UNTUK DROPDOWN (dari StudyGroup)
    // =========================================================================

    private function getKelasList()
    {
        return StudyGroup::orderBy('grade')
                         ->orderBy('name')
                         ->get();
    }

    // =========================================================================
    // HELPER: PARSE TANGGAL
    // =========================================================================

    private function parseDate(mixed $value): ?string
    {
        if ($value === null || trim((string) $value) === '') return null;

        try {
            if (is_numeric($value) && (int) $value > 1000) {
                return Date::excelToDateTimeObject((float) $value)->format('Y-m-d');
            }

            $str = trim((string) $value);

            if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $str, $m)) {
                return sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
            }

            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $str)) {
                return $str;
            }

            $ts = strtotime($str);
            return $ts ? date('Y-m-d', $ts) : null;
        } catch (\Exception) {
            return null;
        }
    }

    // =========================================================================
    // HELPER: PARSE JENIS KELAMIN
    // =========================================================================

    private function parseJk(mixed $value): string
    {
        $v = strtoupper(trim((string) $value));
        return str_starts_with($v, 'P') ? 'P' : 'L';
    }

    // =========================================================================
    // HELPER: BERSIHKAN STRING
    // =========================================================================

    private function cleanString(mixed $value): ?string
    {
        $str = trim((string) $value);
        return ($str === '' || $str === 'null') ? null : $str;
    }
}