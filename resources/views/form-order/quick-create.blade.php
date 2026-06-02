<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Form Order — Scan</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #fff5f5; /* soft rose background */
            --surface:   #ffffff;
            --panel:     #fff7f7; /* very light rose panel */
            --border:    #fde2e2; /* pale red border */
            --accent:    #ef4444; /* primary red */
            --accent-dk: #dc2626; /* darker red */
            --accent-lt: #fff1f1; /* light red/rose */
            --success:   #16a34a; /* keep success green for positive actions */
            --success-dk:#15803d;
            --text:      #1e293b;
            --muted:     #6b7280; /* neutral muted gray */
            --radius:    12px;
            --shadow-lg: 0 8px 32px rgba(239,68,68,.08);
        }

        body {
            background: var(--bg);
            font-family: 'Sora', sans-serif;
            color: var(--text);
            min-height: 100vh;
        }

        /* ── Page wrapper ── */
        .page { padding: 1.25rem 1rem 3rem; }

        .card {
            background: var(--surface);
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            padding: 1.75rem;
            max-width: 1100px;
            margin: 0 auto;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 1.5rem;
        }

        /* ── Two-column grid ── */
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        @media (max-width: 860px) {
            .grid-2 { grid-template-columns: 1fr; }
            .card { padding: 1rem; border-radius: 14px; }
            .page { padding: .75rem .5rem 2rem; }
        }

        /* ── Panel ── */
        .panel {
            background: var(--panel);
            border: 1.5px solid var(--border);
            border-radius: 14px;
            padding: 1.25rem;
        }

        .section-label {
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 1rem;
        }

        /* ── Scanner ── */
        .scanner-box {
            position: relative;
            width: 100%;
            background: #0f172a;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: .75rem;
            /* KUNCI: fixed aspect ratio, tidak bergantung pada library */
            aspect-ratio: 4 / 3;
        }

        .scanner-box video {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .scanner-box canvas {
            display: none;
        }

        /* Overlay crosshair */
        .scanner-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }

        .scanner-frame {
            width: 55%;
            aspect-ratio: 1;
            position: relative;
        }

        .scanner-frame::before,
        .scanner-frame::after,
        .scanner-frame > span::before,
        .scanner-frame > span::after {
            content: '';
            position: absolute;
            width: 22px;
            height: 22px;
            border-color: #4ade80;
            border-style: solid;
        }

        .scanner-frame::before  { top: 0;    left: 0;  border-width: 3px 0 0 3px; }
        .scanner-frame::after   { top: 0;    right: 0; border-width: 3px 3px 0 0; }
        .scanner-frame > span::before { bottom: 0; left: 0;  border-width: 0 0 3px 3px; }
        .scanner-frame > span::after  { bottom: 0; right: 0; border-width: 0 3px 3px 0; }

        /* Scan line */
        .scan-line {
            position: absolute;
            left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, #4ade80, transparent);
            animation: scanMove 2s ease-in-out infinite;
            top: 0;
        }

        @keyframes scanMove {
            0%   { top: 10%; }
            50%  { top: 88%; }
            100% { top: 10%; }
        }

        .scanner-status {
            text-align: center;
            font-size: .82rem;
            font-weight: 500;
            color: var(--muted);
            margin-top: .5rem;
            min-height: 1.4em;
            padding: 0 .5rem;
        }

        .scanner-status.ok  { color: var(--success); }
        .scanner-status.err { color: #ef4444; }

        /* ── Field ── */
        .field { margin-bottom: .9rem; }

        .label {
            display: block;
            font-size: .74rem;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: .3rem;
            letter-spacing: .03em;
        }

        .input {
            width: 100%;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            padding: .6rem .85rem;
            font-size: .9rem;
            font-family: 'Sora', sans-serif;
            color: var(--text);
            background: #fff;
            transition: border-color .18s, box-shadow .18s;
            outline: none;
            /* Mencegah zoom di iOS */
            font-size: 16px;
        }

        .input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(67,97,238,.12);
        }

        .input[readonly] {
            background: var(--panel);
            color: var(--muted);
            cursor: default;
        }

        .input-row {
            display: flex;
            gap: .5rem;
        }

        .input-row .input { flex: 1; min-width: 0; }

        /* ── Buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .35rem;
            border: none;
            border-radius: 8px;
            padding: .6rem 1rem;
            font-family: 'Sora', sans-serif;
            font-size: .84rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform .12s, background .15s;
            white-space: nowrap;
            min-height: 44px;
        }

        .btn:active { transform: scale(.96); }

        .btn-primary {
            background: var(--accent);
            color: #fff;
        }

        .btn-primary:hover { background: var(--accent-dk); }

        .btn-success {
            background: var(--success);
            color: #fff;
        }

        .btn-success:hover { background: var(--success-dk); }

        .btn-outline {
            background: #fff;
            color: var(--muted);
            border: 1.5px solid var(--border);
        }

        .btn-outline:hover { border-color: var(--accent); color: var(--accent); }

        .btn-danger {
            background: #fff;
            color: #ef4444;
            border: 1.5px solid #fca5a5;
            font-size: .75rem;
            padding: .3rem .65rem;
            min-height: unset;
        }

        .btn-danger:hover { background: #fef2f2; }

        .btn-full { width: 100%; }

        /* ── Divider ── */
        .divider { border: none; border-top: 1.5px solid var(--border); margin: 1.25rem 0; }

        /* ── Paket rows ── */
        .paket-list { display: flex; flex-direction: column; gap: .6rem; }

        .paket-row {
            display: flex;
            align-items: center;
            gap: .65rem;
            background: var(--panel);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: .6rem .85rem;
        }

        .paket-name { flex: 1; font-size: .85rem; font-weight: 500; }

        .paket-badge {
            font-size: .7rem;
            font-weight: 600;
            background: var(--accent-lt);
            color: var(--accent);
            border-radius: 20px;
            padding: .15rem .5rem;
            white-space: nowrap;
        }

        .paket-qty {
            width: 5.5rem;
            text-align: center;
            border: 1.5px solid var(--border);
            border-radius: 7px;
            padding: .4rem .5rem;
            font-size: 16px;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 500;
            color: var(--text);
            background: #fff;
            outline: none;
            -moz-appearance: textfield;
        }

        .paket-qty::-webkit-outer-spin-button,
        .paket-qty::-webkit-inner-spin-button { -webkit-appearance: none; }

        .paket-qty:focus { border-color: var(--accent); }

        @media (max-width: 480px) {
            .paket-row { flex-wrap: wrap; }
            .paket-qty { width: 100%; }
        }

        /* ── Signature ── */
        .ttd-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: .85rem;
            margin-top: 1rem;
        }

        @media (max-width: 640px) {
            .ttd-grid { grid-template-columns: 1fr; }
        }

        .ttd-box {
            background: var(--panel);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: .85rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .5rem;
        }

        .ttd-box.invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239,68,68,.12);
        }

        .ttd-label {
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--accent);
        }

        .ttd-canvas-wrap {
            position: relative;
            width: 100%;
            border: 1.5px dashed var(--border);
            border-radius: 8px;
            background: #fff;
            overflow: hidden;
            touch-action: none;
        }

        .ttd-canvas-wrap canvas {
            display: block;
            width: 100%;
            height: 120px;
            cursor: crosshair;
            touch-action: none;
        }

        .ttd-placeholder {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            color: #c8d3f0;
            font-size: .75rem;
            font-weight: 500;
            transition: opacity .2s;
        }

        .ttd-canvas-wrap.has-sig .ttd-placeholder { opacity: 0; }

        /* ── Footer ── */
        .form-footer {
            display: flex;
            justify-content: flex-end;
            gap: .75rem;
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1.5px solid var(--border);
            flex-wrap: wrap;
        }

        @media (max-width: 480px) {
            .form-footer { flex-direction: column; }
            .form-footer .btn { width: 100%; }
        }

        /* ── Toast Alert ── */
        .toast {
            display: none;
            position: fixed;
            top: 1.25rem;
            right: 1.25rem;
            background: var(--surface);
            border-left: 4px solid var(--success);
            border-radius: var(--radius);
            padding: 1.25rem 1rem;
            max-width: 400px;
            width: calc(100% - 2.5rem);
            box-shadow: var(--shadow-lg);
            z-index: 9999;
            animation: slideIn .3s ease-out;
        }

        .toast.show { display: block; }

        @keyframes slideIn {
            from { transform: translateX(420px); opacity: 0; }
            to   { transform: translateX(0); opacity: 1; }
        }

        .toast-title { font-size: .9rem; font-weight: 700; color: var(--success); margin-bottom: .5rem; }
        .toast-body  { font-size: .82rem; color: var(--text); line-height: 1.5; }

        .toast-detail {
            background: var(--accent-lt);
            border-radius: 8px;
            padding: .65rem;
            margin-top: .65rem;
            font-size: .78rem;
        }

        .toast-detail strong { display: block; color: var(--accent); font-weight: 700; margin-bottom: .25rem; }

        .toast-close {
            position: absolute;
            top: .6rem; right: .6rem;
            background: none; border: none;
            cursor: pointer; color: var(--muted);
            font-size: 1.1rem; line-height: 1;
        }

        .toast-close:hover { color: var(--text); }

        /* ── Utility ── */
        .mt-sm { margin-top: .6rem; }
    </style>
