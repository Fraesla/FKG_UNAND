<?php

namespace App\Imports;

use App\Models\Jurusan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JurusanImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Jurusan([
            'id_fakultas' => $row['id_fakultas'], // pastikan kolom file Excel = "id_fakultas"
            'nama' => $row['nama'], // pastikan kolom file Excel = "nama"
        ]); 
    }
}
