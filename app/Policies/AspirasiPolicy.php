<?php

namespace App\Policies;

use App\Models\Aspirasi;
use App\Models\User;

class AspirasiPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // semua role internal (administrator/verifikator/pimpinan/komisi) boleh lihat daftar
    }

    public function view(User $user, Aspirasi $aspirasi): bool
    {
        if ($user->role === 'komisi') {
            return $aspirasi->komisi->contains('id', $user->komisi_id);
        }

        return true;
    }

    /**
     * Verifikasi masuk + kategorisasi (M4).
     */
    public function verifikasi(User $user): bool
    {
        return in_array($user->role, ['administrator', 'verifikator'], true);
    }

    /**
     * Salurkan ke Komisi DPRD, termasuk lintas komisi (M4, D7) -- hanya Administrator.
     */
    public function salurkan(User $user): bool
    {
        return $user->role === 'administrator';
    }

    /**
     * Update status tindak lanjut oleh Komisi yang bersangkutan.
     */
    public function updateTindakLanjut(User $user, Aspirasi $aspirasi): bool
    {
        if ($user->role === 'administrator') {
            return true;
        }

        return $user->role === 'komisi' && $aspirasi->komisi->contains('id', $user->komisi_id);
    }

    /**
     * Catatan/tinjauan Pimpinan.
     */
    public function tinjauPimpinan(User $user): bool
    {
        return in_array($user->role, ['administrator', 'pimpinan'], true);
    }
}
