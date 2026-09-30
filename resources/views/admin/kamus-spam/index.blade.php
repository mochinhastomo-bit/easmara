@extends('layouts.admin')

@section('title', 'Kamus Spam')
@section('pretitle', 'Master Data')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Kamus Spam</h1>
        <button type="button" class="btn btn-cta btn-sm" id="btnTambahKamus" data-route-store="{{ route('admin.kamus-spam.store') }}">+ Tambah Kata</button>
    </div>

    <div class="card mb-4">
        <div class="card-header">Kata Kunci Spam Aktif</div>
        <div class="card-body">
            <table id="tabelKamusSpam" class="table table-hover w-100" data-route-data="{{ route('admin.kamus-spam.data') }}">
                <thead>
                    <tr><th>Kata</th><th>Bobot Skor</th><th>Status</th><th>Aksi</th></tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Usulan Kata Baru <span class="text-secondary small">(dari Verifikator)</span></div>
        <div class="card-body">
            <table id="tabelUsulanSpam" class="table table-hover w-100" data-route-data="{{ route('admin.kamus-spam.usulan.data') }}">
                <thead>
                    <tr><th>Kata Diusulkan</th><th>Diusulkan Oleh</th><th>Tanggal</th><th>Aksi</th></tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalKamus" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formKamus" data-method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="">
                    <div class="modal-header">
                        <h5 class="modal-title">Kata Kamus Spam</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Kata/Frasa</label>
                            <input type="text" name="kata" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bobot Skor</label>
                            <input type="number" name="bobot_skor" class="form-control" min="1" max="100" value="5" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="aktif" value="1" class="form-check-input" id="aktifKamus" checked>
                            <label class="form-check-label" for="aktifKamus">Aktif</label>
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

    @vite(['resources/js/pages/admin/kamus-spam.js'])
@endsection
