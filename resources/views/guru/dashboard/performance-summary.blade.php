<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

    <!-- Total Siswa -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-2xl">
                👨‍🎓
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800 dark:text-slate-100">
                    {{ $totalSiswa ?? 0 }}
                </p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    Total Siswa
                </p>
            </div>
        </div>
    </div>

    <!-- Kehadiran -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-2xl">
                ✅
            </div>
            <div>
                <p class="text-2xl font-bold text-green-700 dark:text-green-400">
                    {{ number_format($kehadiranPct ?? 0, 1) }}%
                </p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    Kehadiran Bulan Ini
                </p>
            </div>
        </div>
    </div>

    <!-- Rata-rata Nilai -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-2xl">
                📊
            </div>
            <div>
                <p class="text-2xl font-bold text-blue-700 dark:text-blue-400">
                    {{ number_format($rataNilai ?? 0, 1) }}
                </p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    Rata-rata Nilai
                </p>
            </div>
        </div>
    </div>

    <!-- Siswa Berisiko -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-2xl">
                ⚠️
            </div>
            <div>
                <p class="text-2xl font-bold text-red-700 dark:text-red-400">
                    {{ $siswaRisiko ?? 0 }}
                </p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    Butuh Perhatian
                </p>
            </div>
        </div>
    </div>

</div>