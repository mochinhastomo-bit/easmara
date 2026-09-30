<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\ProsedurLayanan;

class ProsedurController extends Controller
{
    public function show()
    {
        return view('publik.prosedur', [
            'prosedurOnline' => ProsedurLayanan::where('jenis', 'online')->orderBy('urutan')->get(),
            'prosedurOffline' => ProsedurLayanan::where('jenis', 'offline')->orderBy('urutan')->get(),
        ]);
    }
}
