@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')
@section('pretitle', 'Master Data')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Pengaturan Sistem</h1>
        <button type="button" class="btn btn-cta btn-sm" id="btnTambahPengaturan" data-route-store="{{ route('admin.pengaturan.store') }}">+ Tambah Pengaturan</button>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="tabelPengaturan" class="table table-hover w-100" data-route-data="{{ route('admin.pengaturan.data') }}">
                <thead>
                    <tr><th>Key</th><th>Value</th><th>Keterangan</th><th>Aksi</th></tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalPengaturan" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formPengaturan" data-method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="">
                    <div class="modal-header">
                        <h5 class="modal-title">Pengaturan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Key</label>
                            <input type="text" name="key" class="form-control font-monospace" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Value</label>
                            <textarea name="value" class="form-control" rows="2"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <input type="text" name="keterangan" class="form-control">
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

    @vite(['resources/js/pages/admin/pengaturan.js'])
@endsection
