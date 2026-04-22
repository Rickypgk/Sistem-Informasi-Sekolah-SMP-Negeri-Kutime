<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;

class DashboardController extends Controller
{
    public function index()
    {
        $widgetPengumuman = Pengumuman::where('is_active', 1)
            ->where('show_di_dashboard', 1)
            ->whereIn('target_audience', ['siswa', 'semua'])
            ->latest()
            ->limit(4)
            ->get();

        return view('siswa.dashboard', compact('widgetPengumuman'));
    }
}