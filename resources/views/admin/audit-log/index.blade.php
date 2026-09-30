@extends('layouts.admin')

@section('title', 'Audit Log')
@section('pretitle', 'Sistem')

@section('content')
    <h1 class="h4 mb-4">Audit Log</h1>

    <div class="card">
        <div class="card-body">
            <table id="tabelAuditLog" class="table table-hover w-100" data-route-data="{{ route('admin.audit-log.data') }}">
                <thead>
                    <tr><th>Waktu</th><th>Pengguna</th><th>Aksi</th><th>Model</th><th>IP</th></tr>
                </thead>
            </table>
        </div>
    </div>

    @vite(['resources/js/pages/admin/audit-log.js'])
@endsection
