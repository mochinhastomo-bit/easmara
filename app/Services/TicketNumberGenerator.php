<?php

namespace App\Services;

use App\Models\Aspirasi;

class TicketNumberGenerator
{
    /**
     * Format ASM-YYYY-XXXXX, unik & acak/tidak berurutan (D3).
     */
    public static function generate(): string
    {
        do {
            $kode = sprintf('ASM-%d-%05d', now()->year, random_int(0, 99999));
        } while (Aspirasi::withTrashed()->where('kode_tiket', $kode)->exists());

        return $kode;
    }
}
