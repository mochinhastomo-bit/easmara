<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PimpinanDprd;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

#[Middleware('auth')]
#[Middleware('role:administrator')]
class PimpinanDprdController extends Controller
{
    public function index()
    {
        return view('admin.pimpinan-dprd.index');
    }

    public function data()
    {
        return DataTables::of(PimpinanDprd::query())
            ->addColumn('foto_url', fn ($row) => $row->foto ? Storage::disk('public')->url($row->foto) : null)
            ->addColumn('status_badge', fn ($row) => $row->aktif
                ? '<span class="badge text-bg-success">Aktif</span>'
                : '<span class="badge text-bg-secondary">Nonaktif</span>')
            ->addColumn('aksi', function ($row) {
                $updateUrl = route('admin.pimpinan-dprd.update', $row->id);
                $destroyUrl = route('admin.pimpinan-dprd.destroy', $row->id);
                $nama = e($row->nama);

                return '<button type="button" class="btn btn-sm btn-outline-primary btn-edit-pimpinan" '
                    ."data-url=\"{$updateUrl}\" data-nama=\"{$nama}\" data-jabatan=\"".e($row->jabatan)."\" data-urutan=\"{$row->urutan}\" data-aktif=\"".($row->aktif ? 1 : 0).'">Ubah</button> '
                    .'<button type="button" class="btn btn-sm btn-outline-danger btn-hapus-pimpinan" '
                    ."data-url=\"{$destroyUrl}\" data-nama=\"{$nama}\">Hapus</button>";
            })
            ->rawColumns(['status_badge', 'aksi'])
            ->toJson();
    }

    private function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'jabatan' => ['required', 'string', 'max:150'],
            'foto' => ['nullable', 'image', 'max:2048'],
            'urutan' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['aktif'] = $request->boolean('aktif');

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('pimpinan-dprd', 'public');
        }

        PimpinanDprd::create($data);

        return response()->json(['message' => 'Pimpinan DPRD berhasil ditambahkan.']);
    }

    public function update(Request $request, string $id)
    {
        $item = PimpinanDprd::findOrFail($id);
        $data = $request->validate($this->rules());
        $data['aktif'] = $request->boolean('aktif');

        if ($request->hasFile('foto')) {
            if ($item->foto) {
                Storage::disk('public')->delete($item->foto);
            }
            $data['foto'] = $request->file('foto')->store('pimpinan-dprd', 'public');
        }

        $item->update($data);

        return response()->json(['message' => 'Pimpinan DPRD berhasil diperbarui.']);
    }

    public function destroy(string $id)
    {
        $item = PimpinanDprd::findOrFail($id);
        if ($item->foto) {
            Storage::disk('public')->delete($item->foto);
        }
        $item->delete();

        return response()->json(['message' => 'Pimpinan DPRD berhasil dihapus.']);
    }
}
