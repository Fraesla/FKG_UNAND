<?php

namespace App\Imports;

use App\Models\Dosen;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class DosenImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
        {
            // Skip baris kosong
            if (!isset($row['nip']) || !isset($row['nama']) || 
                empty($row['nip']) || empty($row['nama'])) 
            {
                return null;
            }

            // Insert dosen
            $dosen = Dosen::create([
                'nip' => $row['nip'],
                'nama' => $row['nama'],
                'gender' => Null,
                'contact' => Null,
                'alamat' => Null,
                'foto' => Null,
            ]);

            // Cek hanya berdasarkan username = nip
            $cek = DB::table('user')->where('username', $row['nip'])->first();

            if (!$cek) {
                DB::table('user')->insert([
                    'username' => $row['nip'],
                    'password' => bcrypt('Unand2025'),
                    'level'    => 'dosen', // perbaiki typo
                    'status'   => '0',
                ]);
            }

            return $dosen;
        }
}
