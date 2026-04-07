<script>
(function () {
    'use strict';

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

    window.wdgTutup = function (role) {
        var overlay = document.getElementById('wdgModal-' + (role || 'guru'));
        if (!overlay) return;
        overlay.classList.add('hidden');
        overlay.classList.remove('flex');
        document.body.style.overflow = '';
    };

    document.addEventListener('keydown', function (ev) {
        if (ev.key === 'Escape') wdgTutup('guru');
    });

    // Di file scripts Anda, tambahkan:
    chart: {
        fontFamily: "'Plus Jakarta Sans', sans-serif",
        type: 'area',
        height: 240,
        toolbar: { show: false }
    },
    title: { style: { fontSize: '14px' } },
    xaxis: { labels: { style: { fontSize: '11px' } } },
    yaxis: { labels: { style: { fontSize: '11px' } } },

    // Fungsi wdgHtml, e(), s() tetap sama seperti kode asli Anda
    // Paste seluruh function wdgHtml, e, s di sini...
})();
</script>