<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';
    protected $fillable = ['nama']; // tambah kolom lain kalau ada

    public function dosen()
    {
        return $this->hasMany(Nilai::class, 'id_dosen');
    }
}
