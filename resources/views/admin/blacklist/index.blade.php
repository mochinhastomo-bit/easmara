@extends('layouts.admin')

@section('title', 'Blacklist Nomor')
@section('pretitle', 'Master Data')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Blacklist Nomor HP</h1>
        <button type="button" class="btn btn-cta btn-sm" data-bs-toggle="modal" data-bs-target="#modalBlacklist">+ Blokir Nomor</button>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="tabelBlacklist" class="table table-hover w-100" data-route-data="{{ route('admin.blacklist.data') }}">
                <thead>
                    <tr><th>Nomor HP</th><th>Alasan</th><th>Diblokir Oleh</th><th>Tanggal</th><th>Aksi</th></tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalBlacklist" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formBlacklist" action="{{ route('admin.blacklist.store') }}" data-method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Blokir Nomor HP</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nomor HP</label>
                            <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alasan</label>
                            <textarea name="alasan" class="form-control" rows="2"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Blokir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @vite(['resources/js/pages/admin/blacklist.js'])
@endsection
