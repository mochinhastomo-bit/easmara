@extends('layouts.admin')

@section('title', 'Prosedur Layanan')
@section('pretitle', 'Profil Instansi')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Prosedur Layanan</h1>
        <button type="button" class="btn btn-cta btn-sm" id="btnTambahProsedur" data-route-store="{{ route('admin.prosedur.store') }}">+ Tambah</button>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="tabelProsedur" class="table table-hover w-100" data-route-data="{{ route('admin.prosedur.data') }}">
                <thead>
                    <tr><th>Jenis</th><th>Judul</th><th>Urutan</th><th>Aksi</th></tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalProsedur" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formProsedur" data-method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="">
                    <div class="modal-header">
                        <h5 class="modal-title">Prosedur Layanan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Jenis</label>
                            <select name="jenis" class="form-select" required>
                                <option value="online">Online</option>
                                <option value="offline">Offline</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Judul</label>
                            <input type="text" name="judul" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konten</label>
                            <textarea name="konten" class="form-control" rows="5"></textarea>
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

    @vite(['resources/js/pages/admin/prosedur.js'])
@endsection