</head>
<body>

<!-- Toast -->
<div id="toast" class="toast">
    <button class="toast-close" onclick="closeToast()">&times;</button>
    <p class="toast-title">✔ Berhasil!</p>
    <p class="toast-body" id="toastMsg"></p>
    <div class="toast-detail">
        <strong id="toastKodeLabel">Kode Unik:</strong>
        <div id="toastKode"></div>
        <div style="margin-top:.4rem;">
            <strong style="display:inline;font-weight:600;">Jumlah Voucher:</strong>
            <span id="toastVoucher" style="margin-left:.3rem;"></span> voucher
        </div>
    </div>
</div>

<div class="page">
    <div class="card">
        <h2 class="card-title">📦 Form Order — Scan</h2>

        <div class="grid-2">

            <!-- ════════ KIRI: Scanner & Info Toko ════════ -->
            <div class="panel">
                <p class="section-label">📷 Scan Toko</p>

                <!-- Scanner murni HTML — tidak pakai library apapun -->
                <div class="scanner-box">
                    <video id="scanVideo" playsinline autoplay muted></video>
                    <canvas id="scanCanvas"></canvas>
                    <div class="scanner-overlay">
                        <div class="scanner-frame">
                            <span></span>
                            <div class="scan-line"></div>
                        </div>
                    </div>
                </div>

                <div style="display:flex; gap:.5rem; align-items:center; margin-bottom:.5rem;">
                    <p id="scanStatus" class="scanner-status" style="flex:1; margin:0;">Mengaktifkan kamera…</p>
                    <button type="button" id="btnFlip" class="btn btn-outline" style="padding:.4rem .7rem; min-height:unset; font-size:.8rem;">
                        🔄 Balik
                    </button>
                </div>

                <button type="button" id="btnScanUlang" class="btn btn-outline btn-full mt-sm">
                    ⟳ Scan Ulang
                </button>

                <div style="margin-top:1rem;">
                    <div class="field">
                        <label class="label">Kode Toko</label>
                        <div class="input-row">
                            <input type="text" id="kode_toko_input" class="input" placeholder="Scan atau masukkan kode toko" autocomplete="off">
                            <button type="button" id="btnLookupToko" class="btn btn-primary">🔍 Cari</button>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Lokasi Event</label>
                        <input type="text" id="lokasi_event" readonly class="input" value="{{ $defaultLokasi->nama_lokasi ?? '' }}">
                    </div>

                    <div class="field">
                        <label class="label">PIC</label>
                        <input type="text" id="pic" readonly class="input">
                    </div>

                    <div class="field">
                        <label class="label">No. HP</label>
                        <input type="text" id="no_hp" readonly class="input">
                    </div>

                    <div class="field">
                        <label class="label">Kota / Kabupaten</label>
                        <input type="text" id="kota" readonly class="input">
                    </div>
                </div>
            </div>

            <!-- ════════ KANAN: Form Order ════════ -->
            <div class="panel">
                <p class="section-label">🗒️ Detail Order</p>

                <form id="scanForm" action="{{ route('form-order.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="source" value="quick-scan">
                    <div class="field">
                        <label class="label">Kode Agen</label>
                        <div class="input-row">
                            <input type="text" id="kode_agen_manual_input" class="input" placeholder="Masukkan kode agen lalu klik Cari" autocomplete="off">
                            <button type="button" id="btnLookupAgen" class="btn btn-primary">🔍 Cari</button>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Kode Agen Terpilih</label>
                        <input type="text" id="kode_agen_input" name="kode_agen_input" readonly class="input" placeholder="Belum ada agen dipilih">
                    </div>

                    <div class="field">
                        <label class="label">Nama Agen</label>
                        <input type="text" id="nama_agen" readonly class="input">
                    </div>

                    <div class="field">
                        <label class="label">Brand</label>
                        <input type="text" id="brand" name="brand" readonly class="input">
                    </div>

                    <div class="field">
                        <label class="label">Nama Sales</label>
                        <input type="text" id="nama_sales" name="nama_sales" class="input">
                    </div>

                    <hr class="divider">
                    <p class="section-label">📦 Pilih Paket</p>

                    <div class="paket-list">
                        @foreach($masterTargets as $target)
                        <div class="paket-row">
                            <span class="paket-name">{{ $target->target }}</span>
                            <span class="paket-badge">{{ $target->point }} pt</span>
                            <input
                                type="number"
                                min="0"
                                name="targets[{{ $loop->index }}][jumlah_pengambilan]"
                                id="jumlah_{{ $target->id }}"
                                data-point="{{ $target->point }}"
                                data-kupon="{{ $target->kupon ?? 0 }}"
                                value="0"
                                class="paket-qty"
                            >
                            <input type="hidden" name="targets[{{ $loop->index }}][master_target_id]" value="{{ $target->id }}">
                        </div>
                        @endforeach
                    </div>

                    <!-- Tanda Tangan -->
                    <hr class="divider">
                    <p class="section-label">✍️ Tanda Tangan</p>

                    <div class="ttd-grid">
                        <div class="ttd-box">
                            <span class="ttd-label">PIC Toko</span>
                            <div class="ttd-canvas-wrap" id="wrap-pic">
                                <canvas id="canvas-pic" width="300" height="120"></canvas>
                                <span class="ttd-placeholder">TTD Disini</span>
                            </div>
                            <button type="button" class="btn btn-danger" onclick="clearTTD('pic')">✕ Hapus</button>
                            <input type="hidden" name="ttd_pic" id="ttd_pic_hidden">
                        </div>

                        <div class="ttd-box">
                            <span class="ttd-label">Agen</span>
                            <div class="ttd-canvas-wrap" id="wrap-agen">
                                <canvas id="canvas-agen" width="300" height="120"></canvas>
                                <span class="ttd-placeholder">TTD Disini</span>
                            </div>
                            <button type="button" class="btn btn-danger" onclick="clearTTD('agen')">✕ Hapus</button>
                            <input type="hidden" name="ttd_agen" id="ttd_agen_hidden">
                        </div>

                        <div class="ttd-box">
                            <span class="ttd-label">Kobin Tiles</span>
                            <div class="ttd-canvas-wrap" id="wrap-kobin">
                                <canvas id="canvas-kobin" width="300" height="120"></canvas>
                                <span class="ttd-placeholder">TTD Disini</span>
                            </div>
                            <button type="button" class="btn btn-danger" onclick="clearTTD('kobin')">✕ Hapus</button>
                            <input type="hidden" name="ttd_kobin_tiles" id="ttd_kobin_hidden">
                        </div>
                    </div>

                    <!-- Hidden fields -->
                    <input type="hidden" name="nama_toko"    id="nama_toko_hidden">
                    <input type="hidden" name="kode_toko"    id="kode_toko_hidden">
                    <input type="hidden" name="pic"          id="pic_hidden">
                    <input type="hidden" name="no_hp"        id="no_hp_hidden">
                    <input type="hidden" name="kota"         id="kota_hidden">
                    <input type="hidden" name="lokasi_event" id="lokasi_event_hidden">
                    <input type="hidden" name="nama_agen"    id="nama_agen_id_hidden">
                    <input type="hidden" name="nama_agen_id" id="nama_agen_id_hidden_alt">
                    <input type="hidden" name="pic_old"      id="pic_old_hidden">
                    <input type="hidden" name="nomor_pic_old" id="nomor_pic_old_hidden">

                    <div class="form-footer">
                        <a href="{{ route('form-order.index') }}" class="btn btn-outline">✕ Batal</a>
                        <button type="submit" class="btn btn-success">✔ Simpan Order</button>
                    </div>
                </form>
            </div>

        </div><!-- end grid-2 -->
    </div><!-- end card -->
