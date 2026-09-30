<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PesanKontak;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Yajra\DataTables\DataTables;

#[Middleware('auth')]
#[Middleware('role:administrator,verifikator')]
class PesanKontakController extends Controller
{
    public function index()
    {
        return view('admin.pesan-kontak.index');
    }

    public function data()
    {
        return DataTables::of(PesanKontak::query())
            ->editColumn('created_at', fn ($row) => $row->created_at->translatedFormat('d M Y, H:i'))
            ->addColumn('status_badge', function ($row) {
                $map = ['baru' => 'primary', 'dibaca' => 'secondary', 'ditindaklanjuti' => 'success'];

                return '<span class="badge text-bg-'.($map[$row->status] ?? 'secondary').'">'.ucfirst($row->status).'</span>';
            })
            ->addColumn('aksi', function ($row) {
                $showUrl = route('admin.pesan-kontak.show', $row->id);
                $updateUrl = route('admin.pesan-kontak.update-status', $row->id);
                $destroyUrl = route('admin.pesan-kontak.destroy', $row->id);

                return '<button type="button" class="btn btn-sm btn-outline-primary btn-lihat-pesan" data-url="'.$showUrl.'" data-update-url="'.$updateUrl.'">Lihat</button> '
                    .'<button type="button" class="btn btn-sm btn-outline-danger btn-hapus-pesan" data-url="'.$destroyUrl.'">Hapus</button>';
            })
            ->rawColumns(['status_badge', 'aksi'])
            ->toJson();
    }

    public function show(string $id)
    {
        $pesan = PesanKontak::findOrFail($id);

        if ($pesan->status === 'baru') {
            $pesan->update(['status' => 'dibaca']);
        }

        return response()->json([
            'nama' => $pesan->nama,
            'email' => $pesan->email,
            'subjek' => $pesan->subjek,
            'pesan' => $pesan->pesan,
            'status' => $pesan->status,
            'created_at' => $pesan->created_at->translatedFormat('d M Y, H:i'),
        ]);
    }

    public function updateStatus(Request $request, string $id)
    {
        $data = $request->validate(['status' => ['required', 'in:baru,dibaca,ditindaklanjuti']]);
        PesanKontak::findOrFail($id)->update($data);

        return response()->json(['message' => 'Status pesan berhasil diperbarui.']);
    }

    public function destroy(string $id)
    {
        PesanKontak::findOrFail($id)->delete();

        return response()->json(['message' => 'Pesan berhasil dihapus.']);
    }
}
