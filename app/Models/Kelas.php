<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';
    protected $fillable = ['nama']; // tambah kolom lain kalau ada

    public function kelas()
    {
        return $this->hasMany(Nilai::class, 'id_kelas');
    }
}
