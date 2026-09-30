<?php

namespace App\Http\Controllers\Admin;

use App\Exports\RekapAspirasiExport;
use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Komisi;
use App\Services\RekapFilterQuery;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Maatwebsite\Excel\Facades\Excel;

#[Middleware('auth')]
class RekapController extends Controller
{
    private const FILTER_KEYS = ['periode_dari', 'periode_sampai', 'kategori_id', 'komisi_id', 'wilayah_id', 'status_verifikasi'];

    public function __construct(private readonly RekapFilterQuery $filterQuery) {}

    public function index()
    {
        return view('admin.rekap.index', [
            'kategoriList' => Kategori::orderBy('nama')->get(),
            'komisiList' => Komisi::orderBy('nama')->get(),
        ]);
    }

    private function filters(Request $request): array
    {
        return $request->only(self::FILTER_KEYS);
    }

    private function ambilData(array $filters)
    {
        return $this->filterQuery->apply($filters)
            ->with(['kategori', 'wilayah.parent', 'komisi'])
            ->get();
    }

    public function data(Request $request)
    {
        $items = $this->ambilData($this->filters($request));

        return response()->json([
            'total' => $items->count(),
            'per_kategori' => $items->groupBy(fn ($a) => $a->kategori?->nama ?? 'Lainnya')->map->count(),
            'per_status' => $items->groupBy('status_verifikasi')->map->count(),
            'per_komisi' => $items->flatMap->komisi->groupBy('nama')->map->count(),
            'per_kecamatan' => $items->groupBy(function ($a) {
                if (! $a->wilayah) {
                    return 'Belum dipetakan';
                }

                return $a->wilayah->jenis === 'kecamatan' ? $a->wilayah->nama : ($a->wilayah->parent?->nama ?? 'Belum dipetakan');
            })->map->count(),
            'selesai' => $items->flatMap->komisi->where('pivot.status_tindak_lanjut', 'selesai')->count(),
        ]);
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new RekapAspirasiExport($this->filters($request)), 'rekap-aspirasi-'.now()->format('Ymd-His').'.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $filters = $this->filters($request);
        $items = $this->ambilData($filters);

        $pdf = Pdf::loadView('admin.rekap.pdf', [
            'items' => $items,
            'filters' => $filters,
            'dicetakOleh' => $request->user(),
            'tanggalCetak' => now(),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('rekap-aspirasi-'.now()->format('Ymd-His').'.pdf');
    }
}
