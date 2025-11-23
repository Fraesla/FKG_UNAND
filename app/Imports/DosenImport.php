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
       // 1. Insert data dosen
        $dosen = Dosen::create([
            'nama' => $row['nama'],
            'nip' => $row['nip'],
            'nidn' => $row['nidn'],
            'gender' => $row['gender'],
            'pangol' => $row['pangol'],
            'napater' => $row['napater'],
            'napaber' => $row['napaber'],
            'jf' => $row['jf'],
            'js' => $row['js'],
            'najater' => $row['najater'],
            'penter' => $row['penter'],
            'keterangan' => $row['keterangan'],
        ]);

        // 2. Cek apakah user sudah ada
        $cek = DB::table('user')->where('username', $row['nip'])->first();

        if (!$cek) {
            // 3. Buat akun user otomatis
            DB::table('user')->insert([
                'username' => $row['nip'],      // Gunakan NIP dari Excel
                'password' => bcrypt('Unand2025'),
                'level'    => 'dosen',
                'status'   => '0',
            ]);
        }

        return $dosen;
    }
}
