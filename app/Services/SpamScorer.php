<?php

namespace App\Services;

use App\Models\KamusSpam;
use App\Models\PengaturanSistem;

/**
 * Skor spam otomatis (M3) sebagai bantuan -- penetapan final tetap manual oleh
 * Verifikator/Administrator (context §M3: "Admin tetapkan spam/bukan spam manual").
 */
class SpamScorer
{
    const AMBANG_BATAS_DEFAULT = 10; // dipakai kalau pengaturan 'spam_ambang_skor' kosong

    /**
     * @return array{skor: int, is_spam: bool}
     */
    public function skor(string ...$teks): array
    {
        $gabungan = mb_strtolower(implode(' ', $teks));
        $kamus = KamusSpam::where('aktif', true)->get(['kata', 'bobot_skor']);

        $skor = 0;
        foreach ($kamus as $entri) {
            $kata = mb_strtolower($entri->kata);
            if ($kata !== '' && str_contains($gabungan, $kata)) {
                $skor += $entri->bobot_skor;
            }
        }

        $ambangBatas = (int) PengaturanSistem::ambil('spam_ambang_skor', self::AMBANG_BATAS_DEFAULT);

        return ['skor' => $skor, 'is_spam' => $skor >= $ambangBatas];
    }
}
