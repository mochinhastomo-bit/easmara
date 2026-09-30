<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[Table(name: 'prosedur_layanan', keyType: 'string', incrementing: false)]
#[Fillable(['jenis', 'judul', 'konten', 'urutan'])]
class ProsedurLayanan extends Model
{
    use HasUuids;
}
