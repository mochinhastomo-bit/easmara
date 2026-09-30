<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Table(name: 'foto_bukti', keyType: 'string', incrementing: false)]
#[Fillable(['aspirasi_id', 'status_log_id', 'path_file', 'tipe', 'user_id'])]
class FotoBukti extends Model
{
    use HasUuids, SoftDeletes;

    public function aspirasi(): BelongsTo
    {
        return $this->belongsTo(Aspirasi::class);
    }

    public function statusLog(): BelongsTo
    {
        return $this->belongsTo(StatusLog::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Signed URL sementara (D4) -- Laravel 11+ mendukung ini native untuk disk 'local'
     * (visibility private, diverifikasi otomatis oleh route bawaan storage.local).
     */
    public function signedUrl(): string
    {
        return \Illuminate\Support\Facades\Storage::disk('local')->temporaryUrl(
            $this->path_file,
            now()->addMinutes(30)
        );
    }
}