</div><!-- end page -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
/* ═══════════════════════════════════════════
   SCANNER — native getUserMedia + jsQR
   Tidak ada library yang inject DOM / CSS
═══════════════════════════════════════════ */
const videoEl    = document.getElementById('scanVideo');
const canvasEl   = document.getElementById('scanCanvas');
const canvasCtx  = canvasEl.getContext('2d');
const statusEl   = document.getElementById('scanStatus');

let scanRunning  = false;
let scanStream   = null;
let scanRAF      = null;
let scanLock     = false;
let lastCode     = '';

function setStatus(msg, type) {
    statusEl.textContent  = msg;
    statusEl.className    = 'scanner-status' + (type ? ' ' + type : '');
}

async function startScanner() {
    if (scanRunning) return;
    try {
        scanStream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: { ideal: 'environment' }, width: { ideal: 1280 } }
        });
        videoEl.srcObject = scanStream;
        await videoEl.play();
        scanRunning = true;
        setStatus('Kamera aktif. Arahkan QR code ke frame.');
        requestAnimationFrame(tick);
    } catch (err) {
        setStatus('Gagal aktifkan kamera: ' + (err.message || err), 'err');
    }
}

function tick() {
    if (!scanRunning) return;
    if (videoEl.readyState === videoEl.HAVE_ENOUGH_DATA) {
        canvasEl.width  = videoEl.videoWidth;
        canvasEl.height = videoEl.videoHeight;
        canvasCtx.drawImage(videoEl, 0, 0);

        const imgData = canvasCtx.getImageData(0, 0, canvasEl.width, canvasEl.height);
        const result  = jsQR(imgData.data, imgData.width, imgData.height, {
            inversionAttempts: 'dontInvert'
        });

        if (result && result.data) {
            const code = result.data.trim();
            if (code && !scanLock && code !== lastCode) {
                scanLock = true;
                lastCode = code;
                document.getElementById('kode_toko_input').value = code.toUpperCase();
                setStatus('QR terbaca. Mencari data…');
                doLookupToko(code).always(function() {
                    setTimeout(function() { scanLock = false; }, 1500);
                });
            }
        }
    }
    scanRAF = requestAnimationFrame(tick);
}

