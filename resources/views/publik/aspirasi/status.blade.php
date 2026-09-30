@extends('layouts.app')

@section('title', 'Cek Status Aspirasi')

@section('content')
    <h1 class="h4 mb-3">Cek Status Aspirasi</h1>

    <form method="POST" action="{{ route('aspirasi.status.cari') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nomor Tiket</label>
            <input type="text" name="kode_tiket" class="form-control font-monospace"
                   placeholder="ASM-2026-00000" value="{{ old('kode_tiket') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Cari</button>
    </form>
@endsection
