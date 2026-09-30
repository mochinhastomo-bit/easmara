<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[Table(name: 'pesan_kontak', keyType: 'string', incrementing: false)]
#[Fillable(['nama', 'email', 'subjek', 'pesan', 'status'])]
class PesanKontak extends Model
{
    use HasUuids;
}
