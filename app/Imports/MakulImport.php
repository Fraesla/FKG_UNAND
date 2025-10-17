<?php

namespace App\Imports;

use App\Models\Makul;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MakulImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Makul([
            'kode' => $row['kode'], // pastikan kolom file Excel = "kode"
            'nama' => $row['nama'], // pastikan kolom file Excel = "nama"
        ]);
    }
}
