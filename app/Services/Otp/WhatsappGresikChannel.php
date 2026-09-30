<?php

namespace App\Services\Otp;

use App\Contracts\OtpChannel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Klien API WhatsApp resmi Pemkab Gresik (§5 CONTEXT_EASMARA.md).
 *
 * BELUM ada endpoint/kredensial resmi dari Diskominfo saat modul ini ditulis --
 * konfigurasi di bawah adalah PLACEHOLDER. Isi WHATSAPP_GRESIK_BASE_URL dan
 * WHATSAPP_GRESIK_TOKEN di .env begitu Diskominfo menyediakannya; payload
 * (nomor_tujuan, isi_pesan, tipe_pesan) mengikuti kontrak yang sudah disepakati
 * di context, tapi field/response API sesungguhnya perlu disesuaikan saat
 * dokumentasi API resmi didapat.
 */
class WhatsappGresikChannel implements OtpChannel
{
    public function kirim(string $tujuan, string $pesan): bool
    {
        $baseUrl = config('services.whatsapp_gresik.base_url');
        $token = config('services.whatsapp_gresik.token');

        if (blank($baseUrl) || blank($token)) {
            Log::warning('WhatsappGresikChannel: kredensial belum dikonfigurasi, OTP tidak terkirim via WA.', [
                'tujuan' => $tujuan,
            ]);

            return false;
        }

        try {
            $response = Http::withToken($token)
                ->timeout(10)
                ->post($baseUrl.'/kirim-pesan', [
                    'nomor_tujuan' => $tujuan,
                    'isi_pesan' => $pesan,
                    'tipe_pesan' => 'otp',
                ]);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('WhatsappGresikChannel gagal kirim OTP: '.$e->getMessage());

            return false;
        }
    }
}
