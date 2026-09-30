@extends('layouts.app')

@section('title', 'Verifikasi Nomor WhatsApp')

@section('content')
    <h1 class="h4 mb-3">Verifikasi Nomor WhatsApp</h1>
    <p class="text-secondary">
        Kode verifikasi (OTP) akan dikirim ke nomor <strong>{{ $noHpMasked }}</strong>.
    </p>

    <form method="POST" action="{{ route('aspirasi.verifikasi-hp.kirim') }}">
        @csrf
        <button type="submit" class="btn btn-primary">Kirim Kode OTP</button>
        <a href="{{ route('aspirasi.buat') }}" class="btn btn-link">Ubah data</a>
    </form>
@endsection
