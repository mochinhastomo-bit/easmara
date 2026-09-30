<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\PimpinanDprd;
use App\Models\ProfilInstansi;

class ProfilController extends Controller
{
    public function show()
    {
        return view('publik.profil', [
            'bagianProfil' => ProfilInstansi::orderBy('updated_at')->get(),
            'pimpinan' => PimpinanDprd::where('aktif', true)->orderBy('urutan')->get(),
        ]);
    }
}
