<?php

namespace App\Exports;

use App\Models\Aspirasi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RekapAspirasiExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private readonly array $filters) {}

    public function collection(): Collection
    {
        return app(\App\Services\RekapFilterQuery::class)->apply($this->filters)
            ->with(['kategori', 'wilayah', 'komisi'])
            ->get();
    }

    public function headings(): array
    {
        return ['Kode Tiket', 'Tanggal', 'Kategori', 'Wilayah', 'Komisi', 'Status Verifikasi', 'Judul'];
    }

    public function map($aspirasi): array
    {
        return [
            $aspirasi->kode_tiket,
            $aspirasi->created_at->format('d-m-Y'),
            $aspirasi->kategori?->nama,
            $aspirasi->wilayah?->nama ?? '-',
            $aspirasi->komisi->pluck('nama')->implode(', ') ?: '-',
            ucfirst($aspirasi->status_verifikasi),
            $aspirasi->judul,
        ];
    }
}
