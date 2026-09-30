@extends('layouts.app')

@section('title', 'Status Aspirasi')

@php
    $labelVerifikasi = [
        'menunggu' => ['Menunggu Verifikasi', 'secondary'],
        'diverifikasi' => ['Terverifikasi', 'success'],
        'ditolak' => ['Ditolak', 'danger'],
    ][$aspirasi->status_verifikasi] ?? ['-', 'secondary'];

    $labelTindakLanjut = [
        'diterima' => ['Diterima Komisi', 'info'],
        'ditunda' => ['Ditunda', 'warning'],
        'diproses' => ['Sedang Diproses', 'primary'],
        'ditolak' => ['Ditolak', 'danger'],
        'selesai' => ['Selesai', 'success'],
    ];
@endphp

@section('content')
    <h1 class="h4 mb-3">Status Aspirasi</h1>

    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="font-monospace fw-semibold">{{ $aspirasi->kode_tiket }}</span>
                <span class="badge text-bg-{{ $labelVerifikasi[1] }}">{{ $labelVerifikasi[0] }}</span>
            </div>
            <h2 class="h6 mb-1">{{ $aspirasi->judul }}</h2>
            <p class="text-secondary small mb-2">
                {{ $aspirasi->kategori?->nama }}
                @if ($aspirasi->wilayah) &middot; {{ $aspirasi->wilayah->nama }} @endif
                &middot; {{ $aspirasi->created_at->translatedFormat('d F Y') }}
            </p>

            @if ($aspirasi->komisi->isNotEmpty())
                <hr>
                <p class="small fw-semibold mb-1">Disalurkan ke:</p>
                @foreach ($aspirasi->komisi as $komisi)
                    @php $tl = $labelTindakLanjut[$komisi->pivot->status_tindak_lanjut] ?? ['-', 'secondary']; @endphp
                    <span class="badge text-bg-{{ $tl[1] }} me-1">{{ $komisi->nama }}: {{ $tl[0] }}</span>
                @endforeach
            @endif
        </div>
    </div>

    @if ($aspirasi->statusLog->isNotEmpty())
        <h2 class="h6">Riwayat</h2>
        <ul class="list-group mb-3">
            @foreach ($aspirasi->statusLog as $log)
                <li class="list-group-item d-flex justify-content-between">
                    <span>{{ $log->status_ke }}</span>
                    <span class="text-secondary small">{{ $log->created_at?->translatedFormat('d M Y, H:i') }}</span>
                </li>
            @endforeach
        </ul>
    @endif

    <a href="{{ route('aspirasi.status') }}" class="btn btn-link ps-0">&larr; Cari tiket lain</a>
@endsection
