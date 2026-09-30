@extends('layouts.app')

@section('title', 'Aspirasi Terkirim')

@section('content')
    <div class="text-center py-4">
        <h1 class="h4 text-success mb-3">Aspirasi Anda Berhasil Terkirim</h1>
        <p class="text-secondary">Simpan nomor tiket berikut untuk menelusuri status aspirasi Anda:</p>
        <div class="border rounded-3 p-4 bg-white d-inline-block my-3">
            <span class="h3 font-monospace">{{ $kodeTiket }}</span>
        </div>
        <div>
            <a href="{{ route('aspirasi.status') }}" class="btn btn-outline-primary">Cek Status Sekarang</a>
            <a href="{{ route('beranda') }}" class="btn btn-link">Kembali ke Beranda</a>
        </div>
    </div>
@endsection
