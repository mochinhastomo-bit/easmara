<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[Table(name: 'rate_limit_log', keyType: 'string', incrementing: false)]
#[Fillable(['target', 'jenis', 'jumlah_percobaan', 'window_mulai', 'window_selesai'])]
class RateLimitLog extends Model
{
    use HasUuids;

    const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'window_mulai' => 'datetime',
            'window_selesai' => 'datetime',
        ];
    }
}
