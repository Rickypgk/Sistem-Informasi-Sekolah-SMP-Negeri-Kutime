<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;

class PublicBeritaController extends Controller
{
    public function index(Request $request)
    {
        // Pengumuman penting (maks. 3, tampil di atas)
        $pengumuman = Berita::aktif()
            ->pengumuman()
            ->penting()
            ->orderBy('tanggal_publish', 'desc')
            ->take(3)
            ->get();

        // Query berita dengan filter
        $query = Berita::aktif()->orderBy('tanggal_publish', 'desc');

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('cari')) {
            $query->where('judul', 'like', '%' . $request->cari . '%');
        }

        $beritas = $query->paginate(8)->withQueryString();

        return view('website.berita', compact('pengumuman', 'beritas'));
    }

    public function show(string $slug)
    {
        $berita = Berita::aktif()
            ->where('slug', $slug)
            ->firstOrFail();

        // Berita terkait – kategori sama, prioritaskan yang punya media
        $terkait = Berita::aktif()
            ->where('kategori', $berita->kategori)
            ->where('id', '!=', $berita->id)
            ->orderByRaw("CASE WHEN media_tipe != 'none' AND media_tipe IS NOT NULL THEN 0 ELSE 1 END")
            ->orderBy('tanggal_publish', 'desc')
            ->take(3)
            ->get();

        return view('website.berita-detail', compact('berita', 'terkait'));
    }
}