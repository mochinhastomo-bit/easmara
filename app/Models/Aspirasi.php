<?php

namespace App\Models;

use App\Policies\AspirasiPolicy;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[UsePolicy(AspirasiPolicy::class)]
#[Table(name: 'aspirasi', keyType: 'string', incrementing: false)]
#[Fillable([
    'kode_tiket', 'kode_lama', 'nama_pelapor', 'nik', 'no_hp_enc', 'email',
    'wilayah_id', 'kategori_id', 'judul', 'uraian', 'alamat_detail', 'sumber',
    'status_verifikasi', 'is_spam', 'skor_spam', 'status_pimpinan', 'catatan_pimpinan',
    'disalurkan_oleh', 'disalurkan_at', 'ip_pengirim',
])]
class Aspirasi extends Model
{
    use HasUuids, SoftDeletes;

    protected function casts(): array
    {
        return [
            'no_hp_enc' => 'encrypted',
            'is_spam' => 'boolean',
            'disalurkan_at' => 'datetime',
        ];
    }

    public function wilayah(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class);
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function disalurkanOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disalurkan_oleh');
    }

    public function komisi(): BelongsToMany
    {
        return $this->belongsToMany(Komisi::class, 'aspirasi_komisi')
            ->withPivot('status_tindak_lanjut')
            ->withTimestamps();
    }

    public function aspirasiKomisi(): HasMany
    {
        return $this->hasMany(AspirasiKomisi::class);
    }

    public function statusLog(): HasMany
    {
        return $this->hasMany(StatusLog::class)->orderByDesc('created_at');
    }

    public function fotoBukti(): HasMany
    {
        return $this->hasMany(FotoBukti::class);
    }
}
