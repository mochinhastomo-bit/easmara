<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use App\Models\AuditLog;
use App\Models\Kategori;
use App\Models\Komisi;
use App\Models\StatusLog;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

#[Middleware('auth')]
class AspirasiAdminController extends Controller
{
    /**
     * Query dasar aspirasi, dibatasi cakupan sesuai role (Komisi cuma lihat miliknya).
     */
    private function scopedQuery()
    {
        $user = Auth::user();
        $query = Aspirasi::query()->with(['kategori', 'wilayah', 'komisi']);

        if ($user->role === 'komisi') {
            $query->whereHas('komisi', fn ($q) => $q->where('komisi.id', $user->komisi_id));
        }

        return $query;
    }

    public function index()
    {
        return view('admin.aspirasi.index');
    }

    public function data()
    {
        return DataTables::of($this->scopedQuery())
            ->addColumn('kategori_nama', function (Aspirasi $a) {
                if (! $a->kategori) {
                    return '—';
                }
                $warna = \App\Support\LabelWarna::warna($a->kategori->nama);

                return '<span class="badge bg-'.$warna.'-lt">'.e($a->kategori->nama).'</span>';
            })
            ->addColumn('wilayah_nama', fn (Aspirasi $a) => $a->wilayah?->nama ?? '—')
            ->addColumn('komisi_nama', function (Aspirasi $a) {
                if ($a->komisi->isEmpty()) {
                    return '—';
                }

                return $a->komisi->map(function ($k) {
                    $warna = \App\Support\LabelWarna::warna($k->nama);

                    return '<span class="badge bg-'.$warna.'-lt">'.e($k->nama).'</span>';
                })->implode(' ');
            })
            ->addColumn('status_badge', function (Aspirasi $a) {
                $warna = \App\Support\StatusWarna::verifikasi($a->status_verifikasi);
                $spam = $a->is_spam ? ' <span class="badge text-bg-warning">Spam</span>' : '';

                return '<span class="badge text-bg-'.$warna.'">'.ucfirst($a->status_verifikasi).'</span>'.$spam;
            })
            ->addColumn('aksi', fn (Aspirasi $a) => '<a href="'.route('admin.aspirasi.show', $a).'" class="btn btn-sm btn-outline-primary">Detail</a>')
            ->rawColumns(['kategori_nama', 'komisi_nama', 'status_badge', 'aksi'])
            ->editColumn('created_at', fn (Aspirasi $a) => $a->created_at->translatedFormat('d M Y'))
            ->toJson();
    }

    #[Authorize('view', 'aspirasi')]
    public function show(Aspirasi $aspirasi)
    {
        $aspirasi->load(['kategori', 'wilayah', 'komisi', 'statusLog.user', 'fotoBukti']);

        return view('admin.aspirasi.show', [
            'aspirasi' => $aspirasi,
            'komisiList' => Komisi::where('aktif', true)->orderBy('nama')->get(),
        ]);
    }

    public function verifikasi(Request $request, Aspirasi $aspirasi)
    {
        Gate::authorize('verifikasi', Aspirasi::class);

        $data = $request->validate([
            'status_verifikasi' => ['required', 'in:diverifikasi,ditolak'],
            'is_spam' => ['nullable', 'boolean'],
            'catatan' => ['nullable', 'string'],
        ]);

        $sebelum = $aspirasi->only(['status_verifikasi', 'is_spam']);

        $aspirasi->update([
            'status_verifikasi' => $data['status_verifikasi'],
            'is_spam' => $request->boolean('is_spam'),
        ]);

        StatusLog::create([
            'aspirasi_id' => $aspirasi->id,
            'user_id' => Auth::id(),
            'status_dari' => $sebelum['status_verifikasi'],
            'status_ke' => $data['status_verifikasi'],
            'catatan' => $data['catatan'] ?? null,
        ]);

        $this->catatAudit('verifikasi_aspirasi', $aspirasi, $sebelum, $aspirasi->only(['status_verifikasi', 'is_spam']));

        return response()->json(['message' => 'Status verifikasi berhasil diperbarui.']);
    }

