
<script>
(function(){
    'use strict';

    window.wdgBuka = function(d) {
        var role    = d.widgetRole || 'guru';
        var konten  = document.getElementById('wdgModalKonten-' + role);
        var overlay = document.getElementById('wdgModal-' + role);
        if (!konten || !overlay) return;
        konten.innerHTML = wdgHtml(d);
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
        document.body.style.overflow = 'hidden';
    };

    window.wdgTutup = function(role) {
        var overlay = document.getElementById('wdgModal-' + (role || 'guru'));
        if (!overlay) return;
        overlay.classList.add('hidden');
        overlay.classList.remove('flex');
        document.body.style.overflow = '';
    };

    document.addEventListener('keydown', function(ev) {
        if (ev.key === 'Escape') wdgTutup('guru');
    });

    function e(str) {
        return String(str || '')
            .replace(/&/g,'&amp;')
            .replace(/</g,'&lt;')
            .replace(/>/g,'&gt;')
            .replace(/"/g,'&quot;');
    }

    function s(str) {
        return String(str || '').replace(/<[^>]*>/g, '');
    }

    function catStyle(kat) {
        var map = {
            'penting' : { bg:'#fee2e2', color:'#b91c1c' },
            'info'    : { bg:'#e0f2fe', color:'#0369a1' },
            'umum'    : { bg:'#f8fafc', color:'#475569' },
        };
        return map[String(kat).toLowerCase()] || { bg:'#eef2ff', color:'#4f46e5' };
    }

    function wdgHtml(d) {
        var cs = catStyle(d.kategori);
        var tgl = d.tanggal ? String(d.tanggal).substring(0, 10) : '';
        return [
            '<div style="margin-bottom:10px;display:flex;align-items:center;gap:8px;flex-wrap:wrap;">',
                '<span style="padding:3px 10px;border-radius:6px;font-size:.62rem;font-weight:700;',
                    'background:', cs.bg, ';color:', cs.color, ';">',
                    e(d.kategori || 'Umum'),
                '</span>',
                tgl ? '<span style="font-size:.62rem;color:#94a3b8;">' + e(tgl) + '</span>' : '',
            '</div>',
            '<h3 style="font-size:.9rem;font-weight:800;color:#1e293b;margin:0 0 10px;line-height:1.3;">',
                e(d.judul),
            '</h3>',
            '<div style="font-size:.75rem;color:#374151;line-height:1.7;white-space:pre-wrap;">',
                e(s(d.isi)),
            '</div>',
        ].join('');
    }

})();
</script><?php /**PATH C:\PA 3\smpn-kutime\resources\views/guru/dashboard/scripts-modal.blade.php ENDPATH**/ ?>