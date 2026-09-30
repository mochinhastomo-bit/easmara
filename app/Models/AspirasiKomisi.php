<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table(name: 'aspirasi_komisi', keyType: 'string', incrementing: false)]
#[Fillable(['aspirasi_id', 'komisi_id', 'status_tindak_lanjut'])]
class AspirasiKomisi extends Model
{
    use HasUuids;

    public function aspirasi(): BelongsTo
    {
        return $this->belongsTo(Aspirasi::class);
    }

    public function komisi(): BelongsTo
    {
        return $this->belongsTo(Komisi::class);
    }

    public function statusLog(): HasMany
    {
        return $this->hasMany(StatusLog::class);
    }
}
