@extends('layouts.admin')

@section('title', 'Pimpinan DPRD')
@section('pretitle', 'Profil Instansi')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Pimpinan DPRD</h1>
        <button type="button" class="btn btn-cta btn-sm" id="btnTambahPimpinan" data-route-store="{{ route('admin.pimpinan-dprd.store') }}">+ Tambah</button>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="tabelPimpinan" class="table table-hover w-100" data-route-data="{{ route('admin.pimpinan-dprd.data') }}">
                <thead>
                    <tr><th>Foto</th><th>Nama</th><th>Jabatan</th><th>Urutan</th><th>Status</th><th>Aksi</th></tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalPimpinan" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formPimpinan" data-method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="">
                    <div class="modal-header">
                        <h5 class="modal-title">Pimpinan DPRD</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" placeholder="Ketua DPRD, Wakil Ketua, dll" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto <span class="text-secondary">(opsional, maks. 2MB)</span></label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Urutan</label>
                            <input type="number" name="urutan" class="form-control" value="0" min="0">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="aktif" value="1" class="form-check-input" id="aktifPimpinan" checked>
                            <label class="form-check-label" for="aktifPimpinan">Aktif</label>
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

    @vite(['resources/js/pages/admin/pimpinan-dprd.js'])
@endsection
