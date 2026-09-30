<?php

namespace App\Services;

use App\Models\OtpLog;
use App\Models\PengaturanSistem;
use App\Models\RateLimitLog;
use App\Services\Otp\EmailOtpChannel;
use App\Services\Otp\WhatsappGresikChannel;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class OtpService
{
    const MAX_OTP_PER_HARI_DEFAULT = 3; // M2 -- dipakai kalau pengaturan 'otp_maks_per_hari' kosong

    const MASA_BERLAKU_MENIT_DEFAULT = 5; // dipakai kalau pengaturan 'otp_masa_berlaku_menit' kosong

    const MAX_PERCOBAAN_KODE = 5;

    public function __construct(
        private readonly WhatsappGresikChannel $whatsapp,
        private readonly EmailOtpChannel $email,
    ) {}

    /**
     * @return array{ok: bool, message: string, channel?: string}
     */
    public function requestOtp(string $noHpNormalized, ?string $email, string $ip, string $kodeTiket): array
    {
        $maksPerHari = (int) PengaturanSistem::ambil('otp_maks_per_hari', self::MAX_OTP_PER_HARI_DEFAULT);
        $masaBerlakuMenit = (int) PengaturanSistem::ambil('otp_masa_berlaku_menit', self::MASA_BERLAKU_MENIT_DEFAULT);

        $limiterKeyNomor = 'otp-nomor:'.$noHpNormalized;
        $limiterKeyIp = 'otp-ip:'.$ip;

        if (RateLimiter::tooManyAttempts($limiterKeyNomor, $maksPerHari)) {
            $this->catatRateLimit($noHpNormalized, 'otp');

            return ['ok' => false, 'message' => "Nomor ini sudah mencapai batas maksimal permintaan OTP hari ini (maks. {$maksPerHari}x/hari). Silakan coba lagi besok."];
        }

        if (RateLimiter::tooManyAttempts($limiterKeyIp, $maksPerHari * 5)) {
            $this->catatRateLimit($ip, 'otp');

            return ['ok' => false, 'message' => 'Terlalu banyak permintaan dari jaringan Anda. Silakan coba lagi nanti.'];
        }

        $kode = (string) random_int(100000, 999999);
        $pesan = $this->susunPesan($kode, $masaBerlakuMenit);

        // Coba jalur otomatis dulu (aktif begitu Diskominfo menyediakan kredensial API).
        $channel = 'whatsapp';
        $terkirim = $this->whatsapp->kirim($noHpNormalized, $pesan);

        if (! $terkirim && $email) {
            $channel = 'email';
            $terkirim = $this->email->kirim($email, $pesan);
        }

        // Belum ada jalur otomatis yang berhasil -- alihkan ke verifikasi WA manual:
        // pelapor mengirim kode_tiket ke wa_dinas_nomor, staf membalas kode ini secara
        // manual dari WhatsApp Dinas. Tetap dianggap "terkirim" karena kodenya sudah
        // tersimpan dan siap dibalaskan staf, hanya saluran pengirimannya yang berbeda.
        // Jalur ini hanya bisa ditawarkan kalau wa_dinas_nomor sudah diisi admin --
        // tanpa itu, tidak ada tujuan sama sekali untuk pelapor kirim WA verifikasi.
        if (! $terkirim) {
            if (blank(PengaturanSistem::ambil('wa_dinas_nomor'))) {
                return ['ok' => false, 'message' => 'Gagal mengirim kode OTP. Sistem verifikasi WA belum dikonfigurasi, silakan hubungi admin.'];
            }

            $channel = 'whatsapp_manual';
        }

        RateLimiter::hit($limiterKeyNomor, 86400);
        RateLimiter::hit($limiterKeyIp, 86400);
        $this->catatRateLimit($noHpNormalized, 'otp');

        OtpLog::create([
            'tujuan' => $channel === 'email' ? $email : $noHpNormalized,
            'kode_tiket' => $kodeTiket,
            'channel' => $channel,
            'kode_otp_enc' => Crypt::encryptString($kode),
            'status' => 'terkirim',
            'percobaan' => 0,
            'expired_at' => now()->addMinutes($masaBerlakuMenit),
        ]);

        $pesanHasil = match ($channel) {
            'whatsapp' => 'Kode OTP telah dikirim via WhatsApp.',
            'email' => 'Kode OTP telah dikirim via email.',
            'whatsapp_manual' => 'Kirim permohonan verifikasi via WhatsApp ke Dinas, kode OTP akan dibalas dari sana.',
        };

        return ['ok' => true, 'message' => $pesanHasil, 'channel' => $channel];
    }

    /**
     * Satu-satunya tempat teks pesan OTP disusun -- supaya "berlaku X menit"
     * yang dikirim ke warga selalu sinkron dengan expired_at yang sesungguhnya,
     * dan nomor WA Dinas ikut tampil otomatis begitu diisi di Pengaturan Sistem.
     */
    private function susunPesan(string $kode, int $masaBerlakuMenit): string
    {
        $pesan = "Kode verifikasi e-ASMARA Anda: {$kode}. Jangan bagikan kode ini kepada siapa pun. Berlaku {$masaBerlakuMenit} menit.";

        $nomorDinas = PengaturanSistem::ambil('wa_dinas_nomor');
        if (filled($nomorDinas)) {
            $pesan .= " Pesan ini resmi dari WA Dinas Kominfo Gresik ({$nomorDinas}).";
        }

        return $pesan;
    }

    /**
     * @return string 'ok'|'tidak_ditemukan'|'kadaluarsa'|'terlalu_banyak_percobaan'|'salah'
     */
    public function verify(string $tujuan, string $kode): string
    {
        $otp = OtpLog::where('tujuan', $tujuan)
            ->where('status', 'terkirim')
            ->orderByDesc('created_at')
            ->first();

        if (! $otp) {
            return 'tidak_ditemukan';
        }

        if ($otp->expired_at->isPast()) {
            $otp->update(['status' => 'kadaluarsa']);

            return 'kadaluarsa';
        }

        if ($otp->percobaan >= self::MAX_PERCOBAAN_KODE) {
            return 'terlalu_banyak_percobaan';
        }

        $kodeAsli = Crypt::decryptString($otp->kode_otp_enc);

        if (! hash_equals($kodeAsli, $kode)) {
            $otp->increment('percobaan');

            return 'salah';
        }

        $otp->update(['status' => 'terpakai']);

        return 'ok';
    }

    private function catatRateLimit(string $target, string $jenis): void
    {
        RateLimitLog::create([
            'target' => $target,
            'jenis' => $jenis,
            'jumlah_percobaan' => 1,
            'window_mulai' => now()->startOfDay(),
            'window_selesai' => now()->endOfDay(),
        ]);
    }
}
