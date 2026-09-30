<?php

namespace App\Support;

/**
 * Satu-satunya tempat pemetaan warna badge status Aspirasi -- dipakai di
 * Dashboard, daftar Aspirasi (Yajra), dan detail Aspirasi supaya warnanya
 * selalu konsisten di semua halaman.
 */
class StatusWarna
{
    public static function verifikasi(string $status): string
    {
        return match ($status) {
            'diverifikasi' => 'success',
            'ditolak' => 'danger',
            default => 'secondary', // menunggu
        };
    }

    public static function tindakLanjut(string $status): string
    {
        return match ($status) {
            'diterima' => 'info',
            'diproses' => 'primary',
            'selesai' => 'success',
            'ditolak' => 'danger',
            'ditunda' => 'warning',
            default => 'secondary',
        };
    }

    public static function pimpinan(?string $status): string
    {
        return match ($status) {
            'ditindaklanjuti' => 'success',
            'ditolak' => 'danger',
            default => 'secondary', // menunggu / null
        };
    }
}
