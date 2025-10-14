<?php

namespace App\Imports;

use App\Models\Prodi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProdiImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Prodi([
            'id_jurusan' => $row['id_jurusan'], // pastikan kolom file Excel = "id_jurusan"
            'nama' => $row['nama'], // pastikan kolom file Excel = "nama"
        ]);
    }
}
