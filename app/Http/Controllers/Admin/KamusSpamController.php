<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KamusSpam;
use App\Models\UsulanKamusSpam;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

#[Middleware('auth')]
#[Middleware('role:administrator,verifikator')]
class KamusSpamController extends Controller
{
    public function index()
    {
        return view('admin.kamus-spam.index');
    }

    public function data()
    {
        return DataTables::of(KamusSpam::query())
            ->addColumn('status_badge', fn ($row) => $row->aktif
                ? '<span class="badge text-bg-success">Aktif</span>'
                : '<span class="badge text-bg-secondary">Nonaktif</span>')
            ->addColumn('aksi', function ($row) {
                $updateUrl = route('admin.kamus-spam.update', $row->id);
                $destroyUrl = route('admin.kamus-spam.destroy', $row->id);
                $kata = e($row->kata);

                return '<button type="button" class="btn btn-sm btn-outline-primary btn-edit-kamusspam" '
                    ."data-url=\"{$updateUrl}\" data-kata=\"{$kata}\" data-bobot_skor=\"{$row->bobot_skor}\" data-aktif=\"".($row->aktif ? 1 : 0).'">Ubah</button> '
                    .'<button type="button" class="btn btn-sm btn-outline-danger btn-hapus-kamusspam" '
                    ."data-url=\"{$destroyUrl}\" data-kata=\"{$kata}\">Hapus</button>";
            })
            ->rawColumns(['status_badge', 'aksi'])
            ->toJson();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kata' => ['required', 'string', 'max:100', 'unique:kamus_spam,kata'],
            'bobot_skor' => ['required', 'integer', 'min:1', 'max:100'],
        ]);
        $data['aktif'] = $request->boolean('aktif');
        KamusSpam::create($data);

        return response()->json(['message' => 'Kata berhasil ditambahkan ke kamus spam.']);
    }

    public function update(Request $request, string $id)
    {
        $item = KamusSpam::findOrFail($id);
        $data = $request->validate([
            'kata' => ['required', 'string', 'max:100', 'unique:kamus_spam,kata,'.$id],
            'bobot_skor' => ['required', 'integer', 'min:1', 'max:100'],
        ]);
        $data['aktif'] = $request->boolean('aktif');
        $item->update($data);

        return response()->json(['message' => 'Kamus spam berhasil diperbarui.']);
    }

    public function destroy(string $id)
    {
        KamusSpam::findOrFail($id)->delete();

        return response()->json(['message' => 'Kata berhasil dihapus dari kamus spam.']);
    }

    public function usulanData()
    {
        return DataTables::of(UsulanKamusSpam::with('diusulkanOleh')->where('status', 'pending'))
            ->addColumn('diusulkan_oleh_nama', fn ($row) => $row->diusulkanOleh?->name ?? '—')
            ->addColumn('aksi', function ($row) {
                $approveUrl = route('admin.kamus-spam.usulan.setujui', $row->id);
                $rejectUrl = route('admin.kamus-spam.usulan.tolak', $row->id);

                return '<button type="button" class="btn btn-sm btn-success btn-setujui-usulan" data-url="'.$approveUrl.'">Setujui</button> '
                    .'<button type="button" class="btn btn-sm btn-outline-danger btn-tolak-usulan" data-url="'.$rejectUrl.'">Tolak</button>';
            })
            ->rawColumns(['aksi'])
            ->toJson();
    }

    public function setujuiUsulan(string $id)
    {
        $usulan = UsulanKamusSpam::findOrFail($id);

        KamusSpam::firstOrCreate(
            ['kata' => $usulan->kata],
            ['bobot_skor' => 5, 'aktif' => true]
        );

        $usulan->update(['status' => 'disetujui', 'disetujui_oleh' => Auth::id()]);

        return response()->json(['message' => 'Usulan kata disetujui dan ditambahkan ke kamus spam.']);
    }

    public function tolakUsulan(string $id)
    {
        $usulan = UsulanKamusSpam::findOrFail($id);
        $usulan->update(['status' => 'ditolak', 'disetujui_oleh' => Auth::id()]);

        return response()->json(['message' => 'Usulan kata ditolak.']);
    }
}
