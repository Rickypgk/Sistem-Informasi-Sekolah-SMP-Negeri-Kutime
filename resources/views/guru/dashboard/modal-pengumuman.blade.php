{{-- resources/views/guru/dashboard/modal-pengumuman.blade.php --}}
<div id="wdgModal-guru"
     class="hidden fixed inset-0 z-[300] items-center justify-center p-4"
     style="background:rgba(0,0,0,.45);"
     onclick="if(event.target===this)wdgTutup('guru')">

    <div style="background:#fff;border-radius:14px;width:100%;max-width:440px;
                max-height:88vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.2);">

        <div style="display:flex;align-items:center;justify-content:space-between;
                     padding:14px 16px;border-bottom:1px solid #f1f5f9;position:sticky;top:0;background:#fff;z-index:1;">
            <p style="font-size:.85rem;font-weight:700;color:#1e293b;margin:0;">📢 Pengumuman</p>
            <button onclick="wdgTutup('guru')"
                    style="width:26px;height:26px;border:none;background:#f1f5f9;border-radius:7px;
                           cursor:pointer;font-size:.85rem;color:#64748b;display:flex;
                           align-items:center;justify-content:center;line-height:1;">
                ✕
            </button>
        </div>

        <div id="wdgModalKonten-guru" style="padding:16px;font-size:.75rem;color:#374151;line-height:1.6;">
        </div>

    </div>
</div>