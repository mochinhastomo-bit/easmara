@extends('layouts.admin')

@section('title', 'Media Sosial')
@section('pretitle', 'Profil Instansi')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Media Sosial</h1>
        <button type="button" class="btn btn-cta btn-sm" id="btnTambahMediaSosial" data-route-store="{{ route('admin.media-sosial.store') }}">+ Tambah</button>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="tabelMediaSosial" class="table table-hover w-100" data-route-data="{{ route('admin.media-sosial.data') }}">
                <thead>
                    <tr><th>Platform</th><th>URL</th><th>Ikon</th><th>Urutan</th><th>Aksi</th></tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalMediaSosial" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formMediaSosial" data-method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="">
                    <div class="modal-header">
                        <h5 class="modal-title">Media Sosial</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Platform</label>
                            <input type="text" name="platform" class="form-control" placeholder="Instagram, Facebook, dll" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">URL</label>
                            <input type="url" name="url" class="form-control" placeholder="https://" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ikon <span class="text-secondary">(opsional, nama class Bootstrap Icons)</span></label>
                            <input type="text" name="ikon" class="form-control" placeholder="bi-instagram">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Urutan</label>
                            <input type="number" name="urutan" class="form-control" value="0" min="0">
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

    @vite(['resources/js/pages/admin/media-sosial.js'])
@endsection