async function stopScanner() {
    scanRunning = false;
    if (scanRAF)    { cancelAnimationFrame(scanRAF); scanRAF = null; }
    if (scanStream) { scanStream.getTracks().forEach(t => t.stop()); scanStream = null; }
    videoEl.srcObject = null;
    setStatus('Kamera dihentikan.');
}

/* Scan ulang → reload halaman */
document.getElementById('btnScanUlang').addEventListener('click', function() {
    window.location.reload();
});

$('#btnLookupToko').on('click', function() {
    const kode = $('#kode_toko_input').val().trim();
    if (!kode) { alertErr('Masukkan kode toko terlebih dahulu'); return; }
    doLookupToko(kode);
});

$('#kode_toko_input').on('keydown', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); $('#btnLookupToko').click(); }
});

$('#btnLookupAgen').on('click', function() {
    const kode = $('#kode_agen_manual_input').val().trim();
    if (!kode) { alertErr('Masukkan kode agen terlebih dahulu'); return; }
    doLookupAgen(kode);
});

$('#kode_agen_manual_input').on('keydown', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); $('#btnLookupAgen').click(); }
});

/* ═══════════════════════════════════════════
   TANDA TANGAN
═══════════════════════════════════════════ */
const pads = {};

function initPad(key) {
    const canvas = document.getElementById('canvas-' + key);
    const wrap   = document.getElementById('wrap-' + key);
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let drawing = false, lx = 0, ly = 0;

    function pos(e) {
        const r  = canvas.getBoundingClientRect();
        const sx = canvas.width  / r.width;
        const sy = canvas.height / r.height;
        const src = e.touches ? e.touches[0] : e;
        return { x: (src.clientX - r.left) * sx, y: (src.clientY - r.top) * sy };
    }

    function start(e) { e.preventDefault(); drawing = true; const p = pos(e); lx = p.x; ly = p.y; }
    function move(e) {
        e.preventDefault(); if (!drawing) return;
        const p = pos(e);
        ctx.beginPath(); ctx.moveTo(lx, ly); ctx.lineTo(p.x, p.y);
        ctx.strokeStyle = '#1e293b'; ctx.lineWidth = 2.2;
        ctx.lineCap = 'round'; ctx.lineJoin = 'round'; ctx.stroke();
        lx = p.x; ly = p.y;
        wrap.classList.add('has-sig');
        syncPad(key);
    }
    function end(e) { e.preventDefault(); drawing = false; }

    canvas.addEventListener('mousedown',  start);
    canvas.addEventListener('mousemove',  move);
    canvas.addEventListener('mouseup',    end);
    canvas.addEventListener('mouseleave', end);
    canvas.addEventListener('touchstart', start, { passive: false });
    canvas.addEventListener('touchmove',  move,  { passive: false });
    canvas.addEventListener('touchend',   end,   { passive: false });

    pads[key] = { canvas, ctx, wrap };
}

