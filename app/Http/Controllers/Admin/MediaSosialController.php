<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaSosial;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Yajra\DataTables\DataTables;

#[Middleware('auth')]
#[Middleware('role:administrator')]
class MediaSosialController extends Controller
{
    public function index()
    {
        return view('admin.media-sosial.index');
    }

    public function data()
    {
        return DataTables::of(MediaSosial::query())
            ->addColumn('aksi', function ($row) {
                $updateUrl = route('admin.media-sosial.update', $row->id);
                $destroyUrl = route('admin.media-sosial.destroy', $row->id);
                $platform = e($row->platform);
                $url = e($row->url);
                $ikon = e($row->ikon ?? '');

                return '<button type="button" class="btn btn-sm btn-outline-primary btn-edit-mediasosial" '
                    ."data-url=\"{$updateUrl}\" data-platform=\"{$platform}\" data-link=\"{$url}\" data-ikon=\"{$ikon}\" data-urutan=\"{$row->urutan}\">Ubah</button> "
                    .'<button type="button" class="btn btn-sm btn-outline-danger btn-hapus-mediasosial" '
                    ."data-url=\"{$destroyUrl}\" data-platform=\"{$platform}\">Hapus</button>";
            })
            ->rawColumns(['aksi'])
            ->toJson();
    }

    private function rules(): array
    {
        return [
            'platform' => ['required', 'string', 'max:100'],
            'url' => ['required', 'url', 'max:255'],
            'ikon' => ['nullable', 'string', 'max:255'],
            'urutan' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function store(Request $request)
    {
        MediaSosial::create($request->validate($this->rules()));

        return response()->json(['message' => 'Media sosial berhasil ditambahkan.']);
    }

    public function update(Request $request, string $id)
    {
        MediaSosial::findOrFail($id)->update($request->validate($this->rules()));

        return response()->json(['message' => 'Media sosial berhasil diperbarui.']);
    }

    public function destroy(string $id)
    {
        MediaSosial::findOrFail($id)->delete();

        return response()->json(['message' => 'Media sosial berhasil dihapus.']);
    }
}
