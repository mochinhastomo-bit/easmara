<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use Illuminate\Http\Request;

class AspirasiStatusController extends Controller
{
    public function form()
    {
        return view('publik.aspirasi.status');
    }

    public function cari(Request $request)
    {
        $request->validate([
            'kode_tiket' => ['required', 'string'],
        ]);

        $aspirasi = Aspirasi::with(['kategori', 'wilayah', 'komisi', 'statusLog'])
            ->where('kode_tiket', trim($request->kode_tiket))
            ->first();

        if (! $aspirasi) {
            return back()->withErrors(['kode_tiket' => 'Nomor tiket tidak ditemukan. Periksa kembali penulisannya.']);
        }

        return view('publik.aspirasi.status-hasil', ['aspirasi' => $aspirasi]);
    }
}
