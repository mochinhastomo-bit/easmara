<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[Table(name: 'banner_konten', keyType: 'string', incrementing: false)]
#[Fillable(['judul', 'gambar', 'url_tujuan', 'urutan', 'aktif'])]
class BannerKonten extends Model
{
    use HasUuids;

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }
}
