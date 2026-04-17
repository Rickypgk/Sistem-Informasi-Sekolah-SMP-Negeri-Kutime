<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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


class UserController extends Controller
{
    // =========================================================================
    // TAMPILAN INDEX
    // =========================================================================

    public function index(Request $request): View
    {
        $activeTab = $request->get('tab', 'guru');

        $gurus     = User::where('role', 'guru')->with(['guru.kelas'])->latest()->get();
        $siswas    = User::where('role', 'siswa')->with(['siswa.kelas'])->latest()->get();
        $kelasList = Kelas::orderBy('nama')->get();

        return view('admin.users.index', compact('gurus', 'siswas', 'activeTab', 'kelasList'));
    }

    // =========================================================================
    // DOWNLOAD TEMPLATE IMPORT EXCEL
    // URL: ?role=guru | ?role=siswa | ?guru= | ?siswa=
    // =========================================================================
    public function downloadTemplate(Request $request)
    {
        $role = $request->query('role')
            ?? ($request->has('guru') ? 'guru' : null)
            ?? ($request->has('siswa') ? 'siswa' : null);

        abort_unless(in_array($role, ['guru', 'siswa'], true), 404);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        if ($role === 'guru') {
            $sheetTitle = 'Import Guru';
            $filename = 'template_import_guru.xlsx';
            $headers = [
                'nama', 'email', 'password', 'nip', 'jenis_kelamin (L/P)',
                'tempat_lahir', 'tanggal_lahir (dd/mm/yyyy)', 'pendidikan_terakhir',
                'status_pegawai', 'pangkat_gol_rang', 'no_sk_pertama',
                'no_sk_terakhir', 'nama_kelas'
            ];
            $contohData = [
                'Budi Santoso, S.Pd', 'budi@guru.sch.id', 'password123',
                '198501012010011001', 'L', 'Jakarta', '01/01/1985',
                'S1 Pendidikan Matematika', 'PNS', 'Penata Muda / III-a',
                '001/SK/2010', '002/SK/2023', 'Kelas 7A'
            ];
        } else {
            $sheetTitle = 'Import Siswa';
            $filename = 'template_import_siswa.xlsx';
            $headers = [
                'nama', 'email', 'password', 'nis_nipd', 'nik', 'jenis_kelamin (L/P)',
                'tempat_lahir', 'tanggal_lahir (dd/mm/yyyy)', 'agama', 'no_telp',
                'shkun', 'nama_kelas', 'alamat', 'rt', 'rw', 'dusun',
                'kecamatan', 'kode_pos', 'jenis_tinggal', 'transportasi',
                'penerima_kps (Ya/Tidak)', 'no_kps'
            ];
            $contohData = [
                'Ani Rahayu', 'ani@siswa.sch.id', 'password123', '20240001',
                '3201010101010001', 'P', 'Bogor', '01/01/2010', 'Islam',
                '08123456789', '', 'Kelas 7A', 'Jl. Merdeka No. 1', '001',
                '002', 'Cikaret', 'Cibinong', '16913', 'Bersama Orang Tua',
                'Jalan kaki', 'Tidak', ''
            ];
        }

        $sheet->setTitle($sheetTitle);
        $colCount = count($headers);
        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colCount);

