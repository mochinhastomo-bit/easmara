@extends('layouts.admin')

@section('pretitle', 'Ringkasan')
@section('title', 'Dasbor')

@section('content')
    <div class="row g-3 mb-4">
        @foreach ([
            ['label' => 'Total Aspirasi', 'value' => $stats['total'], 'color' => 'azure', 'icon' => 'message-report'],
            ['label' => 'Menunggu Verifikasi', 'value' => $stats['menunggu'], 'color' => 'secondary', 'icon' => 'clock'],
            ['label' => 'Terverifikasi', 'value' => $stats['diverifikasi'], 'color' => 'success', 'icon' => 'circle-check'],
            ['label' => 'Ditolak', 'value' => $stats['ditolak'], 'color' => 'danger', 'icon' => 'circle-x'],
            ['label' => 'Terindikasi Spam', 'value' => $stats['spam'], 'color' => 'warning', 'icon' => 'alert-triangle'],
        ] as $tile)
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card h-100 stat-tile">
                    <span class="stat-tile-icon bg-{{ $tile['color'] }}">
                        <i class="ti ti-{{ $tile['icon'] }}"></i>
                    </span>
                    <div class="card-body">
                        <div class="text-secondary small">{{ $tile['label'] }}</div>
                        <div class="h3 mb-0">{{ $tile['value'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card mb-4 overflow-hidden">
        <div class="card-body bg-primary text-white">
            <canvas id="chartTren" height="80" data-labels="{{ json_encode($labelTren) }}" data-values="{{ json_encode($dataTren) }}"></canvas>
        </div>
        <div class="card-footer">
            <div class="fw-medium">Tren Aspirasi Masuk</div>
            <div class="text-secondary small">14 hari terakhir</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Aspirasi Terbaru</div>
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>Kode Tiket</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Wilayah</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($terbaru as $a)
                        <tr>
                            <td class="font-monospace">{{ $a->kode_tiket }}</td>
                            <td>{{ $a->judul }}</td>
                            <td>{{ $a->kategori?->nama }}</td>
                            <td>{{ $a->wilayah?->nama ?? '—' }}</td>
                            <td><span class="badge text-bg-{{ \App\Support\StatusWarna::verifikasi($a->status_verifikasi) }}">{{ ucfirst($a->status_verifikasi) }}</span></td>
                            <td><a href="{{ route('admin.aspirasi.show', $a) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-secondary py-3">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @vite(['resources/js/pages/admin/dashboard.js'])
@endsection
