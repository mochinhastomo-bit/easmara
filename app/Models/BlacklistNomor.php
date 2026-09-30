<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table(name: 'blacklist_nomor', keyType: 'string', incrementing: false)]
#[Fillable(['no_hp_enc', 'alasan', 'diblokir_oleh'])]
class BlacklistNomor extends Model
{
    use HasUuids;

    protected function casts(): array
    {
        return [
            'no_hp_enc' => 'encrypted',
        ];
    }

    public function diblokirOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diblokir_oleh');
    }
}
