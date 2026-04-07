<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    // =========================================================================
    // CEK DEPENDENCY — dipanggil di awal setiap method yang butuh PhpSpreadsheet
    // =========================================================================

    private function requireSpreadsheet(): void
    {
        if (! class_exists(\PhpOffice\PhpSpreadsheet\Spreadsheet::class)) {
            abort(500,
                'Library PhpSpreadsheet belum terinstall. ' .
                'Jalankan: composer require phpoffice/phpspreadsheet'
            );
        }
    }

    // =========================================================================
    // INDEX
    // =========================================================================

    public function index(Request $request): View
    {
        $activeTab = $request->get('tab', 'guru');

        $gurus = User::where('role', 'guru')
            ->with(['guru', 'guru.kelas'])
            ->latest()->get();

        $siswas = User::where('role', 'siswa')
            ->with(['siswa', 'siswa.kelas'])
            ->latest()->get();

        $kelasList = Kelas::orderBy('nama')->get();

        // alias untuk kompatibilitas view lama yang mungkin pakai $kelas
        $kelas = $kelasList;

        return view('admin.users.index', compact(
            'gurus',
            'siswas',
            'activeTab',
            'kelasList',
            'kelas',
        ));
    }

    // =========================================================================
    // SHOW
    // =========================================================================

    public function show(User $user): View
    {
        $user->role === 'guru'
            ? $user->load(['guru', 'guru.kelas'])
            : $user->load(['siswa', 'siswa.kelas']);

        return view('admin.users.show', compact('user'));
    }

    // =========================================================================
    // STORE — Buat akun baru (guru / siswa)
    // =========================================================================

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'role'     => ['required', 'in:guru,siswa'],
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
            'kelas_id' => ['nullable', 'exists:kelas,id'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
        ]);

        if ($data['role'] === 'guru') {
            $user->guru()->create([]);
        } else {
            $user->siswa()->create([
                'kelas_id'     => $data['kelas_id'] ?? null,
                // penerima_kps = TINYINT: 0 = Tidak, 1 = Ya
                'penerima_kps' => 0,
                'jk'           => 'L',
            ]);
        }

        return redirect()
            ->route('admin.users.index', ['tab' => $data['role']])
            ->with('success', "Akun {$data['role']} \"{$data['name']}\" berhasil dibuat.");
    }

    // =========================================================================
    // UPDATE — Edit akun + profil user
    // =========================================================================

    public function update(Request $request, User $user): RedirectResponse
    {
        $rules = [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255',
                        \Illuminate\Validation\Rule::unique('users', 'email')->ignore($user->id)],
        ];

        if ($user->role === 'guru') {
            $rules = array_merge($rules, [
                'nama'                => ['nullable', 'string', 'max:255'],
                'nip'                 => ['nullable', 'string', 'max:30',
                                          \Illuminate\Validation\Rule::unique('gurus', 'nip')
                                              ->ignore($user->guru?->id)],
                'jk'                  => ['nullable', 'in:L,P'],
                'status_pegawai'      => ['nullable', 'string', 'max:100'],
                'pendidikan_terakhir' => ['nullable', 'string', 'max:100'],
                'kelas_id'            => ['nullable', 'exists:kelas,id'],
            ]);
        } else {
            $rules = array_merge($rules, [
                'nama'          => ['nullable', 'string', 'max:255'],
                'nidn'          => ['nullable', 'string', 'max:30'],
                'jk'            => ['nullable', 'in:L,P'],
                'agama'         => ['nullable', 'string', 'max:50'],
                'no_telp'       => ['nullable', 'string', 'max:20'],
                'kelas_id'      => ['nullable', 'exists:kelas,id'],
                'penerima_kps'  => ['nullable', 'in:Ya,Tidak'],
                'no_kps'        => ['nullable', 'string', 'max:50'],
            ]);
        }

        $data = $request->validate($rules);

        // ── 1. Update tabel users ────────────────────────────────────────────
        $user->update([
            'name'  => $data['name'],
            'email' => $data['email'],
        ]);

        // ── 2. Update profil (guru / siswa) ──────────────────────────────────
        if ($user->role === 'guru') {
            $guru = $user->guru ?? $user->guru()->create([]);

            // FIX: hapus array_filter — simpan semua field langsung termasuk null
            // Sebelumnya array_filter membuang nilai null sehingga kelas_id tidak
            // pernah ter-update ke null (tidak bisa hapus wali kelas)
            $guru->update([
                'nama'                => $data['nama']                ?? null,
                'nip'                 => $data['nip']                 ?? null,
                'jk'                  => $data['jk']                  ?? null,
                'status_pegawai'      => $data['status_pegawai']      ?? null,
                'pendidikan_terakhir' => $data['pendidikan_terakhir'] ?? null,
                'kelas_id'            => $data['kelas_id']            ?? null,
            ]);

        } else {
            // Ambil atau buat profil siswa jika belum ada
            $siswa = $user->siswa ?? $user->siswa()->create([
                'penerima_kps' => 0,
                'jk'           => 'L',
            ]);

            // FIX: konversi penerima_kps string (Ya/Tidak) → integer (1/0)
            // karena kolom penerima_kps adalah TINYINT di database
            $penerimarKps = match($data['penerima_kps'] ?? null) {
                'Ya'    => 1,
                'Tidak' => 0,
                default => $siswa->penerima_kps, // pertahankan nilai lama jika tidak dikirim
            };

            // FIX: simpan semua field langsung tanpa array_filter
            $siswa->update([
                'nama'         => $data['nama']     ?? null,
                'nidn'         => $data['nidn']     ?? null,
                'jk'           => $data['jk']       ?? null,
                'agama'        => $data['agama']    ?? null,
                'no_telp'      => $data['no_telp']  ?? null,
                'no_kps'       => $data['no_kps']   ?? null,
                'kelas_id'     => $data['kelas_id'] ?? null,
                'penerima_kps' => $penerimarKps,
            ]);
        }

        return redirect()
            ->route('admin.users.index', ['tab' => $user->role])
            ->with('success', "Data \"{$user->name}\" berhasil diperbarui.");
    }

    // =========================================================================
    // RESET PASSWORD
    // =========================================================================

    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
        ]);

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()
            ->route('admin.users.index', ['tab' => $user->role])
            ->with('success', "Password \"{$user->name}\" berhasil direset.");
    }

    // =========================================================================
    // DESTROY
    // =========================================================================

    public function destroy(User $user): RedirectResponse
    {
        $tab  = $user->role;
        $name = $user->name;
        $user->delete();

        return redirect()
            ->route('admin.users.index', ['tab' => $tab])
            ->with('success', "Akun \"{$name}\" berhasil dihapus.");
    }

    // =========================================================================
    // EXPORT EXCEL
    // =========================================================================

    public function exportExcel(Request $request): void
    {
        $this->requireSpreadsheet();

        $role = $request->get('role', 'guru');

        $role === 'siswa'
            ? $this->doExportSiswaExcel()
            : $this->doExportGuruExcel();
    }

    private function doExportGuruExcel(): void
    {
        $users = User::where('role', 'guru')
            ->with(['guru', 'guru.kelas'])
            ->latest()->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Guru');

        $headers = [
            'No', 'Nama Lengkap', 'Email', 'NIP', 'Jenis Kelamin',
            'Tempat Lahir', 'Tanggal Lahir', 'Pendidikan Terakhir',
            'Status Pegawai', 'Pangkat / Gol. Ruang',
            'No. SK Pertama', 'No. SK Terakhir', 'Wali Kelas',
        ];

        $this->applyHeaderRow($sheet, $headers, 1);

        $row = 2;
        foreach ($users as $i => $u) {
            $g = $u->guru;
            $sheet->fromArray([
                $i + 1,
                $g?->nama          ?? $u->name,
                $u->email,
                $g?->nip           ?? '',
                $g?->jk === 'L' ? 'Laki-laki' : ($g?->jk === 'P' ? 'Perempuan' : ''),
                $g?->tempat_lahir  ?? '',
                $g?->tanggal_lahir  ? $g->tanggal_lahir->format('d/m/Y') : '',
                $g?->pendidikan_terakhir ?? '',
                $g?->status_pegawai      ?? '',
                $g?->pangkat_gol_ruang   ?? '',
                $g?->no_sk_pertama       ?? '',
                $g?->no_sk_terakhir      ?? '',
                $g?->kelas?->nama        ?? '',
            ], null, 'A' . $row);

            $this->applyDataRow($sheet, 'A' . $row . ':M' . $row, $row);
            $row++;
        }

        foreach ([
            'A' => 6,  'B' => 28, 'C' => 30, 'D' => 22, 'E' => 14,
            'F' => 18, 'G' => 14, 'H' => 20, 'I' => 16, 'J' => 24,
            'K' => 28, 'L' => 28, 'M' => 16,
        ] as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        $this->streamExcel($spreadsheet, 'Data_Guru_' . now()->format('Ymd_His') . '.xlsx');
    }

    private function doExportSiswaExcel(): void
    {
        $users = User::where('role', 'siswa')
            ->with(['siswa', 'siswa.kelas'])
            ->latest()->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Siswa');

        $headers = [
            'No', 'Nama Lengkap', 'Email', 'NIS/NIDN', 'NIK',
            'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir',
            'Agama', 'No. Telepon', 'SKHUN', 'Kelas',
            'Alamat', 'RT', 'RW', 'Dusun', 'Kecamatan', 'Kode Pos',
            'Jenis Tinggal', 'Transportasi', 'Penerima KPS', 'No. KPS',
        ];

        $lastCol = $this->colLetter(count($headers));
        $this->applyHeaderRow($sheet, $headers, 1);

        $row = 2;
        foreach ($users as $i => $u) {
            $s = $u->siswa;
            $sheet->fromArray([
                $i + 1,
                $s?->nama               ?? $u->name,
                $u->email,
                $s?->nidn               ?? '',
                $s?->nik                ?? '',
                $s?->jk === 'L' ? 'Laki-laki' : ($s?->jk === 'P' ? 'Perempuan' : ''),
                $s?->tempat_lahir       ?? '',
                $s?->tgl_lahir           ? $s->tgl_lahir->format('d/m/Y') : '',
                $s?->agama              ?? '',
                $s?->no_telp            ?? '',
                $s?->shkun              ?? '',
                $s?->kelas?->nama       ?? '',
                $s?->alamat             ?? '',
                $s?->rt                 ?? '',
                $s?->rw                 ?? '',
                $s?->dusun              ?? '',
                $s?->kecamatan          ?? '',
                $s?->kode_pos           ?? '',
                $s?->jenis_tinggal      ?? '',
                $s?->jalan_transportasi ?? '',
                $s?->penerima_kps ? 'Ya' : 'Tidak',
                $s?->no_kps             ?? '',
            ], null, 'A' . $row);

            $this->applyDataRow($sheet, 'A' . $row . ':' . $lastCol . $row, $row);
            $row++;
        }

        foreach ([
            'A' => 6,  'B' => 28, 'C' => 30, 'D' => 14, 'E' => 18,
            'F' => 14, 'G' => 18, 'H' => 14, 'I' => 12, 'J' => 14,
            'K' => 16, 'L' => 14, 'M' => 28, 'N' => 6,  'O' => 6,
            'P' => 14, 'Q' => 18, 'R' => 10, 'S' => 18, 'T' => 18,
            'U' => 14, 'V' => 14,
        ] as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        $this->streamExcel($spreadsheet, 'Data_Siswa_' . now()->format('Ymd_His') . '.xlsx');
    }

    // =========================================================================
    // EXPORT PDF
    // =========================================================================

    public function exportPdf(Request $request)
    {
        $role = $request->get('role', 'guru');

        if ($role === 'siswa') {
            $users = User::where('role', 'siswa')->with(['siswa', 'siswa.kelas'])->latest()->get();
            $html  = $this->buildSiswaPdfHtml($users);
            $name  = 'Data_Siswa_' . now()->format('Ymd_His') . '.pdf';
        } else {
            $users = User::where('role', 'guru')->with(['guru', 'guru.kelas'])->latest()->get();
            $html  = $this->buildGuruPdfHtml($users);
            $name  = 'Data_Guru_' . now()->format('Ymd_His') . '.pdf';
        }

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            return \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)
                ->setPaper('a4', 'landscape')
                ->download($name);
        }

        return response($html, 200, [
            'Content-Type'        => 'text/html; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . str_replace('.pdf', '.html', $name) . '"',
        ]);
    }

    private function buildGuruPdfHtml($users): string
    {
        $rows = '';
        foreach ($users as $i => $u) {
            $g   = $u->guru;
            $jk  = $g?->jk === 'L' ? 'L' : ($g?->jk === 'P' ? 'P' : '—');
            $tgl = $g?->tanggal_lahir ? $g->tanggal_lahir->format('d/m/Y') : '—';
            $bg  = $i % 2 === 0 ? '#ffffff' : '#eef2ff';
            $rows .= "
            <tr style='background:{$bg}'>
                <td style='text-align:center'>" . ($i + 1) . "</td>
                <td><strong>" . $this->esc($g?->nama ?? $u->name) . "</strong>
                    <br><small style='color:#94a3b8'>" . $this->esc($u->email) . "</small></td>
                <td><code>" . $this->esc($g?->nip ?? '—') . "</code></td>
                <td style='text-align:center'>{$jk}</td>
                <td>" . $this->esc($g?->tempat_lahir ?? '—') . "<br><small>{$tgl}</small></td>
                <td>" . $this->esc($g?->pendidikan_terakhir ?? '—') . "</td>
                <td>" . $this->esc($g?->status_pegawai ?? '—') . "</td>
                <td>" . $this->esc($g?->pangkat_gol_ruang ?? '—') . "</td>
                <td>" . $this->esc($g?->kelas?->nama ?? '—') . "</td>
            </tr>";
        }

        return $this->pdfLayout('Data Guru', $users->count() . ' guru',
            '<th style="width:24px">#</th>
             <th>Nama / Email</th>
             <th>NIP</th>
             <th style="width:28px">JK</th>
             <th>Tempat / Tgl Lahir</th>
             <th>Pendidikan</th>
             <th>Status</th>
             <th>Pangkat / Gol</th>
             <th>Wali Kelas</th>',
            $rows
        );
    }

    private function buildSiswaPdfHtml($users): string
    {
        $rows = '';
        foreach ($users as $i => $u) {
            $s   = $u->siswa;
            $jk  = $s?->jk === 'L' ? 'L' : ($s?->jk === 'P' ? 'P' : '—');
            $tgl = $s?->tgl_lahir ? $s->tgl_lahir->format('d/m/Y') : '—';
            $bg  = $i % 2 === 0 ? '#ffffff' : '#eef2ff';
            $al  = implode(', ', array_filter([
                $s?->alamat,
                $s?->dusun,
                $s?->kecamatan,
                $s?->rt ? 'RT ' . $s->rt : null,
                $s?->rw ? 'RW ' . $s->rw : null,
                $s?->kode_pos,
            ])) ?: '—';
            $rows .= "
            <tr style='background:{$bg}'>
                <td style='text-align:center'>" . ($i + 1) . "</td>
                <td><strong>" . $this->esc($s?->nama ?? $u->name) . "</strong>
                    <br><small style='color:#94a3b8'>" . $this->esc($u->email) . "</small></td>
                <td><code>" . $this->esc($s?->nidn ?? '—') . "</code></td>
                <td style='text-align:center'>{$jk}</td>
                <td>" . $this->esc($s?->tempat_lahir ?? '—') . "<br><small>{$tgl}</small></td>
                <td>" . $this->esc($s?->agama ?? '—') . "</td>
                <td>" . $this->esc($s?->kelas?->nama ?? '—') . "</td>
                <td>" . $this->esc($al) . "</td>
                <td style='text-align:center'>" . ($s?->penerima_kps ? 'Ya' : 'Tidak') . "</td>
            </tr>";
        }

        return $this->pdfLayout('Data Siswa', $users->count() . ' siswa',
            '<th style="width:24px">#</th>
             <th>Nama / Email</th>
             <th>NIS</th>
             <th style="width:28px">JK</th>
             <th>Tempat / Tgl Lahir</th>
             <th>Agama</th>
             <th>Kelas</th>
             <th>Alamat</th>
             <th>KPS</th>',
            $rows
        );
    }

    private function pdfLayout(string $title, string $info, string $thead, string $tbody): string
    {
        $app  = config('app.name', 'Sistem Sekolah');
        $date = now()->isoFormat('D MMMM Y, HH:mm');
        return "<!DOCTYPE html>
<html lang='id'>
<head><meta charset='utf-8'>
<style>
* { box-sizing:border-box; margin:0; padding:0; }
body { font-family:'DejaVu Sans',Arial,sans-serif; font-size:8pt; color:#1e293b; padding:14px; }
h1   { font-size:13pt; color:#4f46e5; margin-bottom:2px; }
.sub { font-size:8pt; color:#64748b; margin-bottom:10px; }
table { width:100%; border-collapse:collapse; font-size:7.5pt; }
tr    { page-break-inside:avoid; }
th    { background:#4f46e5; color:#fff; padding:5px 6px; text-align:left; border:1px solid #6366f1; }
td    { padding:4px 6px; border:1px solid #e2e8f0; vertical-align:top; }
code  { font-size:7pt; background:#f1f5f9; padding:1px 3px; border-radius:2px; }
small { font-size:6.5pt; }
.footer { margin-top:8px; font-size:7pt; color:#94a3b8; border-top:1px solid #e2e8f0; padding-top:5px; }
</style></head>
<body>
<h1>{$title}</h1>
<p class='sub'>Total: {$info} &nbsp;|&nbsp; Dicetak: {$date}</p>
<table><thead><tr>{$thead}</tr></thead><tbody>{$tbody}</tbody></table>
<p class='footer'>Digenerate oleh {$app}</p>
</body></html>";
    }

    // =========================================================================
    // DOWNLOAD TEMPLATE IMPORT
    // =========================================================================

    public function downloadTemplate(Request $request): void
    {
        $this->requireSpreadsheet();

        $role = $request->get('role', 'guru');

        $role === 'siswa'
            ? $this->doTemplateSiswa()
            : $this->doTemplateGuru();
    }

    private function doTemplateGuru(): void
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Import Guru');

        $this->buildNoteRow($sheet,
            'PETUNJUK: Hapus baris ini sebelum upload. ' .
            'Kolom (*) wajib diisi. Jenis Kelamin: L atau P. ' .
            'Tanggal: dd/mm/yyyy. nama_kelas harus sama persis dengan kelas di sistem.',
            'M'
        );

        $this->buildHeaderRow($sheet, [
            'nama *', 'email *', 'password *', 'nip',
            'jenis_kelamin (L/P)', 'tempat_lahir', 'tanggal_lahir (dd/mm/yyyy)',
            'pendidikan_terakhir', 'status_pegawai', 'pangkat_gol_ruang',
            'no_sk_pertama', 'no_sk_terakhir', 'nama_kelas',
        ], 2);

        $this->buildExampleRow($sheet, [
            'Budi Santoso, S.Pd', 'budi@sekolah.sch.id', 'password123',
            '198501012010011001', 'L', 'Jakarta', '01/01/1985',
            'S1', 'PNS', 'Penata Muda / III-a', '001/SK/2010', '002/SK/2023', 'Kelas 7A',
        ], 3);

        $this->buildEmptyRows($sheet, 4, 23, 'M');

        foreach ([
            'A' => 28, 'B' => 32, 'C' => 16, 'D' => 22, 'E' => 20,
            'F' => 18, 'G' => 24, 'H' => 20, 'I' => 18, 'J' => 24,
            'K' => 26, 'L' => 26, 'M' => 16,
        ] as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        $sheet->freezePane('A3');
        $this->buildInfoSheet($spreadsheet->createSheet(), 'guru');
        $spreadsheet->setActiveSheetIndex(0);

        $this->streamExcel($spreadsheet, 'Template_Import_Guru.xlsx');
    }

    private function doTemplateSiswa(): void
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Import Siswa');

        $this->buildNoteRow($sheet,
            'PETUNJUK: Hapus baris ini sebelum upload. ' .
            'Kolom (*) wajib diisi. Jenis Kelamin: L atau P. ' .
            'Tanggal: dd/mm/yyyy. penerima_kps: Ya atau Tidak. ' .
            'nama_kelas harus sama persis dengan kelas di sistem.',
            'V'
        );

        $this->buildHeaderRow($sheet, [
            'nama *', 'email *', 'password *', 'nis_nidn', 'nik',
            'jenis_kelamin (L/P) *', 'tempat_lahir', 'tanggal_lahir (dd/mm/yyyy)',
            'agama', 'no_telp', 'shkun', 'nama_kelas',
            'alamat', 'rt', 'rw', 'dusun', 'kecamatan', 'kode_pos',
            'jenis_tinggal', 'transportasi', 'penerima_kps (Ya/Tidak) *', 'no_kps',
        ], 2);

        $this->buildExampleRow($sheet, [
            'Ani Rahayu', 'ani@sekolah.sch.id', 'password123',
            '2024001', '3201010101010001', 'P', 'Bogor', '01/01/2008',
            'Islam', '08123456789', '123456789', 'Kelas 7A',
            'Jl. Merdeka No. 1', '001', '002', 'Cikaret', 'Cibinong', '16913',
            'Bersama Orang Tua', 'Angkot', 'Tidak', '',
        ], 3);

        $this->buildEmptyRows($sheet, 4, 23, 'V');

        foreach ([
            'A' => 26, 'B' => 30, 'C' => 14, 'D' => 14, 'E' => 18,
            'F' => 20, 'G' => 16, 'H' => 24, 'I' => 12, 'J' => 14,
            'K' => 16, 'L' => 14, 'M' => 28, 'N' => 6,  'O' => 6,
            'P' => 14, 'Q' => 18, 'R' => 10, 'S' => 18, 'T' => 18,
            'U' => 22, 'V' => 14,
        ] as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        $sheet->freezePane('A3');
        $this->buildInfoSheet($spreadsheet->createSheet(), 'siswa');
        $spreadsheet->setActiveSheetIndex(0);

        $this->streamExcel($spreadsheet, 'Template_Import_Siswa.xlsx');
    }

    // =========================================================================
    // IMPORT DARI EXCEL
    // =========================================================================

    public function import(Request $request): RedirectResponse
    {
        $this->requireSpreadsheet();

        $request->validate([
            'role'        => ['required', 'in:guru,siswa'],
            'import_file' => ['required', 'file', 'mimes:xlsx,xls', 'max:5120'],
        ]);

        $role = $request->input('role');
        $file = $request->file('import_file');

        try {
            $reader      = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file->getPathname());
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getPathname());
            $rows        = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);
        } catch (\Exception $e) {
            return back()->withErrors(['import_file' => 'File tidak dapat dibaca: ' . $e->getMessage()]);
        }

        // Cari baris header (kolom A berisi 'nama' atau 'nama *')
        $headerIdx = null;
        foreach ($rows as $idx => $row) {
            $first = strtolower(trim(str_replace('*', '', (string)($row[0] ?? ''))));
            if ($first === 'nama') {
                $headerIdx = $idx;
                break;
            }
        }

        if ($headerIdx === null) {
            return back()->withErrors(['import_file' => 'Header tidak ditemukan. Kolom A baris header harus berisi "nama".']);
        }

        // Normalisasi nama kolom — hapus tanda *, satuan, dan keterangan dalam kurung
        $headers  = array_map(
            fn($h) => trim(strtolower(str_replace(
                ['*', ' (l/p)', ' (ya/tidak)', ' (dd/mm/yyyy)'],
                '',
                (string)$h
            ))),
            $rows[$headerIdx]
        );
        $dataRows = array_slice($rows, $headerIdx + 1);

        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        DB::beginTransaction();
        try {
            foreach ($dataRows as $lineNum => $row) {
                $lineNum += $headerIdx + 2;

                $data = [];
                foreach ($headers as $ci => $key) {
                    $data[$key] = trim((string)($row[$ci] ?? ''));
                }

                // Skip baris benar-benar kosong
                if (empty($data['nama']) && empty($data['email'])) continue;

                if (empty($data['nama'])) {
                    $errors[] = "Baris {$lineNum}: kolom 'nama' kosong.";
                    $skipped++;
                    continue;
                }
                if (empty($data['email'])) {
                    $errors[] = "Baris {$lineNum}: kolom 'email' kosong.";
                    $skipped++;
                    continue;
                }
                if (! filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Baris {$lineNum}: email '{$data['email']}' tidak valid.";
                    $skipped++;
                    continue;
                }
                if (User::where('email', $data['email'])->exists()) {
                    $errors[] = "Baris {$lineNum}: email '{$data['email']}' sudah terdaftar.";
                    $skipped++;
                    continue;
                }

                // penerima_kps & jenis_kelamin bersifat opsional di Excel
                // — makeSiswa() sudah handle fallback: penerima_kps→0, jk→'L'

                $password = ! empty($data['password']) ? $data['password'] : 'password123';

                $user = User::create([
                    'name'     => $data['nama'],
                    'email'    => $data['email'],
                    'password' => Hash::make($password),
                    'role'     => $role,
                ]);

                $role === 'guru'
                    ? $this->makeGuru($user, $data)
                    : $this->makeSiswa($user, $data);

                $imported++;
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['import_file' => 'Error saat menyimpan: ' . $e->getMessage()]);
        }

        $msg = "Import selesai: {$imported} data berhasil.";
        if ($skipped) $msg .= " {$skipped} baris dilewati.";
        if ($errors)  session()->flash('import_errors', array_slice($errors, 0, 20));

        return redirect()
            ->route('admin.users.index', ['tab' => $role])
            ->with('success', $msg);
    }

    // =========================================================================
    // PRIVATE — buat profil guru/siswa dari baris Excel
    // =========================================================================

    private function makeGuru(User $user, array $d): void
    {
        $kelasId = null;
        if (! empty($d['nama_kelas'])) {
            $kelasId = Kelas::whereRaw('LOWER(nama) = ?', [strtolower($d['nama_kelas'])])->value('id');
        }

        $user->guru()->create([
            'nama'                => $d['nama']                ?? null,
            'nip'                 => $d['nip']                 ?? null,
            'kelas_id'            => $kelasId,
            'jk'                  => $this->parseJk($d['jenis_kelamin'] ?? null),
            'tempat_lahir'        => $d['tempat_lahir']        ?? null,
            'tanggal_lahir'       => $this->parseDate($d['tanggal_lahir'] ?? null),
            'pendidikan_terakhir' => $d['pendidikan_terakhir'] ?? null,
            'status_pegawai'      => $d['status_pegawai']      ?? null,
            'pangkat_gol_ruang'   => $d['pangkat_gol_ruang']   ?? null,
            'no_sk_pertama'       => $d['no_sk_pertama']       ?? null,
            'no_sk_terakhir'      => $d['no_sk_terakhir']      ?? null,
        ]);
    }

    private function makeSiswa(User $user, array $d): void
    {
        $kelasId = null;
        if (! empty($d['nama_kelas'])) {
            $kelasId = Kelas::whereRaw('LOWER(nama) = ?', [strtolower($d['nama_kelas'])])->value('id');
        }

        // penerima_kps = TINYINT: 1 = Ya, 0 = Tidak (default aman jika kosong)
        $raw = strtolower(trim($d['penerima_kps'] ?? ''));
        $kps = in_array($raw, ['ya', 'y', '1']) ? 1 : 0;

        // ── FIX: jk WAJIB tidak boleh null ────────────────────────────────────
        // parseJk() sudah mengembalikan 'L'/'P'/null — fallback ke 'L' jika null
        $jk = $this->parseJk($d['jenis_kelamin'] ?? null) ?? 'L';

        $user->siswa()->create([
            'nama'               => $d['nama']            ?? null,
            'nidn'               => $d['nis_nidn']         ?? $d['nis'] ?? null,
            'kelas_id'           => $kelasId,
            'nik'                => $d['nik']              ?? null,
            'jk'                 => $jk,                  // tidak pernah null
            'tempat_lahir'       => $d['tempat_lahir']     ?? null,
            'tgl_lahir'          => $this->parseDate($d['tanggal_lahir'] ?? null),
            'agama'              => $d['agama']            ?? null,
            'no_telp'            => $d['no_telp']          ?? null,
            'shkun'              => $d['shkun']            ?? null,
            'alamat'             => $d['alamat']           ?? null,
            'rt'                 => $d['rt']               ?? null,
            'rw'                 => $d['rw']               ?? null,
            'dusun'              => $d['dusun']            ?? null,
            'kecamatan'          => $d['kecamatan']        ?? null,
            'kode_pos'           => $d['kode_pos']         ?? null,
            'jenis_tinggal'      => $d['jenis_tinggal']    ?? null,
            'jalan_transportasi' => $d['transportasi']     ?? null,
            'penerima_kps'       => $kps,                 // tidak pernah null
            'no_kps'             => $d['no_kps']           ?? null,
        ]);
    }

    // =========================================================================
    // PRIVATE — Spreadsheet style helpers
    // =========================================================================

    private function applyHeaderRow($sheet, array $headers, int $rowNum): void
    {
        $lastCol = $this->colLetter(count($headers));
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . $rowNum, $h);
            $col++;
        }
        $sheet->getStyle("A{$rowNum}:{$lastCol}{$rowNum}")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4338CA']],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'borders'   => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => '6366F1']]],
        ]);
        $sheet->getRowDimension($rowNum)->setRowHeight(28);
    }

    private function applyDataRow($sheet, string $range, int $rowNum): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
            'font'    => ['size' => 9],
            'fill'    => [
                'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => $rowNum % 2 === 0 ? 'EEF2FF' : 'FFFFFF'],
            ],
        ]);
        $sheet->getRowDimension($rowNum)->setRowHeight(18);
    }

    private function buildNoteRow($sheet, string $text, string $lastCol): void
    {
        $sheet->mergeCells('A1:' . $lastCol . '1');
        $sheet->setCellValue('A1', $text);
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['italic' => true, 'color' => ['rgb' => '92400E'], 'size' => 9],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEF9C3']],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'borders'   => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'FDE68A']]],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(42);
    }

    private function buildHeaderRow($sheet, array $headers, int $rowNum): void
    {
        $lastCol = $this->colLetter(count($headers));
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . $rowNum, $h);
            $col++;
        }
        $sheet->getStyle("A{$rowNum}:{$lastCol}{$rowNum}")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4338CA']],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'borders'   => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => '6366F1']]],
        ]);
        $sheet->getRowDimension($rowNum)->setRowHeight(30);
    }

    private function buildExampleRow($sheet, array $values, int $rowNum): void
    {
        $lastCol = $this->colLetter(count($values));
        $col = 'A';
        foreach ($values as $v) {
            $sheet->setCellValue($col . $rowNum, $v);
            $col++;
        }
        $sheet->getStyle("A{$rowNum}:{$lastCol}{$rowNum}")->applyFromArray([
            'font'      => ['italic' => true, 'color' => ['rgb' => '6366F1'], 'size' => 9],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EEF2FF']],
            'alignment' => ['vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'C7D2FE']]],
        ]);
        $sheet->getRowDimension($rowNum)->setRowHeight(22);
    }

    private function buildEmptyRows($sheet, int $from, int $to, string $lastCol): void
    {
        for ($r = $from; $r <= $to; $r++) {
            $sheet->getStyle("A{$r}:{$lastCol}{$r}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
                'fill'    => [
                    'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $r % 2 === 0 ? 'F8FAFC' : 'FFFFFF'],
                ],
            ]);
            $sheet->getRowDimension($r)->setRowHeight(18);
        }
    }

    private function buildInfoSheet($infoSheet, string $role): void
    {
        $infoSheet->setTitle('Keterangan');
        $infoSheet->getColumnDimension('A')->setWidth(26);
        $infoSheet->getColumnDimension('B')->setWidth(10);
        $infoSheet->getColumnDimension('C')->setWidth(54);

        $infoSheet->setCellValue('A1', 'Nama Kolom');
        $infoSheet->setCellValue('B1', 'Wajib?');
        $infoSheet->setCellValue('C1', 'Keterangan / Contoh');

        $infoSheet->getStyle('A1:C1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4338CA']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => '6366F1']]],
        ]);
        $infoSheet->getRowDimension(1)->setRowHeight(26);

        $keterangan = $role === 'guru' ? [
            ['nama',                 'Ya',    'Nama lengkap guru. Contoh: Budi Santoso, S.Pd'],
            ['email',                'Ya',    'Email untuk login. Harus unik. Contoh: budi@sekolah.sch.id'],
            ['password',             'Ya',    'Password awal akun. Min 8 karakter.'],
            ['nip',                  'Tidak', 'Nomor Induk Pegawai. Contoh: 198501012010011001'],
            ['jenis_kelamin',        'Tidak', 'L = Laki-laki, P = Perempuan'],
            ['tempat_lahir',         'Tidak', 'Kota kelahiran. Contoh: Jakarta'],
            ['tanggal_lahir',        'Tidak', 'Format dd/mm/yyyy. Contoh: 01/01/1985'],
            ['pendidikan_terakhir',  'Tidak', 'Contoh: S1, S2, D3'],
            ['status_pegawai',       'Tidak', 'Contoh: PNS, PPPK, Honorer, GTT'],
            ['pangkat_gol_ruang',    'Tidak', 'Contoh: Penata Muda / III-a'],
            ['no_sk_pertama',        'Tidak', 'Nomor SK pengangkatan pertama'],
            ['no_sk_terakhir',       'Tidak', 'Nomor SK terakhir/terbaru'],
            ['nama_kelas',           'Tidak', 'Harus sama persis dengan nama kelas di sistem. Contoh: Kelas 7A'],
        ] : [
            ['nama',                 'Ya',    'Nama lengkap siswa. Contoh: Ani Rahayu'],
            ['email',                'Ya',    'Email untuk login. Harus unik.'],
            ['password',             'Ya',    'Password awal akun. Min 8 karakter.'],
            ['nis_nidn',             'Tidak', 'Nomor Induk Siswa. Contoh: 2024001'],
            ['nik',                  'Tidak', 'NIK 16 digit.'],
            ['jenis_kelamin',        'Ya',    'L = Laki-laki, P = Perempuan (wajib diisi)'],
            ['tempat_lahir',         'Tidak', 'Kota kelahiran'],
            ['tanggal_lahir',        'Tidak', 'Format dd/mm/yyyy. Contoh: 01/01/2008'],
            ['agama',                'Tidak', 'Contoh: Islam, Kristen, Katolik, Hindu, Buddha'],
            ['no_telp',              'Tidak', 'Contoh: 08123456789'],
            ['shkun',                'Tidak', 'Nomor SKHUN'],
            ['nama_kelas',           'Tidak', 'Harus sama persis dengan nama kelas di sistem. Contoh: Kelas 7A'],
            ['alamat',               'Tidak', 'Alamat jalan lengkap'],
            ['rt',                   'Tidak', 'Nomor RT. Contoh: 001'],
            ['rw',                   'Tidak', 'Nomor RW. Contoh: 002'],
            ['dusun',                'Tidak', 'Nama dusun/lingkungan'],
            ['kecamatan',            'Tidak', 'Nama kecamatan'],
            ['kode_pos',             'Tidak', '5 digit kode pos'],
            ['jenis_tinggal',        'Tidak', 'Contoh: Bersama Orang Tua, Kos, Asrama'],
            ['transportasi',         'Tidak', 'Contoh: Jalan Kaki, Angkot, Sepeda Motor'],
            ['penerima_kps',         'Ya',    'Ya atau Tidak (wajib diisi, default: Tidak)'],
            ['no_kps',               'Tidak', 'Nomor KPS jika ada'],
        ];

        $r = 2;
        foreach ($keterangan as $item) {
            $infoSheet->setCellValue('A' . $r, $item[0]);
            $infoSheet->setCellValue('B' . $r, $item[1]);
            $infoSheet->setCellValue('C' . $r, $item[2]);

            $wajib = $item[1] === 'Ya';
            $infoSheet->getStyle("A{$r}:C{$r}")->applyFromArray([
                'font'      => ['size' => 9, 'bold' => $wajib],
                'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => $r % 2 === 0 ? 'F8FAFC' : 'FFFFFF']],
                'alignment' => ['vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'wrapText' => true],
                'borders'   => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
            ]);
            if ($wajib) {
                $infoSheet->getStyle('B' . $r)->applyFromArray([
                    'font' => ['color' => ['rgb' => 'DC2626'], 'bold' => true],
                ]);
            }
            $infoSheet->getRowDimension($r)->setRowHeight(20);
            $r++;
        }
    }

    // =========================================================================
    // PRIVATE — Utility helpers
    // =========================================================================

    private function streamExcel(\PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet, string $filename): void
    {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . rawurlencode($filename) . '"');
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: public');
        header('Expires: 0');

        $writer->save('php://output');
        exit;
    }

    private function colLetter(int $n): string
    {
        $result = '';
        while ($n > 0) {
            $n--;
            $result = chr(65 + ($n % 26)) . $result;
            $n      = (int)($n / 26);
        }
        return $result;
    }

    private function parseJk(?string $v): ?string
    {
        if (! $v) return null;
        $v = strtoupper(trim($v));
        if ($v === 'L' || str_starts_with($v, 'L')) return 'L';
        if ($v === 'P' || str_starts_with($v, 'P')) return 'P';
        return null;
    }

    private function parseDate(?string $v): ?string
    {
        if (! $v) return null;
        $v = trim($v);

        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $v, $m)) {
            return $m[3] . '-' . str_pad($m[2], 2, '0', STR_PAD_LEFT) . '-' . str_pad($m[1], 2, '0', STR_PAD_LEFT);
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $v)) {
            return $v;
        }

        if (is_numeric($v)) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float)$v)->format('Y-m-d');
            } catch (\Exception $e) {
                // biarkan null
            }
        }

        return null;
    }

    private function esc(?string $v): string
    {
        return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8');
    }
}