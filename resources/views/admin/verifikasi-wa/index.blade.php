@extends('layouts.admin')

@section('title', 'Verifikasi WA Manual')
@section('pretitle', 'Verifikasi Pelapor')

@section('content')
    <h1 class="h4 mb-2">Verifikasi WA Manual</h1>
    <p class="text-secondary">
        Daftar permohonan verifikasi yang masuk lewat WhatsApp ke nomor Dinas. Balas kode OTP di bawah
        secara manual dari aplikasi WhatsApp Dinas ke nomor pelapor yang mengirim Kode Tiket tersebut.
        Baris otomatis hilang setelah kode dipakai atau kedaluwarsa.
    </p>

    <div class="card">
        <div class="card-body">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>Kode Tiket</th>
                        <th>Nomor WA Pelapor</th>
                        <th>Kode OTP</th>
                        <th>Kedaluwarsa</th>
                        <th>Percobaan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftar as $item)
                        <tr>
                            <td class="font-monospace">{{ $item['kode_tiket'] ?? '—' }}</td>
                            <td>{{ $item['no_hp'] }}</td>
                            <td class="font-monospace fs-3 fw-bold">{{ $item['kode_otp'] }}</td>
                            <td>{{ $item['kadaluarsa_pada']->translatedFormat('d M Y, H:i:s') }}</td>
                            <td>{{ $item['percobaan'] }}/5</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-secondary">Belum ada permohonan verifikasi WA manual yang menunggu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer text-center">
            <a href="{{ route('admin.verifikasi-wa.index') }}" class="btn btn-outline-secondary btn-sm">Muat Ulang</a>
        </div>
    </div>
@endsection
