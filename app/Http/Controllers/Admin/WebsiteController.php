<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\PageContent;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebsiteController extends Controller
{
    // ══════════════════════════════════════════════════════════════
    // INDEX — Halaman utama kelola website (semua tab)
    // ══════════════════════════════════════════════════════════════

    public function kelolaWebsite(Request $request)
    {
        $tab       = $request->query('tab', 'home');
        $contents  = PageContent::all()->keyBy('key');
        $heroMedia = PageContent::getHeroMedia();
        $kontak    = PageContent::getKontak();
        $stats     = PageContent::getStats();

        $tipeOptions = [
            'image' => '🖼️ Gambar',
            'video' => '🎥 Video',
            'youtube' => '▶️ YouTube',
            'file' => '📄 File',
        ];

        $kategoriOptions = [
            'kegiatan' => 'Kegiatan',
            'fasilitas' => 'Fasilitas',
            'prestasi' => 'Prestasi',
            'umum' => 'Umum',
        ];

        // ── Berita ────────────────────────────────────────────────
        $beritaQuery = Berita::with('user')->latest();

        if ($request->filled('search')) {
            $beritaQuery->where('judul', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $beritaQuery->where('status', $request->status);
        }
        if ($request->filled('kategori') && $tab === 'berita') {
            $beritaQuery->where('kategori', $request->kategori);
        }

        $beritas     = $beritaQuery->paginate(8, ['*'], 'berita_page')->withQueryString();
        $beritaStats = [
            'total'   => Berita::count(),
            'aktif'   => Berita::where('status', 'aktif')->count(),
            'draf'    => Berita::where('status', 'draf')->count(),
            'penting' => Berita::where('is_penting', true)->count(),
        ];

        // ── Galeri ────────────────────────────────────────────────
        $galeriQuery = Galeri::with('user')->terurut();

        if ($request->filled('galeri_kategori')) {
            $galeriQuery->where('kategori', $request->galeri_kategori);
        }
        if ($request->filled('galeri_tipe')) {
            $galeriQuery->where('tipe', $request->galeri_tipe);
        }
        if ($request->filled('galeri_status')) {
            $galeriQuery->where('status', $request->galeri_status);
        }

        $galeris     = $galeriQuery->paginate(12, ['*'], 'galeri_page')->withQueryString();
        $galeriStats = [
            'total' => Galeri::count(),
            'aktif' => Galeri::where('status', 'aktif')->count(),
            'draf'  => Galeri::where('status', 'draf')->count(),
        ];
        $galeriKategoriOptions = Galeri::kategoriOptions();

        return view('admin.kelola-website.index', compact(
            'tab', 'contents', 'heroMedia', 'kontak', 'stats',
            'beritas', 'beritaStats',
            'galeris', 'galeriStats', 'galeriKategoriOptions', 'tipeOptions',
        ));
    }

    // ══════════════════════════════════════════════════════════════
    // UPDATE HOME — Teks hero, tentang, visi, misi, fasilitas,
    //               sambutan, foto sambutan, info ppdb, kalender
    // ══════════════════════════════════════════════════════════════

    public function updateHome(Request $request)
    {
        $validated = $request->validate([
            // Hero
            'hero_title'             => 'nullable|string|max:255',
            'hero_description'       => 'nullable|string|max:1000',

            // Konten rich-text (Quill)
            'tentang'                => 'nullable|string',
            'visi'                   => 'nullable|string',
            'misi'                   => 'nullable|string',

            // Fasilitas
            'fasilitas_ruang_kelas'  => 'nullable|string|max:500',
            'fasilitas_perpustakaan' => 'nullable|string|max:500',
            'fasilitas_lapangan'     => 'nullable|string|max:500',
            'fasilitas_laboratorium' => 'nullable|string|max:500',

            // Sambutan kepala sekolah
            'sambutan_nama'          => 'nullable|string|max:255',
            'sambutan_jabatan'       => 'nullable|string|max:255',
            'sambutan_teks'          => 'nullable|string',
            'sambutan_foto'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            // Info penting
            'info_ppdb'              => 'nullable|string',
            'info_kalender'          => 'nullable|string',
        ]);

        // ── Simpan field teks biasa ───────────────────────────────
        $simpleKeys = [
            'hero_title',
            'hero_description',
            'fasilitas_ruang_kelas',
            'fasilitas_perpustakaan',
            'fasilitas_lapangan',
            'fasilitas_laboratorium',
            'sambutan_nama',
            'sambutan_jabatan',
            'sambutan_teks',
            'info_ppdb',
            'info_kalender',
        ];

        foreach ($simpleKeys as $key) {
            if ($request->has($key)) {
                PageContent::updateOrCreate(
                    ['key'     => $key],
                    [
                        'value'   => $validated[$key] ?? '',
                        'section' => explode('_', $key)[0] ?? 'general',
                    ]
                );
            }
        }

        // ── Simpan konten rich-text dari Quill ────────────────────
        foreach (['tentang', 'visi', 'misi'] as $key) {
            if ($request->has($key)) {
                PageContent::updateOrCreate(
                    ['key'     => $key],
                    ['value'   => $validated[$key] ?? '', 'section' => $key]
                );
            }
        }

        // ── Upload & simpan foto sambutan ─────────────────────────
        if ($request->hasFile('sambutan_foto')) {
            $oldPath = PageContent::where('key', 'sambutan_foto_path')->first()?->value;
            if ($oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
            $newPath = $request->file('sambutan_foto')->store('sambutan', 'public');
            PageContent::updateOrCreate(
                ['key'     => 'sambutan_foto_path'],
                ['value'   => $newPath, 'section' => 'sambutan']
            );
        }

        return redirect()
            ->route('admin.kelola-website', ['tab' => 'home'])
            ->with('success', 'Konten halaman Beranda berhasil diperbarui.');
    }

    // ══════════════════════════════════════════════════════════════
    // UPDATE HERO MEDIA — Gambar / video / YouTube / slideshow
    // ══════════════════════════════════════════════════════════════

    public function updateHeroMedia(Request $request)
    {
        $tipe = $request->input('hero_media_tipe', 'none');

        $rules = [
            'hero_media_tipe'     => 'required|in:none,image,video,youtube,slideshow',
            'hero_slide_interval' => 'nullable|integer|min:1000|max:30000',
        ];

        if ($tipe === 'image') {
            $rules['hero_files']   = 'nullable|array|max:1';
            $rules['hero_files.*'] = 'image|mimes:jpg,jpeg,png,webp|max:102400';
        } elseif ($tipe === 'video') {
            $rules['hero_files']   = 'nullable|array|max:1';
            $rules['hero_files.*'] = 'mimes:mp4,mov,webm|max:204800';
        } elseif ($tipe === 'youtube') {
            $rules['hero_media_link'] = 'required|url';
        } elseif ($tipe === 'slideshow') {
            $rules['hero_files']   = 'nullable|array|max:10';
            $rules['hero_files.*'] = 'image|mimes:jpg,jpeg,png,webp|max:102400';
        }

        $request->validate($rules);

        $heroMedia = PageContent::firstOrNew(
            ['key'     => 'hero_media_settings'],
            ['section' => 'hero_media']
        );

        // Hapus file lama yang tidak dipertahankan
        $existingFiles = $heroMedia->heroFilesArray ?? [];
        $keepFiles     = $request->input('keep_files', []);

        foreach ($existingFiles as $path) {
            if (!in_array($path, $keepFiles)) {
                Storage::disk('public')->delete($path);
            }
        }

        $filesToKeep = array_values(
            array_filter($existingFiles, fn($p) => in_array($p, $keepFiles))
        );

        $newPaths = [];
        if ($request->hasFile('hero_files')) {
            foreach ($request->file('hero_files') as $file) {
                $folder     = $tipe === 'video' ? 'hero/videos' : 'hero/images';
                $newPaths[] = $file->store($folder, 'public');
            }
        }

        $allFiles = array_merge($filesToKeep, $newPaths);

        $heroMedia->hero_media_tipe     = $tipe;
        $heroMedia->hero_media_files    = $tipe !== 'none' ? json_encode(array_values($allFiles)) : null;
        $heroMedia->hero_media_link     = $tipe === 'youtube' ? $request->input('hero_media_link') : null;
        $heroMedia->hero_slide_interval = (int) $request->input('hero_slide_interval', 4000);
        $heroMedia->value               = 'hero_media_settings';
        $heroMedia->save();

        return redirect()
            ->route('admin.kelola-website', ['tab' => 'home'])
            ->with('success', 'Media hero berhasil diperbarui.');
    }

    // ══════════════════════════════════════════════════════════════
    // DELETE HERO FILE — Hapus satu file dari slideshow/gambar hero
    // ══════════════════════════════════════════════════════════════

    public function deleteHeroFile(Request $request)
    {
        $path      = $request->input('path');
        $heroMedia = PageContent::where('key', 'hero_media_settings')->first();

        if (!$heroMedia || !$path) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        Storage::disk('public')->delete($path);

        $files = array_values(
            array_filter($heroMedia->heroFilesArray, fn($f) => $f !== $path)
        );
        $heroMedia->hero_media_files = json_encode($files);
        $heroMedia->save();

        return back()->with('success', 'File berhasil dihapus.');
    }

    // ══════════════════════════════════════════════════════════════
    // UPDATE STATS — Angka statistik sekolah
    // ══════════════════════════════════════════════════════════════

    public function updateStats(Request $request)
    {
        $validated = $request->validate([
            'stat_siswa'    => 'nullable|string|max:50',
            'stat_guru'     => 'nullable|string|max:50',
            'stat_prestasi' => 'nullable|string|max:50',
            'stat_ekskul'   => 'nullable|string|max:50',
        ]);

        $row = PageContent::firstOrNew(
            ['key'     => 'stats_settings'],
            ['section' => 'stats']
        );

        $row->fill($validated);
        $row->value = 'stats_settings';
        $row->save();

        return redirect()
            ->route('admin.kelola-website', ['tab' => 'home'])
            ->with('success', 'Statistik sekolah berhasil diperbarui.');
    }

    // ══════════════════════════════════════════════════════════════
    // UPDATE KONTAK — Alamat, telepon, email, maps embed, sosmed
    // ══════════════════════════════════════════════════════════════

    public function updateKontak(Request $request)
    {
        $validated = $request->validate([
            'kontak_telepon'    => 'nullable|string|max:30',
            'kontak_email'      => 'nullable|email|max:100',
            'kontak_alamat'     => 'nullable|string|max:500',
            // maps embed bisa berisi URL panjang dengan parameter & dst.
            // Tidak pakai 'url' validation karena embed URL Google Maps
            // sering mengandung format non-standar.
            'kontak_maps_embed' => 'nullable|string|max:5000',
            'sosmed_instagram'  => ['nullable', 'url', 'max:255'],
            'sosmed_facebook'   => ['nullable', 'url', 'max:255'],
            'sosmed_youtube'    => ['nullable', 'url', 'max:255'],
            'sosmed_twitter'    => ['nullable', 'url', 'max:255'],
        ]);

        // ── Bersihkan URL embed peta dari encoding HTML jika ada ──
        // Kadang browser atau paste action meng-encode & menjadi &amp;
        if (!empty($validated['kontak_maps_embed'])) {
            $validated['kontak_maps_embed'] = html_entity_decode(
                $validated['kontak_maps_embed'],
                ENT_QUOTES | ENT_HTML5,
                'UTF-8'
            );
        }

        $row = PageContent::firstOrNew(
            ['key'     => 'kontak_settings'],
            ['section' => 'kontak']
        );

        $row->fill($validated);
        $row->value = 'kontak_settings';
        $row->save();

        return redirect()
            ->route('admin.kelola-website', ['tab' => 'kontak'])
            ->with('success', 'Kontak & media sosial berhasil diperbarui.');
    }

    // ══════════════════════════════════════════════════════════════
    // UPDATE SCHOOL SETTINGS — Logo, favicon, nama sekolah, tagline
    // ══════════════════════════════════════════════════════════════

    public function updateSchoolSettings(Request $request)
    {
        $request->validate([
            'nama_sekolah'   => 'nullable|string|max:255',
            'singkatan'      => 'nullable|string|max:100',
            'tagline_footer' => 'nullable|string|max:500',
            'logo'           => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'favicon'        => 'nullable|mimes:png,jpg,jpeg,ico|max:512',
        ]);

        // Simpan data teks identitas
        if ($request->filled('nama_sekolah')) {
            SchoolSetting::set('nama_sekolah', $request->nama_sekolah);
        }
        if ($request->filled('singkatan')) {
            SchoolSetting::set('singkatan', $request->singkatan);
        }
        if ($request->filled('tagline_footer')) {
            SchoolSetting::set('tagline_footer', $request->tagline_footer);
        }

        // Upload logo
        if ($request->hasFile('logo')) {
            $oldLogo = SchoolSetting::get('logo_path');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
            SchoolSetting::set('logo_path', $request->file('logo')->store('school', 'public'));
        }

        // Upload favicon
        if ($request->hasFile('favicon')) {
            $oldFavicon = SchoolSetting::get('favicon_path');
            if ($oldFavicon) {
                Storage::disk('public')->delete($oldFavicon);
            }
            SchoolSetting::set('favicon_path', $request->file('favicon')->store('school', 'public'));
        }

        return redirect()
            ->route('admin.kelola-website', ['tab' => 'identitas'])
            ->with('success', 'Identitas sekolah berhasil diperbarui.');
    }

    // ══════════════════════════════════════════════════════════════
    // DELETE SAMBUTAN FOTO — Hapus foto kepala sekolah
    // ══════════════════════════════════════════════════════════════

    public function deleteSambutanFoto(Request $request)
    {
        $record = PageContent::where('key', 'sambutan_foto_path')->first();

        if (!$record || !$record->value) {
            return back()->with('error', 'Foto tidak ditemukan.');
        }

        Storage::disk('public')->delete($record->value);
        $record->update(['value' => null]);

        return back()->with('success', 'Foto sambutan berhasil dihapus.');
    }

    // ══════════════════════════════════════════════════════════════
    // DELETE LOGO — Hapus logo sekolah
    // ══════════════════════════════════════════════════════════════

    public function deleteLogo(Request $request)
    {
        $logoPath = SchoolSetting::get('logo_path');

        if (!$logoPath) {
            return back()->with('error', 'Logo tidak ditemukan.');
        }

        Storage::disk('public')->delete($logoPath);
        SchoolSetting::set('logo_path', null);

        return back()->with('success', 'Logo sekolah berhasil dihapus.');
    }
}