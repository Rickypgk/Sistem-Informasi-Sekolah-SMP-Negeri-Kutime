{{-- resources/views/guru/dashboard/scripts-modal.blade.php --}}
<script>
(function () {
    'use strict';

    /* ── Buka modal ── */
    window.wdgBuka = function (d) {
        var role    = d.widgetRole || 'guru';
        var konten  = document.getElementById('wdgModalKonten-' + role);
        var overlay = document.getElementById('wdgModal-' + role);
        if (!konten || !overlay) return;
        konten.innerHTML = wdgHtml(d);
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
        document.body.style.overflow = 'hidden';
    };

    /* ── Tutup modal ── */
    window.wdgTutup = function (role) {
        var overlay = document.getElementById('wdgModal-' + (role || 'guru'));
        if (!overlay) return;
        overlay.classList.add('hidden');
        overlay.classList.remove('flex');
        document.body.style.overflow = '';
    };

    /* ── Tutup dengan ESC ── */
    document.addEventListener('keydown', function (ev) {
        if (ev.key === 'Escape') wdgTutup('guru');
    });

    /* ── Escape HTML ── */
    function e(str) {
        return String(str || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    /* ── Strip HTML tags ── */
    function stripHtml(str) {
        return String(str || '').replace(/<[^>]*>/g, '');
    }

    /* ── Style badge kategori ── */
    function catStyle(kat) {
        var map = {
            'penting'  : { bg: '#fee2e2', color: '#b91c1c' },
            'info'     : { bg: '#e0f2fe', color: '#0369a1' },
            'umum'     : { bg: '#f8fafc', color: '#475569' },
            'kegiatan' : { bg: '#fef9c3', color: '#a16207' },
            'libur'    : { bg: '#ecfdf5', color: '#059669' },
        };
        return map[String(kat).toLowerCase()] || { bg: '#eef2ff', color: '#4f46e5' };
    }

    /* ── Format tanggal sederhana ── */
    function fmtTgl(str) {
        if (!str) return '';
        var d = new Date(String(str).substring(0, 10));
        if (isNaN(d)) return str;
        var bln = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        return d.getDate() + ' ' + bln[d.getMonth()] + ' ' + d.getFullYear();
    }

    /* ── Render HTML modal ── */
    function wdgHtml(d) {
        var cs    = catStyle(d.kategori);
        var parts = [];

        // ── Gambar (jika ada) ──
        if (d.gambarUrl) {
            parts.push(
                '<div style="width:100%;overflow:hidden;background:#f1f5f9;max-height:240px;">',
                    '<img src="' + e(d.gambarUrl) + '"',
                         'alt="' + e(d.judul) + '"',
                         'style="width:100%;max-height:240px;object-fit:cover;display:block;"',
                         'onerror="this.parentElement.style.display=\'none\'">',
                '</div>'
            );
        }

        // ── Body konten ──
        parts.push('<div style="padding:16px;">');

        // Kategori + tanggal
        parts.push(
            '<div style="display:flex;align-items:center;justify-content:space-between;',
                         'gap:8px;margin-bottom:10px;flex-wrap:wrap;">',
                '<span style="padding:3px 10px;border-radius:6px;font-size:.62rem;font-weight:700;',
                              'background:' + cs.bg + ';color:' + cs.color + ';">',
                    e(d.kategori || 'Umum'),
                '</span>',
                d.tanggal
                    ? '<span style="font-size:.6rem;color:#94a3b8;">' + fmtTgl(d.tanggal) + '</span>'
                    : '',
            '</div>'
        );

        // Judul
        parts.push(
            '<h3 style="font-size:.95rem;font-weight:800;color:#1e293b;',
                         'margin:0 0 12px;line-height:1.35;">',
                e(d.judul),
            '</h3>'
        );

        // Isi pengumuman
        // Jika isi mengandung HTML (dari rich editor), tampilkan dengan innerHTML aman
        // Jika plain text, tampilkan dengan white-space:pre-wrap
        var isiContent = String(d.isi || '');
        var isHtml = /<[a-z][\s\S]*>/i.test(isiContent);

        if (isHtml) {
            // Render HTML tapi strip script/iframe berbahaya
            var safeIsi = isiContent
                .replace(/<script[\s\S]*?<\/script>/gi, '')
                .replace(/<iframe[\s\S]*?<\/iframe>/gi, '');
            parts.push(
                '<div style="font-size:.75rem;color:#374151;line-height:1.75;',
                             'word-break:break-word;">',
                    safeIsi,
                '</div>'
            );
        } else {
            parts.push(
                '<div style="font-size:.75rem;color:#374151;line-height:1.75;',
                             'white-space:pre-wrap;word-break:break-word;">',
                    e(stripHtml(isiContent)),
                '</div>'
            );
        }

        // Footer info
        parts.push(
            '<div style="margin-top:14px;padding-top:10px;border-top:1px solid #f1f5f9;',
                          'display:flex;align-items:center;gap:6px;">',
                d.gambarUrl
                    ? '<span style="font-size:.58rem;color:#94a3b8;display:flex;align-items:center;gap:3px;">🖼 Termasuk gambar</span>'
                    : '',
            '</div>'
        );

        parts.push('</div>'); // tutup body konten

        return parts.join('');
    }

})();
</script>