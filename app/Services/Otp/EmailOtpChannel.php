<?php

namespace App\Services\Otp;

use App\Contracts\OtpChannel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailOtpChannel implements OtpChannel
{
    public function kirim(string $tujuan, string $pesan): bool
    {
        try {
            Mail::raw(
                $pesan,
                fn ($message) => $message->to($tujuan)->subject('Kode Verifikasi e-ASMARA')
            );

            return true;
        } catch (\Throwable $e) {
            Log::error('EmailOtpChannel gagal kirim OTP: '.$e->getMessage());

            return false;
        }
    }
}
