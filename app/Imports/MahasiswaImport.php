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
        // 1. Insert data dosen
        $dosen = Dosen::create([
            'nobp' => $row['nobp'],
            'nama' => $row['nama'],
            'gender' => $row['gender'],
            'ukt' => $row['ukt'],
            'id_tahun_ajaran' => $row['id_tahun_ajaran'],
        ]);

        // 2. Cek apakah user sudah ada
        $cek = DB::table('user')->where('username', $row['nobp'])->first();

        if (!$cek) {
            // 3. Buat akun user otomatis
            DB::table('user')->insert([
                'username' => $row['nobp'],      // Gunakan No.BP dari Excel
                'password' => bcrypt('Unand2025'),
                'level'    => 'dosen',
                'status'   => '0',
            ]);
        }

        return $dosen;
    }
}
