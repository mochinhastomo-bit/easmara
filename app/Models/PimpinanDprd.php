<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[Table(name: 'pimpinan_dprd', keyType: 'string', incrementing: false)]
#[Fillable(['nama', 'jabatan', 'foto', 'urutan', 'aktif'])]
class PimpinanDprd extends Model
{
    use HasUuids;

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }
}
