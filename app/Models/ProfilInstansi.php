<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[Table(name: 'profil_instansi', keyType: 'string', incrementing: false)]
#[Fillable(['judul', 'konten'])]
class ProfilInstansi extends Model
{
    use HasUuids;

    const CREATED_AT = null;
}
