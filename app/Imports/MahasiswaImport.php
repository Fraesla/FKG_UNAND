<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class MahasiswaImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
        public function model(array $row)
        {
            // Skip baris kosong
            if (!isset($row['nobp']) || !isset($row['nama']) || 
                empty($row['nobp']) || empty($row['nama'])) 
            {
                return null;
            }

            // Insert mahasiswa
            $mahasiswa = Mahasiswa::create([
                'nobp' => $row['nobp'],
                'nama' => $row['nama'],
                'gender' => NUll,
                'contact' => NUll,
                'alamat' => NUll,
                'id_tahun_ajaran' => NUll,
                'foto' => Null,
            ]);

            // Cek hanya berdasarkan username = nobp
            $cek = DB::table('user')->where('username', $row['nobp'])->first();

            if (!$cek) {
                DB::table('user')->insert([
                    'username' => $row['nobp'],
                    'password' => bcrypt('Unand2025'),
                    'level'    => 'mahasiswa', // perbaiki typo
                    'status'   => '0',
                ]);
            }

            return $mahasiswa;
        }
}