    public function salurkan(Request $request, Aspirasi $aspirasi)
    {
        Gate::authorize('salurkan', Aspirasi::class);

        $data = $request->validate([
            'komisi_id' => ['required', 'array', 'min:1'],
            'komisi_id.*' => ['uuid', 'exists:komisi,id'],
        ]);

        DB::transaction(function () use ($aspirasi, $data) {
            foreach ($data['komisi_id'] as $komisiId) {
                $aspirasi->aspirasiKomisi()->firstOrCreate(['komisi_id' => $komisiId]);
            }

            $aspirasi->update([
                'disalurkan_oleh' => Auth::id(),
                'disalurkan_at' => now(),
            ]);

            StatusLog::create([
                'aspirasi_id' => $aspirasi->id,
                'user_id' => Auth::id(),
                'status_ke' => 'disalurkan',
                'catatan' => 'Disalurkan ke: '.Komisi::whereIn('id', $data['komisi_id'])->pluck('nama')->implode(', '),
            ]);
        });

        $this->catatAudit('salurkan_aspirasi', $aspirasi, null, ['komisi_id' => $data['komisi_id']]);

        return response()->json(['message' => 'Aspirasi berhasil disalurkan.']);
    }

    #[Authorize('updateTindakLanjut', 'aspirasi')]
    public function updateTindakLanjut(Request $request, Aspirasi $aspirasi)
    {
        $data = $request->validate([
            'aspirasi_komisi_id' => ['required', 'uuid', 'exists:aspirasi_komisi,id'],
            'status_tindak_lanjut' => ['required', 'in:diterima,ditunda,diproses,ditolak,selesai'],
            'catatan' => ['nullable', 'string'],
        ]);

        $pivot = $aspirasi->aspirasiKomisi()->findOrFail($data['aspirasi_komisi_id']);
        $sebelum = $pivot->status_tindak_lanjut;
        $pivot->update(['status_tindak_lanjut' => $data['status_tindak_lanjut']]);

        StatusLog::create([
            'aspirasi_id' => $aspirasi->id,
            'aspirasi_komisi_id' => $pivot->id,
            'user_id' => Auth::id(),
            'status_dari' => $sebelum,
            'status_ke' => $data['status_tindak_lanjut'],
            'catatan' => $data['catatan'] ?? null,
        ]);

        $this->catatAudit('update_tindak_lanjut', $aspirasi, ['status' => $sebelum], ['status' => $data['status_tindak_lanjut']]);

        return response()->json(['message' => 'Status tindak lanjut komisi berhasil diperbarui.']);
    }

    public function updatePimpinan(Request $request, Aspirasi $aspirasi)
    {
        Gate::authorize('tinjauPimpinan', Aspirasi::class);

        $data = $request->validate([
            'status_pimpinan' => ['required', 'in:menunggu,ditindaklanjuti,ditolak'],
            'catatan_pimpinan' => ['nullable', 'string'],
        ]);

        $sebelum = $aspirasi->only(['status_pimpinan', 'catatan_pimpinan']);
        $aspirasi->update($data);

        StatusLog::create([
            'aspirasi_id' => $aspirasi->id,
            'user_id' => Auth::id(),
            'status_dari' => $sebelum['status_pimpinan'],
            'status_ke' => 'pimpinan:'.$data['status_pimpinan'],
            'catatan' => $data['catatan_pimpinan'] ?? null,
        ]);

        $this->catatAudit('tinjau_pimpinan', $aspirasi, $sebelum, $data);

        return response()->json(['message' => 'Catatan Pimpinan berhasil disimpan.']);
    }

    private function catatAudit(string $aksi, Aspirasi $aspirasi, ?array $dataLama, ?array $dataBaru): void
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'aksi' => $aksi,
            'model_terkait' => Aspirasi::class,
            'model_id' => $aspirasi->id,
            'data_lama' => $dataLama,
            'data_baru' => $dataBaru,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
