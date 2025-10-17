<?php

namespace App\Imports;

use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KelasImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Kelas([
            'id_prodi' => $row['id_prodi'], // pastikan kolom file Excel = "id_prodi"
            'nama' => $row['nama'], // pastikan kolom file Excel = "nama"
        ]);
    }
}