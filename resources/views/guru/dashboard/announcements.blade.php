{{-- resources/views/guru/dashboard/announcements.blade.php --}}
@php
    $widgetPengumuman = $widgetPengumuman ?? collect();
@endphp

<div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">

    <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span style="font-size:1rem;">📢</span>
            <p class="font-semibold text-slate-800 dark:text-slate-100" style="font-size:.8rem;">
                Pengumuman
            </p>
        </div>
        @if($widgetPengumuman->count() > 0)
            <span style="font-size:.55rem;font-weight:700;background:#eef2ff;color:#4f46e5;
                         border:1px solid #c7d2fe;border-radius:99px;padding:2px 7px;">
                {{ $widgetPengumuman->count() }}
            </span>
        @endif
    </div>

    @if($widgetPengumuman->isEmpty())
        <div style="padding:32px 16px;text-align:center;color:#94a3b8;">
            <p style="font-size:1.5rem;margin-bottom:6px;">📭</p>
            <p style="font-size:.7rem;">Belum ada pengumuman</p>
        </div>
    @else
        <div>
            @foreach($widgetPengumuman as $p)
            @php
                $catColors = [
                    'penting'  => ['bg'=>'#fee2e2','color'=>'#b91c1c','label'=>'Penting'],
                    'info'     => ['bg'=>'#e0f2fe','color'=>'#0369a1','label'=>'Info'],
                    'umum'     => ['bg'=>'#f8fafc','color'=>'#475569','label'=>'Umum'],
                    'default'  => ['bg'=>'#eef2ff','color'=>'#4f46e5','label'=>'Umum'],
                ];
                $cat = $catColors[$p->kategori ?? 'default'] ?? $catColors['default'];
            @endphp
            <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 cursor-pointer
                         hover:bg-slate-50 dark:hover:bg-slate-700/20 transition-colors"
                 onclick="wdgBuka({{ json_encode([
                     'judul'       => $p->judul,
                     'isi'         => $p->isi,
                     'tanggal'     => $p->tanggal_tayang ?? $p->created_at,
                     'kategori'    => $p->kategori ?? 'Umum',
                     'widgetRole'  => 'guru',
                 ]) }})">

                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:6px;margin-bottom:4px;">
                    <p style="font-size:.72rem;font-weight:700;color:#1e293b;line-height:1.3;flex:1;">
                        {{ Str::limit($p->judul, 52) }}
                    </p>
                    <span style="flex-shrink:0;padding:2px 7px;border-radius:5px;font-size:.55rem;
                                  font-weight:700;background:{{ $cat['bg'] }};color:{{ $cat['color'] }};">
                        {{ $cat['label'] }}
                    </span>
                </div>

                <p style="font-size:.62rem;color:#64748b;line-height:1.4;margin-bottom:4px;">
                    {{ Str::limit(strip_tags($p->isi), 80) }}
                </p>

                <p style="font-size:.58rem;color:#94a3b8;">
                    {{ \Carbon\Carbon::parse($p->tanggal_tayang ?? $p->created_at)->isoFormat('D MMM Y') }}
                </p>

            </div>
            @endforeach
        </div>

        <div style="padding:8px 14px;border-top:1px solid #f1f5f9;background:#f8fafc;text-align:center;">
            <a href="{{ route('guru.pengumuman') }}"
               style="font-size:.6rem;font-weight:700;color:#4f46e5;text-decoration:none;">
                Lihat semua pengumuman →
            </a>
        </div>
    @endif

</div>