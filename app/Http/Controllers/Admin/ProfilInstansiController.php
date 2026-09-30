<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfilInstansi;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Yajra\DataTables\DataTables;

#[Middleware('auth')]
#[Middleware('role:administrator')]
class ProfilInstansiController extends Controller
{
    public function index()
    {
        return view('admin.profil-instansi.index');
    }

    public function data()
    {
        return DataTables::of(ProfilInstansi::query())
            ->addColumn('konten_ringkas', fn ($row) => \Illuminate\Support\Str::limit(strip_tags($row->konten ?? ''), 80))
            ->addColumn('aksi', function ($row) {
                $updateUrl = route('admin.profil-instansi.update', $row->id);
                $destroyUrl = route('admin.profil-instansi.destroy', $row->id);
                $judul = e($row->judul);

                return '<button type="button" class="btn btn-sm btn-outline-primary btn-edit-profil" '
                    ."data-url=\"{$updateUrl}\" data-judul=\"{$judul}\" data-konten=\"".e($row->konten ?? '').'">Ubah</button> '
                    .'<button type="button" class="btn btn-sm btn-outline-danger btn-hapus-profil" '
                    ."data-url=\"{$destroyUrl}\" data-judul=\"{$judul}\">Hapus</button>";
            })
            ->rawColumns(['aksi'])
            ->toJson();
    }

    private function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'konten' => ['nullable', 'string'],
        ];
    }

    public function store(Request $request)
    {
        ProfilInstansi::create($request->validate($this->rules()));

        return response()->json(['message' => 'Bagian profil berhasil ditambahkan.']);
    }

    public function update(Request $request, string $id)
    {
        ProfilInstansi::findOrFail($id)->update($request->validate($this->rules()));

        return response()->json(['message' => 'Bagian profil berhasil diperbarui.']);
    }

    public function destroy(string $id)
    {
        ProfilInstansi::findOrFail($id)->delete();

        return response()->json(['message' => 'Bagian profil berhasil dihapus.']);
    }
}