function syncPad(key) {
    const map = { pic: '#ttd_pic_hidden', agen: '#ttd_agen_hidden', kobin: '#ttd_kobin_hidden' };
    $(map[key]).val(document.getElementById('canvas-' + key).toDataURL('image/png'));
}

function setTTDError(key, hasError) {
    const box = document.querySelector('#wrap-' + key)?.closest('.ttd-box');
    if (!box) return;
    box.classList.toggle('invalid', !!hasError);
}

function validateRequiredTTD() {
    const requiredKeys = ['pic', 'agen', 'kobin'];
    let firstInvalidKey = null;

    requiredKeys.forEach(function(key) {
        const wrap = document.getElementById('wrap-' + key);
        const hasSignature = !!(wrap && wrap.classList.contains('has-sig'));
        setTTDError(key, !hasSignature);
        if (!hasSignature && !firstInvalidKey) firstInvalidKey = key;
    });

    if (firstInvalidKey) {
        alertErr('Semua tanda tangan wajib diisi: PIC Toko, Agen, dan Kobin Tiles.');
        const target = document.getElementById('wrap-' + firstInvalidKey);
        if (target) target.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
    }

    return true;
}

function clearTTD(key) {
    const p = pads[key]; if (!p) return;
    p.ctx.clearRect(0, 0, p.canvas.width, p.canvas.height);
    p.wrap.classList.remove('has-sig');
    setTTDError(key, false);
    const map = { pic: '#ttd_pic_hidden', agen: '#ttd_agen_hidden', kobin: '#ttd_kobin_hidden' };
    $(map[key]).val('');
}

