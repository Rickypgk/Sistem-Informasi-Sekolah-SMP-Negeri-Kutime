<div class="relative overflow-hidden rounded-2xl px-6 py-6 text-white shadow-lg"
     style="background: linear-gradient(135deg, #6d28d9 0%, #4f46e5 60%, #4338ca 100%);">

    <div class="absolute inset-0 opacity-[0.06]" aria-hidden="true">
        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="g-grid" width="24" height="24" patternUnits="userSpaceOnUse">
                    <path d="M 24 0 L 0 0 0 24" fill="none" stroke="white" stroke-width="1"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#g-grid)"/>
        </svg>
    </div>

    <div class="absolute -right-8 -top-8 w-40 h-40 rounded-full bg-white/10" aria-hidden="true"></div>

    <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold leading-tight">
                Selamat datang, {{ auth()->user()->name ?? 'Guru' }}! 👋
            </h2>
            <p class="text-violet-200 text-sm mt-1">
                {{ now()->isoFormat('dddd, D MMMM Y') }}
            </p>
        </div>
        <div class="shrink-0 rounded-xl px-4 py-3 text-center border bg-white/15 border-white/20">
            <p class="text-lg font-bold leading-none">{{ now()->isoFormat('D MMM') }}</p>
            <p class="text-violet-200 text-xs mt-1">{{ now()->isoFormat('Y') }}</p>
        </div>
    </div>
</div>