<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

#[Middleware('auth')]
class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Aspirasi::query();

        if ($user->role === 'komisi') {
            $query->whereHas('komisi', fn ($q) => $q->where('komisi.id', $user->komisi_id));
        }

        $stats = [
            'total' => (clone $query)->count(),
            'menunggu' => (clone $query)->where('status_verifikasi', 'menunggu')->count(),
            'diverifikasi' => (clone $query)->where('status_verifikasi', 'diverifikasi')->count(),
            'ditolak' => (clone $query)->where('status_verifikasi', 'ditolak')->count(),
            'spam' => (clone $query)->where('is_spam', true)->count(),
        ];

        $terbaru = (clone $query)->with(['kategori', 'wilayah'])->latest()->limit(8)->get();

        $perHari = (clone $query)
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as jumlah')
            ->groupBy('tanggal')
            ->pluck('jumlah', 'tanggal');

        $labelTren = [];
        $dataTren = [];
        for ($i = 13; $i >= 0; $i--) {
            $tanggal = now()->subDays($i);
            $labelTren[] = $tanggal->translatedFormat('d M');
            $dataTren[] = (int) ($perHari[$tanggal->format('Y-m-d')] ?? 0);
        }

        return view('admin.dashboard', compact('stats', 'terbaru', 'labelTren', 'dataTren'));
    }
}
