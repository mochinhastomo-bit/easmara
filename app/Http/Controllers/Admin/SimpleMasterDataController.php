<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

#[Middleware('auth')]
#[Middleware('role:administrator')]
abstract class SimpleMasterDataController extends Controller
{
    protected string $model;

    protected string $slug;

    protected string $label;

    protected string $viewIndex;

    public function index()
    {
        return view($this->viewIndex);
    }

    public function data()
    {
        return DataTables::of(($this->model)::query())
            ->addColumn('status_badge', fn ($row) => $row->aktif
                ? '<span class="badge text-bg-success">Aktif</span>'
                : '<span class="badge text-bg-secondary">Nonaktif</span>')
            ->addColumn('aksi', fn ($row) => $this->renderAksi($row))
            ->rawColumns(['status_badge', 'aksi'])
            ->toJson();
    }

    protected function renderAksi($row): string
    {
        $updateUrl = route("admin.{$this->slug}.update", $row->id);
        $destroyUrl = route("admin.{$this->slug}.destroy", $row->id);
        $nama = e($row->nama);
        $deskripsi = e($row->deskripsi ?? '');
        $aktif = $row->aktif ? '1' : '0';

        return <<<HTML
            <button type="button" class="btn btn-sm btn-outline-primary btn-edit-{$this->slug}"
                    data-url="{$updateUrl}" data-nama="{$nama}" data-deskripsi="{$deskripsi}" data-aktif="{$aktif}">Ubah</button>
            <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-{$this->slug}"
                    data-url="{$destroyUrl}" data-nama="{$nama}">Hapus</button>
        HTML;
    }

    protected function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['aktif'] = $request->boolean('aktif');
        ($this->model)::create($data);

        return response()->json(['message' => "{$this->label} berhasil ditambahkan."]);
    }

    public function update(Request $request, string $id)
    {
        $item = ($this->model)::findOrFail($id);
        $data = $request->validate($this->rules());
        $data['aktif'] = $request->boolean('aktif');
        $item->update($data);

        return response()->json(['message' => "{$this->label} berhasil diperbarui."]);
    }

    public function destroy(string $id)
    {
        try {
            ($this->model)::findOrFail($id)->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => "{$this->label} ini masih dipakai data lain dan tidak bisa dihapus. Nonaktifkan saja.",
            ], 422);
        }

        return response()->json(['message' => "{$this->label} berhasil dihapus."]);
    }
}
