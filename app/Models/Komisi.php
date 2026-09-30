<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table(name: 'komisi', keyType: 'string', incrementing: false)]
#[Fillable(['nama', 'deskripsi', 'aktif'])]
class Komisi extends Model
{
    use HasUuids;

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function pemetaanKategori(): HasMany
    {
        return $this->hasMany(PemetaanKatKomisi::class);
    }

    public function aspirasi(): BelongsToMany
    {
        return $this->belongsToMany(Aspirasi::class, 'aspirasi_komisi')
            ->withPivot('status_tindak_lanjut')
            ->withTimestamps();
    }
}
