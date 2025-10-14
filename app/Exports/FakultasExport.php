<?php

namespace App\Exports;

use App\Models\Fakultas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FakultasExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
       return Fakultas::select('id', 'nama', 'created_at', 'updated_at')->get();
    }
    /**
     * Tambahkan header kolom
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama Fakultas',
            'Dibuat Pada',
            'Diperbarui Pada',
        ];
    }
}
