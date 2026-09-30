<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[Table(name: 'kamus_spam', keyType: 'string', incrementing: false)]
#[Fillable(['kata', 'bobot_skor', 'aktif'])]
class KamusSpam extends Model
{
    use HasUuids;

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }
}
