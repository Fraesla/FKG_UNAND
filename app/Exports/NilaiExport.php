<?php

namespace App\Exports;

use App\Models\Nilai;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class NilaiExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Nilai::with('kelas','mahasiswa','dosen')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Blok',
            'Nama Mahasiswa',
            'Nama Dosen',
            'Nilai',
        ];
    }

    public function map($nilai): array
    {
        return [
            $nilai->id,
            $nilai->kelas ? $nilai->kelas->nama : '-',
            $nilai->mahasiswa ? $nilai->mahasiswa->nama : '-',
            $nilai->dosen ? $nilai->dosen->nama : '-',
            $nilai->nilai,
        ];
    }
}
