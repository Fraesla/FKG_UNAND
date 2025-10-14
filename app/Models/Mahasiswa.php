<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';
    protected $fillable = ['nama']; // tambah kolom lain kalau ada

    public function mahasiswa()
    {
        return $this->hasMany(Nilai::class, 'id_mahasiswa');
    }
}
