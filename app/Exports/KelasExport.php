<?php

namespace App\Exports;

use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KelasExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Kelas::with('prodi')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Prodi',
            'Nama Blok',
        ];
    }

    public function map($blok): array
    {
        return [
            $blok->id,
            $blok->prodi ? $blok->prodi->nama : '-',
            $blok->nama,
        ];
    }
}
