<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\BannerKonten;

class BerandaController extends Controller
{
    public function show()
    {
        return view('publik.beranda', [
            'bannerList' => BannerKonten::where('aktif', true)->orderBy('urutan')->get(),
        ]);
    }
}
