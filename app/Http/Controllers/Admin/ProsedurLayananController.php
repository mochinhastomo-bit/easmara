<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProsedurLayanan;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Yajra\DataTables\DataTables;

#[Middleware('auth')]
#[Middleware('role:administrator')]
class ProsedurLayananController extends Controller
{
    public function index()
    {
        return view('admin.prosedur.index');
    }

    public function data()
    {
        return DataTables::of(ProsedurLayanan::query())
            ->addColumn('jenis_badge', fn ($row) => $row->jenis === 'online'
                ? '<span class="badge text-bg-info">Online</span>'
                : '<span class="badge text-bg-secondary">Offline</span>')
            ->addColumn('aksi', function ($row) {
                $updateUrl = route('admin.prosedur.update', $row->id);
                $destroyUrl = route('admin.prosedur.destroy', $row->id);
                $judul = e($row->judul);

                return '<button type="button" class="btn btn-sm btn-outline-primary btn-edit-prosedur" '
                    ."data-url=\"{$updateUrl}\" data-jenis=\"{$row->jenis}\" data-judul=\"{$judul}\" data-konten=\"".e($row->konten ?? '')."\" data-urutan=\"{$row->urutan}\">Ubah</button> "
                    .'<button type="button" class="btn btn-sm btn-outline-danger btn-hapus-prosedur" '
                    ."data-url=\"{$destroyUrl}\" data-judul=\"{$judul}\">Hapus</button>";
            })
            ->rawColumns(['jenis_badge', 'aksi'])
            ->toJson();
    }

    private function rules(): array
    {
        return [
            'jenis' => ['required', 'in:online,offline'],
            'judul' => ['required', 'string', 'max:255'],
            'konten' => ['nullable', 'string'],
            'urutan' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function store(Request $request)
    {
        ProsedurLayanan::create($request->validate($this->rules()));

        return response()->json(['message' => 'Prosedur layanan berhasil ditambahkan.']);
    }

    public function update(Request $request, string $id)
    {
        ProsedurLayanan::findOrFail($id)->update($request->validate($this->rules()));

        return response()->json(['message' => 'Prosedur layanan berhasil diperbarui.']);
    }

    public function destroy(string $id)
    {
        ProsedurLayanan::findOrFail($id)->delete();

        return response()->json(['message' => 'Prosedur layanan berhasil dihapus.']);
    }
}
