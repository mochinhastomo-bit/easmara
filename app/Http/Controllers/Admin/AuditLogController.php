<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Yajra\DataTables\DataTables;

#[Middleware('auth')]
#[Middleware('role:administrator')]
class AuditLogController extends Controller
{
    public function index()
    {
        return view('admin.audit-log.index');
    }

    public function data()
    {
        return DataTables::of(AuditLog::with('user'))
            ->addColumn('user_nama', fn ($row) => $row->user?->name ?? 'Sistem/Tamu')
            ->addColumn('created_at_fmt', fn ($row) => $row->created_at?->translatedFormat('d M Y, H:i:s'))
            ->orderColumn('created_at_fmt', 'created_at $1')
            ->toJson();
    }
}
