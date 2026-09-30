<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[Table(name: 'media_sosial', keyType: 'string', incrementing: false)]
#[Fillable(['platform', 'url', 'ikon', 'urutan'])]
class MediaSosial extends Model
{
    use HasUuids;
}
