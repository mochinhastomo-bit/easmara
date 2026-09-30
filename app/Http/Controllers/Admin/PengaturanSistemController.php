<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengaturanSistem;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Yajra\DataTables\DataTables;

#[Middleware('auth')]
#[Middleware('role:administrator')]
class PengaturanSistemController extends Controller
{
    public function index()
    {
        return view('admin.pengaturan.index');
    }

    public function data()
    {
        return DataTables::of(PengaturanSistem::query())
            ->addColumn('aksi', function ($row) {
                $updateUrl = route('admin.pengaturan.update', $row->id);
                $destroyUrl = route('admin.pengaturan.destroy', $row->id);
                $key = e($row->key);
                $value = e($row->value);
                $ket = e($row->keterangan ?? '');

                return '<button type="button" class="btn btn-sm btn-outline-primary btn-edit-pengaturan" '
                    ."data-url=\"{$updateUrl}\" data-key=\"{$key}\" data-value=\"{$value}\" data-keterangan=\"{$ket}\">Ubah</button> "
                    .'<button type="button" class="btn btn-sm btn-outline-danger btn-hapus-pengaturan" '
                    ."data-url=\"{$destroyUrl}\" data-key=\"{$key}\">Hapus</button>";
            })
            ->rawColumns(['aksi'])
            ->toJson();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key' => ['required', 'string', 'max:150', 'unique:pengaturan_sistem,key'],
            'value' => ['nullable', 'string'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);
        PengaturanSistem::create($data);

        return response()->json(['message' => 'Pengaturan berhasil ditambahkan.']);
    }

    public function update(Request $request, string $id)
    {
        $item = PengaturanSistem::findOrFail($id);
        $data = $request->validate([
            'key' => ['required', 'string', 'max:150', 'unique:pengaturan_sistem,key,'.$id],
            'value' => ['nullable', 'string'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);
        $item->update($data);

        return response()->json(['message' => 'Pengaturan berhasil diperbarui.']);
    }

    public function destroy(string $id)
    {
        PengaturanSistem::findOrFail($id)->delete();

        return response()->json(['message' => 'Pengaturan berhasil dihapus.']);
    }
}
