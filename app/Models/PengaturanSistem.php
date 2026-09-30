<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[Table(name: 'pengaturan_sistem', keyType: 'string', incrementing: false)]
#[Fillable(['key', 'value', 'keterangan'])]
class PengaturanSistem extends Model
{
    use HasUuids;

    const CREATED_AT = null;

    /**
     * Ambil satu nilai pengaturan berdasarkan key. Kalau belum diisi admin
     * (atau baris-nya dihapus), otomatis jatuh ke $default -- supaya aplikasi
     * tetap jalan aman walau tabel ini kosong.
     */
    public static function ambil(string $key, mixed $default = null): mixed
    {
        return static::where('key', $key)->value('value') ?? $default;
    }
}
