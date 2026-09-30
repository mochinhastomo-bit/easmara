<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[Table(name: 'otp_log', keyType: 'string', incrementing: false)]
#[Fillable(['tujuan', 'kode_tiket', 'channel', 'kode_otp_enc', 'status', 'percobaan', 'expired_at'])]
class OtpLog extends Model
{
    use HasUuids;

    const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'expired_at' => 'datetime',
        ];
    }
}
