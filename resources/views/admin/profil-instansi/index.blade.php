@extends('layouts.admin')

@section('title', 'Profil Instansi')
@section('pretitle', 'CMS Publik')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Profil Instansi</h1>
        <button type="button" class="btn btn-cta btn-sm" id="btnTambahProfil" data-route-store="{{ route('admin.profil-instansi.store') }}">+ Tambah Bagian</button>
    </div>
    <p class="text-secondary small">Contoh bagian: "Visi &amp; Misi", "Sejarah", "Tugas dan Fungsi", dll. Ditampilkan berurutan di halaman Profil publik.</p>

    <div class="card">
        <div class="card-body">
            <table id="tabelProfil" class="table table-hover w-100" data-route-data="{{ route('admin.profil-instansi.data') }}">
                <thead>
                    <tr><th>Judul</th><th>Konten</th><th>Aksi</th></tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalProfil" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formProfil" data-method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="">
                    <div class="modal-header">
                        <h5 class="modal-title">Bagian Profil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Judul Bagian</label>
                            <input type="text" name="judul" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konten</label>
                            <textarea name="konten" class="form-control" rows="8"></textarea>
                            <div class="invalid-feedback"></div>
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

    @vite(['resources/js/pages/admin/profil-instansi.js'])
@endsection
