<?php

namespace App\Imports;

use App\Models\Nilai;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NilaiImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Nilai([
            'id_kelas' => $row['id_kelas'], // pastikan kolom file Excel = "id_kelas"
            'id_mahasiswa' => $row['id_mahasiswa'], // pastikan kolom file Excel = "id_mahasiswa"
            'id_dosen' => $row['id_dosen'], // pastikan kolom file Excel = "id_dosen"
            'nilai' => $row['nilai'], // pastikan kolom file Excel = "nilai"
        ]);
    }
}
