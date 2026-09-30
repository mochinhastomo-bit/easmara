<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlacklistNomor;
use App\Services\PhoneNormalizer;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

#[Middleware('auth')]
#[Middleware('role:administrator')]
class BlacklistNomorController extends Controller
{
    public function index()
    {
        return view('admin.blacklist.index');
    }

    public function data()
    {
        return DataTables::of(BlacklistNomor::with('diblokirOleh'))
            ->addColumn('no_hp', fn ($row) => $row->no_hp_enc)
            ->addColumn('diblokir_oleh_nama', fn ($row) => $row->diblokirOleh?->name ?? '—')
            ->addColumn('aksi', function ($row) {
                $url = route('admin.blacklist.destroy', $row->id);

                return '<button type="button" class="btn btn-sm btn-outline-danger btn-hapus-blacklist" data-url="'.$url.'">Hapus</button>';
            })
            ->rawColumns(['aksi'])
            ->toJson();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'no_hp' => ['required', 'string', 'min:9', 'max:15'],
            'alasan' => ['nullable', 'string'],
        ]);

        BlacklistNomor::create([
            'no_hp_enc' => PhoneNormalizer::normalize($data['no_hp']),
            'alasan' => $data['alasan'] ?? null,
            'diblokir_oleh' => Auth::id(),
        ]);

        return response()->json(['message' => 'Nomor berhasil diblokir.']);
    }

    public function destroy(string $id)
    {
        BlacklistNomor::findOrFail($id)->delete();

        return response()->json(['message' => 'Nomor berhasil dihapus dari blacklist.']);
    }
}