['pic', 'agen', 'kobin'].forEach(initPad);

/* ═══════════════════════════════════════════
   UPPERCASE INPUT
═══════════════════════════════════════════ */
document.querySelectorAll('input[type="text"]').forEach(function(el) {
    el.addEventListener('input', function() {
        const s = this.selectionStart, e = this.selectionEnd;
        this.value = (this.value || '').toUpperCase();
        try { this.setSelectionRange(s, e); } catch(_) {}
    });
});

/* ═══════════════════════════════════════════
   PAKET QTY UX
═══════════════════════════════════════════ */
document.querySelectorAll('.paket-qty').forEach(function(el) {
    el.addEventListener('focus', function() { if (this.value === '0') this.value = ''; });
    el.addEventListener('blur',  function() { if (this.value === '')  this.value = '0'; });
});

/* ═══════════════════════════════════════════
   HELPERS
═══════════════════════════════════════════ */
function alertErr(msg) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({ icon: 'error', title: 'Perhatian', text: msg, confirmButtonText: 'OK' });
    } else { alert(msg); }
}

function closeToast() { $('#toast').removeClass('show'); }

function resetForm() {
    document.getElementById('scanForm').reset();
    scanLock = false; lastCode = '';
    ['kode_toko_input','pic','no_hp','kota','nama_agen','brand','nama_sales',
     'kode_agen_manual_input','kode_agen_input'].forEach(id => document.getElementById(id).value = '');
    ['nama_toko_hidden','kode_toko_hidden','pic_hidden','no_hp_hidden','kota_hidden',
     'lokasi_event_hidden','nama_agen_id_hidden','nama_agen_id_hidden_alt'].forEach(id => document.getElementById(id).value = '');
    document.querySelectorAll('.paket-qty').forEach(el => el.value = '0');
    ['pic','agen','kobin'].forEach(clearTTD);
}

/* ═══════════════════════════════════════════
   INIT
═══════════════════════════════════════════ */
window.addEventListener('load', startScanner);
window.addEventListener('beforeunload', function() { stopScanner(); });

// Variabel global untuk menyimpan status edit
let isEditingOrder = false;
let currentOrderId = null;

// Modified doLookupAgen function
function doLookupAgen(kode) {
    kode = (kode || '').trim();
    if (!kode) return Promise.reject('Kode agen kosong');
    
    return $.get('{{ url('/api/lookup-agen-by-kode') }}', { kode_agen: kode })
        .done(function(res) {
            if (res.success) {
                $('#kode_agen_input').val(res.data.kode_agen || kode);
                $('#nama_agen').val(res.data.nama_agen || '');
                $('#brand').val((res.data.brands || []).join(', '));
                $('#nama_agen_id_hidden').val(res.data.id || '');
                $('#nama_agen_id_hidden_alt').val(res.data.id || '');
                
                // AFTER loading agen, check for existing order
                checkExistingOrder();
            } else {
                $('#kode_agen_input, #nama_agen, #brand').val('');
                $('#nama_agen_id_hidden, #nama_agen_id_hidden_alt').val('');
                alertErr(res.message || 'Agen tidak ditemukan');
                resetOrderForm();
            }
        })
        .fail(function() { 
            alertErr('Gagal melakukan lookup agen');
            resetOrderForm();
        });
}

