<?php

namespace App\Exports;

use App\Models\Jurusan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class JurusanExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Jurusan::with('fakultas')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Fakultas',
            'Nama Jurusan',
        ];
    }

    public function map($jurusan): array
    {
        return [
            $jurusan->id,
            $jurusan->fakultas ? $jurusan->fakultas->nama : '-',
            $jurusan->nama,
        ];
    }
}
