<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 2.5cm 2cm 2cm 2cm; }
        body { font-family: 'Times New Roman', serif; font-size: 12px; color: #000; }
        .kop { text-align: center; border-bottom: 3px solid #000; padding-bottom: 8px; margin-bottom: 16px; }
        .kop h1 { font-size: 16px; margin: 0; text-transform: uppercase; }
        .kop h2 { font-size: 14px; margin: 0; text-transform: uppercase; }
        .kop p { margin: 2px 0; font-size: 10px; }
        .judul { text-align: center; margin-bottom: 4px; }
        .judul h3 { font-size: 13px; text-decoration: underline; margin: 0; text-transform: uppercase; }
        .meta { margin-bottom: 12px; font-size: 11px; }
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data th, table.data td { border: 1px solid #000; padding: 4px 6px; font-size: 10px; text-align: left; vertical-align: top; }
        table.data th { background: #eee; text-align: center; }
        table.ringkasan { width: 60%; border-collapse: collapse; margin-bottom: 16px; }
        table.ringkasan td { border: 1px solid #000; padding: 3px 6px; font-size: 11px; }
        .ttd { margin-top: 40px; width: 100%; }
        .ttd td { width: 50%; text-align: center; vertical-align: top; font-size: 11px; }
        .ttd .spasi { height: 60px; }
    </style>
</head>
<body>
    <div class="kop">
        <h1>Sekretariat DPRD Kabupaten Gresik</h1>
        <h2>e-ASMARA &mdash; Electronic Aspirasi Masyarakat</h2>
        <p>Jl. Dr. Wahidin Sudirohusodo, Gresik, Jawa Timur</p>
    </div>

    <div class="judul">
        <h3>Rekapitulasi Aspirasi Masyarakat</h3>
    </div>

    <div class="meta">
        <table style="width:100%; font-size: 11px;">
            <tr>
                <td style="width:120px;">Periode</td>
                <td>: {{ $filters['periode_dari'] ?? '—' }} s/d {{ $filters['periode_sampai'] ?? '—' }}</td>
                <td style="width:100px;">Tanggal Cetak</td>
                <td>: {{ $tanggalCetak->translatedFormat('d F Y, H:i') }} WIB</td>
            </tr>
            <tr>
                <td>Dicetak Oleh</td>
                <td colspan="3">: {{ $dicetakOleh->name }} ({{ ucfirst($dicetakOleh->role) }})</td>
            </tr>
        </table>
    </div>

    <table class="ringkasan">
        <tr><td><strong>Total Aspirasi</strong></td><td><strong>{{ $items->count() }}</strong></td></tr>
        <tr><td>Diverifikasi</td><td>{{ $items->where('status_verifikasi', 'diverifikasi')->count() }}</td></tr>
        <tr><td>Menunggu</td><td>{{ $items->where('status_verifikasi', 'menunggu')->count() }}</td></tr>
        <tr><td>Ditolak</td><td>{{ $items->where('status_verifikasi', 'ditolak')->count() }}</td></tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>No.</th>
                <th>Kode Tiket</th>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Wilayah</th>
                <th>Komisi</th>
                <th>Status</th>
                <th>Judul</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $i => $a)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $a->kode_tiket }}</td>
                    <td>{{ $a->created_at->format('d-m-Y') }}</td>
                    <td>{{ $a->kategori?->nama }}</td>
                    <td>{{ $a->wilayah?->nama ?? '-' }}</td>
                    <td>{{ $a->komisi->pluck('nama')->implode(', ') ?: '-' }}</td>
                    <td>{{ ucfirst($a->status_verifikasi) }}</td>
                    <td>{{ $a->judul }}</td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center;">Tidak ada data pada periode/filter ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <table class="ttd">
        <tr>
            <td></td>
            <td>
                Gresik, {{ $tanggalCetak->translatedFormat('d F Y') }}<br>
                Sekretaris DPRD Kabupaten Gresik
                <div class="spasi"></div>
                <strong>( &hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip; )</strong><br>
                NIP. &hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;
            </td>
        </tr>
    </table>
</body>
</html>
