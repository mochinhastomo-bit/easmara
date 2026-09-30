<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OtpLog;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Crypt;

/**
 * Loket verifikasi WA manual (D-lanjutan): daftar permohonan verifikasi yang
 * masuk lewat WhatsApp ke wa_dinas_nomor, belum ada API WA Business resmi
 * jadi staf membalas kode OTP secara manual dari HP/aplikasi WhatsApp Dinas.
 */
#[Middleware('auth')]
#[Middleware('role:administrator,verifikator')]
class VerifikasiWaController extends Controller
{
    public function index()
    {
        $daftar = OtpLog::where('channel', 'whatsapp_manual')
            ->where('status', 'terkirim')
            ->where('expired_at', '>', now())
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (OtpLog $otp) => [
                'kode_tiket' => $otp->kode_tiket,
                'no_hp' => $otp->tujuan,
                'kode_otp' => Crypt::decryptString($otp->kode_otp_enc),
                'kadaluarsa_pada' => $otp->expired_at,
                'percobaan' => $otp->percobaan,
            ]);

        return view('admin.verifikasi-wa.index', ['daftar' => $daftar]);
    }
}
