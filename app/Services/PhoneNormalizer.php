<?php

namespace App\Services;

class PhoneNormalizer
{
    /**
     * Normalisasi nomor HP ke format 628xxx (M2).
     */
    public static function normalize(string $raw): string
    {
        $digits = preg_replace('/\D/', '', $raw);

        if (str_starts_with($digits, '0')) {
            return '62'.substr($digits, 1);
        }

        if (str_starts_with($digits, '62')) {
            return $digits;
        }

        if (str_starts_with($digits, '8')) {
            return '62'.$digits;
        }

        return $digits;
    }

    /**
     * Samarkan nomor HP untuk tampilan publik (M2): 6281234567890 -> 6281****7890
     */
    public static function mask(string $normalized): string
    {
        if (strlen($normalized) < 8) {
            return str_repeat('*', strlen($normalized));
        }

        return substr($normalized, 0, 4).str_repeat('*', strlen($normalized) - 8).substr($normalized, -4);
    }
}
