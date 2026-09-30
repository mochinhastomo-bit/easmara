@extends('layouts.admin')

@section('title', 'Rekap & Laporan')
@section('pretitle', 'Laporan & Rekap')

@section('content')
    <h1 class="h4 mb-4">Rekap &amp; Laporan</h1>

    <div class="card mb-4">
        <div class="card-body">
            <form id="formFilter" class="row g-2 align-items-end"
                  data-route-data="{{ route('admin.rekap.data') }}"
                  data-route-export-excel="{{ route('admin.rekap.export-excel') }}"
                  data-route-export-pdf="{{ route('admin.rekap.export-pdf') }}">
                <div class="col-md-2">
                    <label class="form-label small">Dari Tanggal</label>
                    <input type="date" name="periode_dari" class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Sampai Tanggal</label>
                    <input type="date" name="periode_sampai" class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Kategori</label>
                    <select name="kategori_id" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        @foreach ($kategoriList as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Komisi</label>
                    <select name="komisi_id" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        @foreach ($komisiList as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Status</label>
                    <select name="status_verifikasi" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="menunggu">Menunggu</option>
                        <option value="diverifikasi">Diverifikasi</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-filter btn-sm w-100">Terapkan Filter</button>
                </div>
            </form>
            <div class="mt-2">
                <a href="#" id="linkExportExcel" class="btn btn-sm btn-outline-success">Export Excel</a>
                <a href="#" id="linkExportPdf" class="btn btn-sm btn-outline-danger">Cetak PDF</a>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4" id="kartuStatistik">
        <div class="col-md-3">
            <div class="card"><div class="card-body">
                <div class="text-secondary small">Total Aspirasi</div>
                <div class="h3 mb-0 text-primary" id="statTotal">—</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body">
                <div class="text-secondary small">Sudah Selesai (per Komisi)</div>
                <div class="h3 mb-0 text-success" id="statSelesai">—</div>
            </div></div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-3">
            <div class="card"><div class="card-header">Per Kategori</div><ul class="list-group list-group-flush" id="listKategori"></ul></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-header">Per Komisi</div><ul class="list-group list-group-flush" id="listKomisi"></ul></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-header">Per Kecamatan</div><ul class="list-group list-group-flush" id="listKecamatan"></ul></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-header">Per Status</div><ul class="list-group list-group-flush" id="listStatus"></ul></div>
        </div>
    </div>

    @vite(['resources/js/pages/admin/rekap.js'])
@endsection
