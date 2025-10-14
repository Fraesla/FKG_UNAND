<?php

namespace App\Exports;

use App\Models\Prodi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProdiExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Prodi::with('jurusan')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Jurusan',
            'Nama Prodi',
        ];
    }

    public function map($prodi): array
    {
        return [
            $prodi->id,
            $prodi->jurusan ? $prodi->jurusan->nama : '-',
            $prodi->nama,
        ];
    }
}
