@extends('layouts.admin')

@section('title', 'Daftar Aspirasi')
@section('pretitle', 'Manajemen Aspirasi')

@section('content')
    <h1 class="h4 mb-4">Daftar Aspirasi</h1>

    <div class="card">
        <div class="card-body">
            <table id="tabelAspirasi" class="table table-hover w-100" data-route-data="{{ route('admin.aspirasi.data') }}">
                <thead>
                    <tr>
                        <th>Kode Tiket</th>
                        <th>Pelapor</th>
                        <th>Kategori</th>
                        <th>Wilayah</th>
                        <th>Komisi</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @vite(['resources/js/pages/admin/aspirasi-index.js'])
@endsection
