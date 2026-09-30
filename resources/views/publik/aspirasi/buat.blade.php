@extends('layouts.app')

@section('title', 'Kirim Aspirasi')

@section('content')
    <h1 class="h4 mb-4">Formulir Aspirasi</h1>

    <form method="POST" action="{{ route('aspirasi.store') }}" enctype="multipart/form-data"
          data-recaptcha-action="kirim_aspirasi" autocomplete="off">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama_pelapor" class="form-control" value="{{ old('nama_pelapor') }}" required autocomplete="off">
        </div>

        <div class="mb-3">
            <label class="form-label">Nomor WhatsApp</label>
            <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx"
                   value="{{ old('no_hp') }}" required autocomplete="off">
        </div>

        <div class="mb-3">
            <label class="form-label">Email <span class="text-secondary">(cadangan verifikasi)</span></label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" autocomplete="off">
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Kecamatan</label>
                <select id="kecamatan_select" class="form-select" required>
                    <option value="">Pilih kecamatan&hellip;</option>
                    @foreach ($kecamatanList as $kec)
                        <option value="{{ $kec->id }}">{{ $kec->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Desa/Kelurahan</label>
                <select name="wilayah_id" id="wilayah_select" class="form-select" required disabled>
                    <option value="">Pilih kecamatan dahulu&hellip;</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori_id" class="form-select" required>
                <option value="">Pilih kategori&hellip;</option>
                @foreach ($kategoriList as $kat)
                    <option value="{{ $kat->id }}" @selected(old('kategori_id') == $kat->id)>{{ $kat->nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Judul Aspirasi</label>
            <input type="text" name="judul" class="form-control" value="{{ old('judul') }}" required autocomplete="off">
        </div>

        <div class="mb-3">
            <label class="form-label">Uraian</label>
            <textarea name="uraian" class="form-control" rows="5" required autocomplete="off">{{ old('uraian') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat Detail <span class="text-secondary">(opsional)</span></label>
            <textarea name="alamat_detail" class="form-control" rows="2" autocomplete="off">{{ old('alamat_detail') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Lampiran <span class="text-secondary">(opsional, maks. 5 file &mdash; JPG/PNG/PDF, {{ $lampiranMaksUkuranMb }}MB/file)</span></label>
            <input type="file" name="lampiran[]" class="form-control" multiple accept=".jpg,.jpeg,.png,.pdf">
        </div>

        <div class="form-check mb-4">
            <input type="checkbox" name="consent_pdpb" id="consent_pdpb" class="form-check-input" value="1" required>
            <label class="form-check-label" for="consent_pdpb">
                Saya menyetujui data pribadi saya diproses sesuai ketentuan Perlindungan Data Pribadi (PDPB)
                untuk keperluan tindak lanjut aspirasi ini.
            </label>
        </div>

        <button type="submit" class="btn btn-primary">Lanjutkan</button>
    </form>

    @vite(['resources/js/pages/publik/aspirasi-buat.js'])
@endsection