        // Header
        $sheet->fromArray($headers, null, 'A1');
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);

        // Contoh data
        $sheet->fromArray($contohData, null, 'A2');
        $sheet->getStyle("A2:{$lastCol}2")->applyFromArray([
            'font' => ['italic' => true, 'color' => ['rgb' => '6B7280']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F3F4F6']],
        ]);

        // Auto-size
        foreach (range(1, $colCount) as $colIndex) {
            $sheet->getColumnDimensionByColumn($colIndex)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Konfigurasi template untuk Guru
     */
    private function getGuruTemplateConfig(): array
    {
        return [
            'title' => 'Import Guru',
            'filename' => 'template_import_guru.xlsx',
            'headers' => [
                'nama',
                'email',
                'password',
                'nip',
                'jenis_kelamin (L/P)',
                'tempat_lahir',
                'tanggal_lahir (dd/mm/yyyy)',
                'pendidikan_terakhir',
                'status_pegawai',
                'pangkat_gol_ruang',
                'no_sk_pertama',
                'no_sk_terakhir',
                'nama_kelas',
            ],
            'contohData' => [
                'Budi Santoso, S.Pd',
                'budi@guru.sch.id',
                'password123',
                '198501012010011001',
                'L',
                'Jakarta',
                '01/01/1985',
                'S1 Pendidikan Matematika',
                'PNS',
                'Penata Muda / III-a',
                '001/SK/2010',
                '002/SK/2023',
                'Kelas 7A',
            ]
        ];
    }

    /**
     * Konfigurasi template untuk Siswa
     */
    private function getSiswaTemplateConfig(): array
    {
        return [
            'title' => 'Import Siswa',
            'filename' => 'template_import_siswa.xlsx',
            'headers' => [
                'nama',
                'email',
                'password',
                'nis_nipd',
                'nik',
                'jenis_kelamin (L/P)',
                'tempat_lahir',
                'tanggal_lahir (dd/mm/yyyy)',
                'agama',
                'no_telp',
                'shkun',
                'nama_kelas',
                'alamat',
                'rt',
                'rw',
                'dusun',
                'kecamatan',
                'kode_pos',
                'jenis_tinggal',
                'transportasi',
                'penerima_kps (Ya/Tidak)',
                'no_kps',
            ],
            'contohData' => [
                'Ani Rahayu',
                'ani@siswa.sch.id',
                'password123',
                '20240001',
                '3201010101010001',
                'P',
                'Bogor',
                '01/01/2010',
                'Islam',
                '08123456789',
                '',
                'Kelas 7A',
                'Jl. Merdeka No. 1',
                '001',
                '002',
                'Cikaret',
                'Cibinong',
                '16913',
                'Bersama Orang Tua',
                'Jalan kaki',
                'Tidak',
                '',
            ]
        ];
    }

    /**
     * Setup sheet dengan styling lengkap
     */
    private function setupTemplateSheet($sheet, array $config): void
    {
        $sheet->setTitle($config['title']);
        $headers = $config['headers'];
        $contohData = $config['contohData'];

        $colCount = count($headers);
        $lastCol  = Coordinate::stringFromColumnIndex($colCount);

        // Baris 1 — Header (Bold, Background Biru, Center)
        $sheet->fromArray($headers, null, 'A1');
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders'   => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'D1D5DB'],
                ],
            ],
        ]);

        // Baris 2 — Contoh data (Italic, Background Abu-abu muda)
        $sheet->fromArray($contohData, null, 'A2');
        $sheet->getStyle("A2:{$lastCol}2")->applyFromArray([
            'font'      => ['italic' => true, 'color' => ['rgb' => '6B7280']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F3F4F6']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            'borders'   => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'D1D5DB'],
                ],
            ],
        ]);

        // Baris 3 — Petunjuk (Italic, Background Putih)
        $sheet->setCellValue('A3', '← Contoh data (HAPUS baris ini sebelum import)');
        $sheet->getStyle('A3')->applyFromArray([
            'font'      => ['italic' => true, 'color' => ['rgb' => '9CA3AF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        ]);

        // Auto-size semua kolom
        foreach (range(1, $colCount) as $colIndex) {
            $sheet->getColumnDimensionByColumn($colIndex)->setAutoSize(true);
        }

        // Freeze pane di baris 3
        $sheet->freezePane('A3');

        // Set tinggi baris
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->getRowDimension(2)->setRowHeight(20);
        $sheet->getRowDimension(3)->setRowHeight(18);
    }


    // =========================================================================
    // IMPORT EXCEL — FUNGSI UTAMA
    // =========================================================================

    public function import(Request $request): RedirectResponse
    {
        // 1. Validasi input
        $request->validate([
            'role'            => 'required|in:guru,siswa',
            'password_import' => 'required|string|min:5',
            'import_file'     => 'required|file|mimes:xlsx,xls',
        ]);

        $role         = $request->role;
        $passwordHash = Hash::make($request->password_import);
        $file         = $request->file('import_file');

        // 2. Baca file Excel
        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet       = $spreadsheet->getActiveSheet();
            $rows        = $sheet->toArray(null, true, true, false);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
        }

        // 3. Temukan baris header
        //    Toleran terhadap "nama", "nama *", "nama*", "NAMA", dst.
        $headerRowIndex = null;

        foreach ($rows as $index => $row) {
            $cellA      = strtolower(trim((string) ($row[0] ?? '')));
            $cellAClean = ltrim($cellA, " \t\u{00A0}");

            if ($cellAClean === 'nama' || str_starts_with($cellAClean, 'nama')) {
                $headerRowIndex = $index;
                break;
            }
        }

        if ($headerRowIndex === null) {
            return back()->with('error',
                'Format file tidak dikenali. Pastikan baris pertama berisi header kolom ' .
                'yang dimulai dengan kata "nama". Gunakan template yang tersedia.'
            );
        }

        // 4. Ambil baris data (setelah header), buang baris contoh / kosong
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
            return back()->with('error',
                'Tidak ada data yang ditemukan di file. ' .
                'Pastikan data diisi mulai dari baris ketiga (setelah header dan baris contoh).'
            );
        }

        // 5. Proses import dalam transaksi
        $importedCount = 0;
        $errors        = [];

        DB::beginTransaction();
        try {
            foreach ($dataRows as $rowIndex => $row) {
                $namaRaw  = trim((string) ($row[0] ?? ''));
                $emailRaw = trim((string) ($row[1] ?? ''));

                // Skip baris kosong
                if (empty($namaRaw) || empty($emailRaw)) continue;

                // Validasi format email
                if (!filter_var($emailRaw, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Baris data ke-" . ($rowIndex + 1) . ": Email \"{$emailRaw}\" tidak valid, baris dilewati.";
                    continue;
                }

                // Cek duplikasi email
                if (User::where('email', $emailRaw)->exists()) {
                    $errors[] = "Baris data ke-" . ($rowIndex + 1) . ": Email {$emailRaw} sudah terdaftar.";
                    continue;
                }

                // Simpan ke tabel users
                $user = User::create([
                    'name'     => $namaRaw,
                    'email'    => $emailRaw,
                    'password' => $passwordHash,
                    'role'     => $role,
                ]);

                // Simpan ke tabel profil
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
            return back()->with('error', 'Terjadi kesalahan sistem saat menyimpan data: ' . $e->getMessage());
        }

        // 6. Feedback ke user
        if ($importedCount === 0) {
            return back()
                ->with('error', 'Tidak ada data yang berhasil diimpor. Periksa kembali isi file.')
                ->with('import_errors', $errors);
        }

        return redirect()
            ->route('admin.users.index', ['tab' => $role])
            ->with('success', "Berhasil mengimpor {$importedCount} akun {$role}.")
            ->with('import_errors', $errors);
    }

    // =========================================================================
    // HELPER: SIMPAN PROFIL GURU
    // =========================================================================
    // Urutan kolom template guru (0-based):
    // 0:nama  1:email  2:password(diabaikan)  3:nip  4:jk  5:tempat_lahir
    // 6:tgl_lahir  7:pendidikan  8:status  9:pangkat  10:sk1  11:sk2  12:nama_kelas

    private function storeGuruProfile(User $user, array $row): void
    {
        $kelasId = $this->findKelasId($row[12] ?? null);

        $user->guru()->create([
            'nama'                => $user->name,
            'nip'                 => $this->cleanString($row[3] ?? null),
            'jk'                  => $this->parseJk($row[4] ?? null),
            'tempat_lahir'        => $this->cleanString($row[5] ?? null),
            'tanggal_lahir'       => $this->parseDate($row[6] ?? null),
            'pendidikan_terakhir' => $this->cleanString($row[7] ?? null),
            'status_pegawai'      => $this->cleanString($row[8] ?? null),
            'pangkat_gol_ruang'   => $this->cleanString($row[9] ?? null),
            'no_sk_pertama'       => $this->cleanString($row[10] ?? null),
            'no_sk_terakhir'      => $this->cleanString($row[11] ?? null),
            'kelas_id'            => $kelasId,
        ]);
    }

    // =========================================================================
    // HELPER: SIMPAN PROFIL SISWA
    // =========================================================================
    // Urutan kolom template siswa (0-based):
    // 0:nama  1:email  2:password(diabaikan)  3:nis_nipd  4:nik  5:jk
    // 6:tempat_lahir  7:tgl_lahir  8:agama  9:no_telp  10:shkun  11:nama_kelas
    // 12:alamat  13:rt  14:rw  15:dusun  16:kecamatan  17:kode_pos
    // 18:jenis_tinggal  19:transportasi  20:penerima_kps  21:no_kps

    private function storeSiswaProfile(User $user, array $row): void
    {
        $kelasId = $this->findKelasId($row[11] ?? null);
        $kpsRaw  = strtolower(trim((string) ($row[20] ?? '')));
        $isKps   = in_array($kpsRaw, ['ya', 'yes', '1', 'y', 'true'], true) ? 1 : 0;

        $user->siswa()->create([
            'nama'               => $user->name,
            'nidn'               => $this->cleanString($row[3] ?? null),
            'nik'                => $this->cleanString($row[4] ?? null),
            'jk'                 => $this->parseJk($row[5] ?? null),
            'tempat_lahir'       => $this->cleanString($row[6] ?? null),
            'tgl_lahir'          => $this->parseDate($row[7] ?? null),
            'agama'              => $this->cleanString($row[8] ?? null),
            'no_telp'            => $this->cleanString($row[9] ?? null),
            'shkun'              => $this->cleanString($row[10] ?? null),
            'kelas_id'           => $kelasId,
            'alamat'             => $this->cleanString($row[12] ?? null),
            'rt'                 => $this->cleanString($row[13] ?? null),
            'rw'                 => $this->cleanString($row[14] ?? null),
            'dusun'              => $this->cleanString($row[15] ?? null),
            'kecamatan'          => $this->cleanString($row[16] ?? null),
            'kode_pos'           => $this->cleanString($row[17] ?? null),
            'jenis_tinggal'      => $this->cleanString($row[18] ?? null),
            'jalan_transportasi' => $this->cleanString($row[19] ?? null),
            'penerima_kps'       => $isKps,
            'no_kps'             => $this->cleanString($row[21] ?? null),
        ]);
    }

    // =========================================================================
    // HELPER: CARI KELAS ID BERDASARKAN NAMA
    // =========================================================================

    private function findKelasId(?string $namaKelas): ?int
    {
        if (empty(trim((string) $namaKelas))) return null;

        $kelas = Kelas::whereRaw('LOWER(TRIM(nama)) = ?', [strtolower(trim($namaKelas))])->first();

        return $kelas?->id;
    }

    // =========================================================================
    // HELPER: PARSE TANGGAL
    // Format yang didukung:
    //   - Angka serial Excel  (mis: 45292)
    //   - dd/mm/yyyy          (mis: 01/01/1985)
    //   - yyyy-mm-dd          (mis: 1985-01-01)
    //   - dd-mm-yyyy          (mis: 01-01-1985)
    // =========================================================================

    private function parseDate(mixed $value): ?string
    {
        if ($value === null || trim((string) $value) === '') return null;

        try {
            // Serial date Excel
            if (is_numeric($value) && (int) $value > 1000) {
                return Date::excelToDateTimeObject((float) $value)->format('Y-m-d');
            }

            $str = trim((string) $value);

            // Format dd/mm/yyyy atau dd-mm-yyyy
            if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $str, $m)) {
                return sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
            }

            // Format yyyy-mm-dd (sudah benar)
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $str)) {
                return $str;
            }

            // Fallback: strtotime
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
        if (str_starts_with($v, 'P')) return 'P';
        return 'L'; // default L
    }

    // =========================================================================
    // HELPER: BERSIHKAN STRING (null jika kosong)
    // =========================================================================

    private function cleanString(mixed $value): ?string
    {
        $str = trim((string) $value);
        return ($str === '' || $str === 'null') ? null : $str;
    }

    // =========================================================================
    // TAMBAH USER MANUAL
    // =========================================================================

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role'     => 'required|in:guru,siswa,admin',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('admin.users.index', ['tab' => $user->role])
            ->with('success', 'User berhasil ditambahkan.');
    }

    // =========================================================================
    // HAPUS USER
    // =========================================================================

    public function destroy(User $user): RedirectResponse
    {
        $role = $user->role;
        $user->delete();

        return redirect()
            ->route('admin.users.index', ['tab' => $role])
            ->with('success', 'User berhasil dihapus.');
    }

    // =========================================================================
    // EXPORT EXCEL SEMUA DATA GURU / SISWA
    // URL: ?role=guru  |  ?role=siswa
    // =========================================================================

    public function exportExcel(Request $request)
    {
        $role = $request->query('role', 'guru');

        abort_unless(in_array($role, ['guru', 'siswa'], true), 404, 'Role tidak valid.');

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle(ucfirst($role));

        if ($role === 'guru') {
            $filename = 'data_guru.xlsx';
            $headers  = [
                'No', 'Nama', 'Email', 'NIP', 'Jenis Kelamin', 'Tempat Lahir',
                'Tanggal Lahir', 'Pendidikan Terakhir', 'Status Pegawai',
                'Pangkat/Golongan', 'No SK Pertama', 'No SK Terakhir', 'Kelas',
            ];

            $data = User::where('role', 'guru')->with('guru.kelas')->latest()->get();
            $rows = [];

            foreach ($data as $index => $user) {
                $guru   = $user->guru;
                $rows[] = [
                    $index + 1,
                    $user->name,
                    $user->email,
                    $guru?->nip ?? '-',
                    $guru?->jk ?? '-',
                    $guru?->tempat_lahir ?? '-',
                    $guru?->tanggal_lahir ? $guru->tanggal_lahir->format('d/m/Y') : '-',
                    $guru?->pendidikan_terakhir ?? '-',
                    $guru?->status_pegawai ?? '-',
                    $guru?->pangkat_gol_ruang ?? '-',
                    $guru?->no_sk_pertama ?? '-',
                    $guru?->no_sk_terakhir ?? '-',
                    $guru?->kelas?->nama ?? '-',
                ];
            }
        } else {
            $filename = 'data_siswa.xlsx';
            $headers  = [
                'No', 'Nama', 'Email', 'NIS/NIPD', 'NIK', 'Jenis Kelamin',
                'Tempat Lahir', 'Tanggal Lahir', 'Agama', 'No Telp', 'Kelas', 'Alamat',
            ];

            $data = User::where('role', 'siswa')->with('siswa.kelas')->latest()->get();
            $rows = [];

            foreach ($data as $index => $user) {
                $siswa  = $user->siswa;
                $rows[] = [
                    $index + 1,
                    $user->name,
                    $user->email,
                    $siswa?->nidn ?? '-',
                    $siswa?->nik ?? '-',
                    $siswa?->jk ?? '-',
                    $siswa?->tempat_lahir ?? '-',
                    $siswa?->tgl_lahir ? $siswa->tgl_lahir->format('d/m/Y') : '-',
                    $siswa?->agama ?? '-',
                    $siswa?->no_telp ?? '-',
                    $siswa?->kelas?->nama ?? '-',
                    $siswa?->alamat ?? '-',
                ];
            }
        }

        // Tulis header & data
        $sheet->fromArray($headers, null, 'A1');
        if (!empty($rows)) {
            $sheet->fromArray($rows, null, 'A2');
        }

        $lastCol    = Coordinate::stringFromColumnIndex(count($headers));
        $lastDataRow = count($rows) + 1;

        // Styling header
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Border seluruh tabel
        if ($lastDataRow > 1) {
            $sheet->getStyle("A1:{$lastCol}{$lastDataRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['rgb' => 'D1D5DB'],
                    ],
                ],
            ]);
        }

        // Auto size kolom
        foreach (range(1, count($headers)) as $col) {
            $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(
            function () use ($writer) {
                $writer->save('php://output');
            },
            $filename,
            [
                'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                'Cache-Control'       => 'max-age=0',
            ]
        );
    }
}