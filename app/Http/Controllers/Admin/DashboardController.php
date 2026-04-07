<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;

class DashboardController extends Controller
{
    public function index()
    {
        /*
         * ─────────────────────────────────────────────────────────
         * TAMBAHAN: ambil pengumuman untuk widget dashboard admin.
         * Admin melihat pengumuman untuk semua target audience.
         * ─────────────────────────────────────────────────────────
         */
        $widgetPengumuman = Pengumuman::where('is_active', 1)
            ->where('show_di_dashboard', 1)
            ->whereIn('target_audience', ['guru', 'siswa', 'semua'])
            ->latest()
            ->limit(5)
            ->get();

        /*
         * Tambahkan $widgetPengumuman ke array compact() atau
         * ke array yang Anda return ke view.
         *
         * Jika sebelumnya return view('admin.dashboard'):
         *   return view('admin.dashboard', compact('widgetPengumuman'));
         *
         * Jika sudah ada variabel lain, tambahkan saja:
         *   return view('admin.dashboard', compact('widgetPengumuman', 'varLain1', 'varLain2'));
         */
        return view('admin.dashboard', compact('widgetPengumuman'));
    }
}