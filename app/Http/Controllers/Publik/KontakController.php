<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\PesanKontak;
use App\Rules\Recaptcha;
use Illuminate\Http\Request;

class KontakController extends Controller
{
    public function show()
    {
        return view('publik.kontak');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'subjek' => ['nullable', 'string', 'max:255'],
            'pesan' => ['required', 'string', 'min:10'],
            'recaptcha_token' => [
                config('recaptcha.site_key') ? 'required' : 'nullable',
                new Recaptcha('kirim_kontak'),
            ],
        ]);

        PesanKontak::create([
            'nama' => $data['nama'],
            'email' => $data['email'] ?? null,
            'subjek' => $data['subjek'] ?? null,
            'pesan' => $data['pesan'],
            'status' => 'baru',
        ]);

        return redirect()->route('kontak')->with('status', 'Pesan Anda berhasil terkirim. Terima kasih.');
    }
}
