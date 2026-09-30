<?php

namespace App\Support;

/**
 * Warna badge dekoratif untuk label bebas (nama Kategori, nama Komisi, dst.)
 * yang belum punya makna semantik tetap seperti status (lihat StatusWarna).
 * Warna dipilih deterministik dari hash nama, jadi otomatis konsisten setiap
 * kali label yang sama muncul, dan tetap jalan kalau ada Kategori/Komisi baru
 * tanpa perlu update kode.
 */
class LabelWarna
{
    private const PALET = ['azure', 'indigo', 'purple', 'pink', 'teal', 'cyan', 'lime', 'orange', 'red', 'yellow'];

    public static function warna(string $label): string
    {
        return self::PALET[crc32($label) % count(self::PALET)];
    }
}
