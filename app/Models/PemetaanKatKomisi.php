<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table(name: 'pemetaan_kat_komisi', keyType: 'string', incrementing: false)]
#[Fillable(['kategori_id', 'komisi_id'])]
class PemetaanKatKomisi extends Model
{
    use HasUuids;

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function komisi(): BelongsTo
    {
        return $this->belongsTo(Komisi::class);
    }
}
