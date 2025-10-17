<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';
    protected $fillable = ['id_prodi','nama']; // tambah kolom lain kalau ada

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_prodi');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi');
    }

    public function nilai()
    {
        return $this->belongsTo(Nilai::class, 'id_kelas');
    }
}
