<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MahasiswaImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Mahasiswa([
            'nobp' => $row['nobp'], // pastikan kolom file Excel = "nobp"
            'nama' => $row['nama'], // pastikan kolom file Excel = "nama"
            'gender' => $row['gender'], // pastikan kolom file Excel = "gender"
            'ukt' => $row['ukt'], // pastikan kolom file Excel = "ukt"
            'id_tahun_ajaran' => $row['id_tahun_ajaran'], // pastikan kolom file Excel = "id_tahun_ajaran"
        ]);
    }
}