// New function to check existing order
function checkExistingOrder() {
    const kodeAgen = $('#kode_agen_input').val().trim();
    const namaToko = $('#nama_toko_hidden').val().trim();
    const lokasiEvent = $('#lokasi_event').val().trim();
    const kota = $('#kota').val().trim();
    const picOld = $('#pic_old_hidden').val().trim();
    const nomorPicOld = $('#nomor_pic_old_hidden').val().trim();
    
    if (!kodeAgen || !namaToko || !lokasiEvent || !kota) {
        return;
    }
    
    // Show loading indicator
    $('#btnLookupAgen').prop('disabled', true);
    $('#btnLookupAgen').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Checking...');
    
    $.get('{{ url('/api/check-existing-order') }}', {
        kode_agen: kodeAgen,
        nama_toko: namaToko,
        lokasi_event: lokasiEvent,
        kota: kota,
        pic_old: picOld,
        nomor_pic_old: nomorPicOld
    })
    .done(function(res) {
        $('#btnLookupAgen').prop('disabled', false);
        $('#btnLookupAgen').html('🔍 Cari');
        
        if (res.success && res.exists) {
            // Existing order found - load data
            isEditingOrder = true;
            currentOrderId = res.data.id;
            
            // Add order_id to form
            if ($('#order_id').length === 0) {
                $('<input>').attr({
                    type: 'hidden',
                    id: 'order_id',
                    name: 'order_id'
                }).appendTo('#scanForm');
            }
            $('#order_id').val(currentOrderId);
            
            // CRITICAL: Set the pic_old and nomor_pic_old from existing order data
            // This ensures the update can find the existing toko records
            if (res.data.pic_old) {
                $('#pic_old_hidden').val(res.data.pic_old);
                $('#nomor_pic_old_hidden').val(res.data.nomor_pic_old);
                // Also update the display fields if needed
                $('#pic').val(res.data.pic);
                $('#no_hp').val(res.data.no_hp);
            }
            
            // Update kode_toko if needed
            if (res.data.kode_toko) {
                $('#kode_toko_hidden').val(res.data.kode_toko);
                $('#kode_toko_input').val(res.data.kode_toko);
            }
            
            // Load existing data
            $('#brand').val(res.data.brand || '');
            $('#nama_sales').val(res.data.nama_sales || '');
            
            // Load paket quantities
            $('.paket-qty').each(function() {
                const $this = $(this);
                const masterTargetId = $this.closest('.paket-row').find('input[name$="[master_target_id]"]').val();
                
                if (res.data.details && res.data.details[masterTargetId] !== undefined && res.data.details[masterTargetId] > 0) {
                    $this.val(res.data.details[masterTargetId]);
                } else {
                    $this.val(0);
                }
            });
            
            // Clear all signatures (as requested)
            clearTTD('pic');
            clearTTD('agen');
            clearTTD('kobin');
            
            // Remove has-sig class from wraps
            $('#wrap-pic, #wrap-agen, #wrap-kobin').removeClass('has-sig');
            
            // Format numbers for display
            const formattedTotalPoint = new Intl.NumberFormat('id-ID').format(res.data.total_point || 0);
            const formattedTotalKupon = new Intl.NumberFormat('id-ID').format(res.data.total_kupon || 0);
            
            // Show notification
            Swal.fire({
                icon: 'info',
                title: '📋 Data Order Ditemukan',
                html: `
                    <div style="text-align: left; margin-top: 10px;">
                        <div style="background: #f8f9fa; padding: 12px; border-radius: 8px; margin-bottom: 10px;">
                            <strong>📊 Informasi Order:</strong><br>
                            <span style="color: #ef4444;">◆</span> <strong>Total Point:</strong> ${formattedTotalPoint}<br>
                            <span style="color: #3b82f6;">◆</span> <strong>Brand:</strong> ${res.data.brand || '-'}<br>
                            <span style="color: #8b5cf6;">◆</span> <strong>Sales:</strong> ${res.data.nama_sales || '-'}
                        </div>
                        <div style="background: #fff3cd; padding: 12px; border-radius: 8px; border-left: 4px solid #ffc107;">
                            <strong>⚠️ Perhatian:</strong><br>
                            Tanda tangan akan direset dan harus diisi ulang untuk validasi.
                        </div>
                    </div>
                `,
                confirmButtonText: '✏️ Edit Order',
                confirmButtonColor: '#ef4444',
                showCancelButton: true,
                cancelButtonText: '❌ Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('html, body').animate({
                        scrollTop: $('#scanForm').offset().top - 100
                    }, 500);
                }
            });
            
            // Update submit button
            $('.btn-success').html('✏️ Update Order');
            $('.btn-success').addClass('btn-warning').removeClass('btn-success');
            
        } else if (res.success && !res.exists) {
            // No existing order - normal flow
            isEditingOrder = false;
            currentOrderId = null;
            if ($('#order_id').length) {
                $('#order_id').remove();
            }
            
            // Reset paket quantities to 0
            $('.paket-qty').val(0);
            
            // Clear signatures
            clearTTD('pic');
            clearTTD('agen');
            clearTTD('kobin');
            $('#wrap-pic, #wrap-agen, #wrap-kobin').removeClass('has-sig');
            
            // Reset submit button
            $('.btn-success').html('✔ Simpan Order');
            $('.btn-success').removeClass('btn-warning').addClass('btn-success');
            
            // Swal.fire({
            //     icon: 'success',
            //     title: '✨ Data Baru',
            //     text: res.message || 'Data order tidak ditemukan. Silakan buat order baru.',
            //     timer: 2500,
            //     showConfirmButton: false
            // });
        }
    })
    .fail(function(err) {
        $('#btnLookupAgen').prop('disabled', false);
        $('#btnLookupAgen').html('🔍 Cari');
        console.error('Error checking existing order:', err);
        
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Gagal mengecek data order. Silakan coba lagi.',
            confirmButtonText: 'OK'
        });
    });
}

