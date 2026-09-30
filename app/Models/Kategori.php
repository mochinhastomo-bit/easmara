<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table(name: 'kategori', keyType: 'string', incrementing: false)]
#[Fillable(['nama', 'deskripsi', 'aktif'])]
class Kategori extends Model
{
    use HasUuids;

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    public function aspirasi(): HasMany
    {
        return $this->hasMany(Aspirasi::class);
    }

    public function pemetaanKomisi(): HasMany
    {
        return $this->hasMany(PemetaanKatKomisi::class);
    }
}
