<?php

namespace App\Contracts;

interface OtpChannel
{
    /**
     * Kirim $pesan (sudah disusun lengkap oleh OtpService) ke $tujuan.
     * Return true kalau berhasil dikirim (bukan berarti diterima).
     */
    public function kirim(string $tujuan, string $pesan): bool;
}
