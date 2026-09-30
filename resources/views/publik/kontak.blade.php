@extends('layouts.app')

@section('title', 'Kontak')

@section('content')
    <h1 class="h4 mb-4">Hubungi Kami</h1>

    <form method="POST" action="{{ route('kontak.store') }}" data-recaptcha-action="kirim_kontak">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email <span class="text-secondary">(opsional)</span></label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Subjek <span class="text-secondary">(opsional)</span></label>
            <input type="text" name="subjek" class="form-control" value="{{ old('subjek') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Pesan</label>
            <textarea name="pesan" class="form-control" rows="5" required>{{ old('pesan') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Kirim Pesan</button>
    </form>
@endsection
