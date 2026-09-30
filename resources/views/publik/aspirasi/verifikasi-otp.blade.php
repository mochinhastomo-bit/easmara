@extends('layouts.app')

@section('title', 'Masukkan Kode OTP')

@section('content')
    <h1 class="h4 mb-3">Masukkan Kode OTP</h1>

    @if ($channel === 'whatsapp_manual' && $waLink)
        <div class="alert alert-info">
            <p class="mb-2">
                Kode OTP Anda belum bisa dikirim otomatis. Silakan kirim permohonan verifikasi
                berikut ke WhatsApp Dinas Kominfo Gresik dengan Kode Tiket <strong>{{ $kodeTiket }}</strong>,
                lalu tunggu balasan berisi kode OTP dari nomor tersebut.
            </p>
            <a href="{{ $waLink }}" target="_blank" rel="noopener" class="btn btn-success btn-sm">
                Kirim Permohonan via WhatsApp
            </a>
        </div>
    @else
        <p class="text-secondary">
            Kode 6 digit telah dikirim via {{ $channel === 'whatsapp' ? 'WhatsApp' : 'email' }}.
        </p>
    @endif

    <form method="POST" action="{{ route('aspirasi.verifikasi-otp.submit') }}">
        @csrf
        @if (in_array($channel, ['whatsapp', 'whatsapp_manual']))
            <div class="mb-3">
                <label class="form-label">Nomor WhatsApp Anda</label>
                <input type="text" name="no_hp_konfirmasi" class="form-control" value="{{ $noHp }}" readonly required autocomplete="off">
                <div class="form-text">Nomor WhatsApp yang Anda daftarkan saat mengisi aspirasi ini.</div>
            </div>
        @endif
        <div class="mb-3">
            <label class="form-label">Kode OTP</label>
            <input type="text" name="kode_otp" class="form-control" maxlength="6" inputmode="numeric"
                   autocomplete="one-time-code" required @if (!in_array($channel, ['whatsapp', 'whatsapp_manual'])) autofocus @endif>
        </div>
        <button type="submit" class="btn btn-primary">Verifikasi &amp; Kirim Aspirasi</button>
    </form>

    <form method="POST" action="{{ route('aspirasi.verifikasi-hp.kirim') }}" class="mt-3">
        @csrf
        <button type="submit" class="btn btn-link p-0">Kirim ulang kode OTP</button>
    </form>
@endsection
