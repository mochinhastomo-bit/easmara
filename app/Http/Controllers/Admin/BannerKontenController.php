<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BannerKonten;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

#[Middleware('auth')]
#[Middleware('role:administrator')]
class BannerKontenController extends Controller
{
    public function index()
    {
        return view('admin.banner-konten.index');
    }

    public function data()
    {
        return DataTables::of(BannerKonten::query())
            ->addColumn('gambar_url', fn ($row) => Storage::disk('public')->url($row->gambar))
            ->addColumn('status_badge', fn ($row) => $row->aktif
                ? '<span class="badge text-bg-success">Aktif</span>'
                : '<span class="badge text-bg-secondary">Nonaktif</span>')
            ->addColumn('aksi', function ($row) {
                $updateUrl = route('admin.banner-konten.update', $row->id);
                $destroyUrl = route('admin.banner-konten.destroy', $row->id);
                $judul = e($row->judul ?? '');

                return '<button type="button" class="btn btn-sm btn-outline-primary btn-edit-banner" '
                    ."data-url=\"{$updateUrl}\" data-judul=\"{$judul}\" data-url_tujuan=\"".e($row->url_tujuan ?? '')."\" data-urutan=\"{$row->urutan}\" data-aktif=\"".($row->aktif ? 1 : 0).'">Ubah</button> '
                    .'<button type="button" class="btn btn-sm btn-outline-danger btn-hapus-banner" '
                    ."data-url=\"{$destroyUrl}\" data-judul=\"{$judul}\">Hapus</button>";
            })
            ->rawColumns(['status_badge', 'aksi'])
            ->toJson();
    }

    private function rules(): array
    {
        return [
            'judul' => ['nullable', 'string', 'max:255'],
            'url_tujuan' => ['nullable', 'string', 'max:255'],
            'urutan' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function store(Request $request)
    {
        $request->validate([...$this->rules(), 'gambar' => ['required', 'image', 'max:2048']]);
        $data = $request->only(['judul', 'url_tujuan', 'urutan']);
        $data['aktif'] = $request->boolean('aktif');
        $data['gambar'] = $request->file('gambar')->store('banner-konten', 'public');

        BannerKonten::create($data);

        return response()->json(['message' => 'Banner berhasil ditambahkan.']);
    }

    public function update(Request $request, string $id)
    {
        $item = BannerKonten::findOrFail($id);
        $request->validate([...$this->rules(), 'gambar' => ['nullable', 'image', 'max:2048']]);
        $data = $request->only(['judul', 'url_tujuan', 'urutan']);
        $data['aktif'] = $request->boolean('aktif');

        if ($request->hasFile('gambar')) {
            Storage::disk('public')->delete($item->gambar);
            $data['gambar'] = $request->file('gambar')->store('banner-konten', 'public');
        }

        $item->update($data);

        return response()->json(['message' => 'Banner berhasil diperbarui.']);
    }

    public function destroy(string $id)
    {
        $item = BannerKonten::findOrFail($id);
        Storage::disk('public')->delete($item->gambar);
        $item->delete();

        return response()->json(['message' => 'Banner berhasil dihapus.']);
    }
}
