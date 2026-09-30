<?php

namespace App\Services;

use App\Models\Aspirasi;
use Illuminate\Database\Eloquent\Builder;

class RekapFilterQuery
{
    public function apply(array $filters): Builder
    {
        $query = Aspirasi::query();

        if (! empty($filters['periode_dari'])) {
            $query->whereDate('created_at', '>=', $filters['periode_dari']);
        }

        if (! empty($filters['periode_sampai'])) {
            $query->whereDate('created_at', '<=', $filters['periode_sampai']);
        }

        if (! empty($filters['kategori_id'])) {
            $query->where('kategori_id', $filters['kategori_id']);
        }

        if (! empty($filters['komisi_id'])) {
            $query->whereHas('komisi', fn ($q) => $q->where('komisi.id', $filters['komisi_id']));
        }

        if (! empty($filters['wilayah_id'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('wilayah_id', $filters['wilayah_id'])
                    ->orWhereHas('wilayah', fn ($w) => $w->where('parent_id', $filters['wilayah_id']));
            });
        }

        if (! empty($filters['status_verifikasi'])) {
            $query->where('status_verifikasi', $filters['status_verifikasi']);
        }

        return $query;
    }
}
