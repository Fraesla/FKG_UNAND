<?php

namespace App\Imports;

use App\Models\Dosen;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DosenImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Dosen([
            'nama' => $row['nama'], // pastikan kolom file Excel = "nama"
            'nip' => $row['nip'], // pastikan kolom file Excel = "nip"
            'nidn' => $row['nidn'], // pastikan kolom file Excel = "nidn"
            'gender' => $row['gender'], // pastikan kolom file Excel = "gender"
            'pangol' => $row['pangol'], // pastikan kolom file Excel = "pangol"
            'napater' => $row['napater'], // pastikan kolom file Excel = "napater"
            'napaber' => $row['napaber'], // pastikan kolom file Excel = "napaber"
            'jf' => $row['jf'], // pastikan kolom file Excel = "jf"
            'js' => $row['js'], // pastikan kolom file Excel = "js"
            'najater' => $row['najater'], // pastikan kolom file Excel = "najater"
            'penter' => $row['penter'], // pastikan kolom file Excel = "penter"
            'keterangan' => $row['keterangan'], // pastikan kolom file Excel = "keterangan"
        ]);
    }
}