// Update doLookupToko to pass additional data for checking
function doLookupToko(kode) {
    return $.get('{{ url('/api/lookup-toko-by-kode') }}', { kode: kode })
        .done(function(res) {
            if (res.success) {
                const d = res.data;
                $('#pic').val(d.pic || '');
                $('#no_hp').val(d.no_hp || '');
                $('#kota').val(d.kota || '');
                $('#lokasi_event').val(d.lokasi_event || $('#lokasi_event').val());
                $('#nama_sales').val(d.nama_sales || '');
                // hidden
                $('#nama_toko_hidden').val(d.nama_toko || ''); // Store nama_toko for checking
                $('#kode_toko_hidden').val(d.kode_toko || '');
                $('#pic_hidden').val(d.pic || '');
                $('#no_hp_hidden').val(d.no_hp || '');
                $('#kota_hidden').val(d.kota || '');
                $('#lokasi_event_hidden').val(d.lokasi_event || $('#lokasi_event').val());
                $('#pic_old_hidden').val(d.pic || '');
                $('#nomor_pic_old_hidden').val(d.no_hp || '');
                setStatus('✔ Toko: ' + (d.nama_toko || ''), 'ok');
                
                // AFTER loading toko, check if agen is already loaded
                if ($('#kode_agen_input').val().trim()) {
                    setTimeout(function() {
                        checkExistingOrder();
                    }, 500); // Small delay to ensure all data is set
                }
            } else {
                if (res.default_lokasi) $('#lokasi_event').val(res.default_lokasi);
                setStatus('Toko tidak ditemukan.', 'err');
                alertErr(res.message || 'Toko tidak ditemukan');
                resetOrderForm();
            }
        })
        .fail(function() {
            setStatus('Lookup gagal.', 'err');
            alertErr('Gagal melakukan lookup toko');
            resetOrderForm();
        });
}

// Add CSS for btn-warning class if not exists
const style = document.createElement('style');
style.textContent = `
    .btn-warning {
        background: #f59e0b;
        color: #fff;
    }
    .btn-warning:hover {
        background: #d97706;
    }
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
        border-width: 0.2em;
    }
`;
document.head.appendChild(style);

// Reset order form function
function resetOrderForm() {
    isEditingOrder = false;
    currentOrderId = null;
    if ($('#order_id').length) {
        $('#order_id').remove();
    }
    $('.paket-qty').val(0);
    clearTTD('pic');
    clearTTD('agen');
    clearTTD('kobin');
    $('.btn-success').html('✔ Simpan Order');
}

// Update submit handler to show appropriate message
$('#scanForm').on('submit', function(e) {
    e.preventDefault();
    
    // Pastikan source terkirim
    if ($('#source').length === 0) {
        $('<input>').attr({
            type: 'hidden',
            name: 'source',
            value: 'quick-scan'
        }).appendTo('#scanForm');
    }

    // Uppercase semua text
    $(this).find('input[type="text"], textarea').each(function() {
        if (this.value) this.value = this.value.toUpperCase();
    });

    // Sync tanda tangan
    ['pic', 'agen', 'kobin'].forEach(syncPad);

    // Validasi frontend-only untuk tanda tangan wajib
    if (!validateRequiredTTD()) return;

    // Validasi
    if (!$('#nama_toko_hidden').val())        { alertErr('Scan Toko terlebih dahulu'); return; }
    if (!$('#kode_agen_input').val().trim())  { alertErr('Pilih Kode Agen via tombol Cari'); return; }
    if (!$('#nama_agen_id_hidden_alt').val()) { alertErr('Lookup Agen terlebih dahulu'); return; }
    if (!$('#brand').val())                   { alertErr('Brand harus ada'); return; }

    const fd = new FormData(this);
    const submitAction = isEditingOrder ? 'mengupdate' : 'menyimpan';
    
    // Show loading indicator
    Swal.fire({
        title: 'Memproses...',
        text: `Sedang ${submitAction} data order`,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(res) {
            Swal.close();
            if (res.success) {
                const successMsg = res.is_update ? 'Order berhasil diupdate!' : 'Order berhasil disimpan!';
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: successMsg + ' ' + (res.message || ''),
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = res.redirect_url;
                });
            } else {
                alertErr(res.message || 'Submission gagal');
            }
        },
        error: function(xhr) {
            Swal.close();
            let msg = 'Terjadi kesalahan saat submit.';
            if (xhr.responseJSON?.message) msg = xhr.responseJSON.message;
            else if (xhr.status === 422 && xhr.responseJSON?.errors)
                msg = Object.values(xhr.responseJSON.errors).flat().join('\n');
            alertErr(msg);
        }
    });
});

// Add event listener for lokasi_event change (if it's editable)
$('#lokasi_event').on('change', function() {
    if ($('#kode_agen_input').val().trim() && $('#kode_toko_hidden').val().trim()) {
        checkExistingOrder();
    }
});
</script>
</body>
</html>