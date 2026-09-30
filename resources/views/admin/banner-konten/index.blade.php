@extends('layouts.admin')

@section('title', 'Banner Konten')
@section('pretitle', 'Profil Instansi')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Banner Halaman Utama</h1>
        <button type="button" class="btn btn-cta btn-sm" id="btnTambahBanner" data-route-store="{{ route('admin.banner-konten.store') }}">+ Tambah Banner</button>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="tabelBanner" class="table table-hover w-100" data-route-data="{{ route('admin.banner-konten.data') }}">
                <thead>
                    <tr><th>Gambar</th><th>Judul</th><th>URL Tujuan</th><th>Urutan</th><th>Status</th><th>Aksi</th></tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalBanner" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formBanner" data-method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="">
                    <div class="modal-header">
                        <h5 class="modal-title">Banner</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Judul <span class="text-secondary">(opsional)</span></label>
                            <input type="text" name="judul" class="form-control">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gambar <span class="text-secondary" id="labelGambarWajib">(wajib, maks. 2MB)</span></label>
                            <input type="file" name="gambar" class="form-control" accept="image/*">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">URL Tujuan <span class="text-secondary">(opsional, saat banner diklik)</span></label>
                            <input type="text" name="url_tujuan" class="form-control">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Urutan</label>
                            <input type="number" name="urutan" class="form-control" value="0" min="0">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="aktif" value="1" class="form-check-input" id="aktifBanner" checked>
                            <label class="form-check-label" for="aktifBanner">Aktif</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @vite(['resources/js/pages/admin/banner-konten.js'])
@endsection
