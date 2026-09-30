@extends('layouts.admin')

@section('title', 'Pengguna Internal')
@section('pretitle', 'Master Data')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Pengguna Internal</h1>
        <button type="button" class="btn btn-cta btn-sm" id="btnTambahPengguna" data-route-store="{{ route('admin.pengguna.store') }}">+ Tambah Pengguna</button>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="tabelPengguna" class="table table-hover w-100" data-route-data="{{ route('admin.pengguna.data') }}">
                <thead>
                    <tr><th>Nama</th><th>Email</th><th>Role</th><th>Komisi</th><th>Jabatan</th><th>Status</th><th>Aksi</th></tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalPengguna" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formPengguna" data-method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="">
                    <div class="modal-header">
                        <h5 class="modal-title">Pengguna</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username <span class="text-secondary">(opsional)</span></label>
                            <input type="text" name="username" class="form-control">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3" id="fieldPassword">
                            <label class="form-label">Kata Sandi</label>
                            <input type="text" name="password" class="form-control" minlength="8">
                            <div class="form-text">Kosongkan saat mengubah data untuk mempertahankan sandi lama.</div>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" id="selectRole" required>
                                <option value="administrator">Administrator</option>
                                <option value="verifikator">Verifikator</option>
                                <option value="pimpinan">Pimpinan</option>
                                <option value="komisi">Komisi</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3 d-none" id="fieldKomisi">
                            <label class="form-label">Komisi</label>
                            <select name="komisi_id" class="form-select">
                                <option value="">Pilih komisi&hellip;</option>
                                @foreach ($komisiList as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3 d-none" id="fieldJabatan">
                            <label class="form-label">Jabatan</label>
                            <select name="jabatan_komisi_id" class="form-select">
                                <option value="">Pilih jabatan&hellip;</option>
                                @foreach ($jabatanList as $j)
                                    <option value="{{ $j->id }}">{{ $j->nama }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="aktif" value="1" class="form-check-input" id="aktifPengguna" checked>
                            <label class="form-check-label" for="aktifPengguna">Aktif</label>
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

    @vite(['resources/js/pages/admin/pengguna.js'])
@endsection
