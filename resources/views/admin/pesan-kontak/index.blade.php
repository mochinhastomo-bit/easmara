@extends('layouts.admin')

@section('title', 'Pesan Kontak')
@section('pretitle', 'Profil Instansi')

@section('content')
    <h1 class="h4 mb-4">Pesan Kontak</h1>

    <div class="card">
        <div class="card-body">
            <table id="tabelPesan" class="table table-hover w-100" data-route-data="{{ route('admin.pesan-kontak.data') }}">
                <thead>
                    <tr><th>Nama</th><th>Subjek</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalPesan" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pesan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <dl class="row small mb-3">
                        <dt class="col-sm-3">Nama</dt><dd class="col-sm-9" id="pesanNama"></dd>
                        <dt class="col-sm-3">Email</dt><dd class="col-sm-9" id="pesanEmail"></dd>
                        <dt class="col-sm-3">Subjek</dt><dd class="col-sm-9" id="pesanSubjek"></dd>
                        <dt class="col-sm-3">Tanggal</dt><dd class="col-sm-9" id="pesanTanggal"></dd>
                    </dl>
                    <p style="white-space: pre-wrap;" id="pesanIsi"></p>
                    <div class="mb-0">
                        <label class="form-label small">Status</label>
                        <select id="pesanStatusSelect" class="form-select form-select-sm">
                            <option value="baru">Baru</option>
                            <option value="dibaca">Dibaca</option>
                            <option value="ditindaklanjuti">Ditindaklanjuti</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="btnSimpanStatusPesan">Simpan Status</button>
                </div>
            </div>
        </div>
    </div>

    @vite(['resources/js/pages/admin/pesan-kontak.js'])
@endsection
