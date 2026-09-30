<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table(name: 'status_log', keyType: 'string', incrementing: false)]
#[Fillable(['aspirasi_id', 'aspirasi_komisi_id', 'user_id', 'status_dari', 'status_ke', 'catatan'])]
class StatusLog extends Model
{
    use HasUuids;

    const UPDATED_AT = null;

    public function aspirasi(): BelongsTo
    {
        return $this->belongsTo(Aspirasi::class);
    }

    public function aspirasiKomisi(): BelongsTo
    {
        return $this->belongsTo(AspirasiKomisi::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fotoBukti(): HasMany
    {
        return $this->hasMany(FotoBukti::class);
    }
}
