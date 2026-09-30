<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Table(keyType: 'string', incrementing: false)]
#[Fillable(['name', 'email', 'username', 'password', 'role', 'komisi_id', 'jabatan_komisi_id', 'aktif'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasUuids, Notifiable, SoftDeletes;

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'aktif' => 'boolean',
            'last_failed_login' => 'datetime',
        ];
    }

    public function isAdministrator(): bool
    {
        return $this->role === 'administrator';
    }

    public function isVerifikator(): bool
    {
        return $this->role === 'verifikator';
    }

    public function isPimpinan(): bool
    {
        return $this->role === 'pimpinan';
    }

    public function isKomisi(): bool
    {
        return $this->role === 'komisi';
    }

    public function komisi(): BelongsTo
    {
        return $this->belongsTo(Komisi::class);
    }

    public function jabatanKomisi(): BelongsTo
    {
        return $this->belongsTo(JabatanKomisi::class);
    }

    public function aspirasiDisalurkan(): HasMany
    {
        return $this->hasMany(Aspirasi::class, 'disalurkan_oleh');
    }

    public function statusLog(): HasMany
    {
        return $this->hasMany(StatusLog::class);
    }

    public function auditLog(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }
}
