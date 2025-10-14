<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jurusan extends Model
{
    use HasFactory;

    protected $table = 'jurusan';
    protected $fillable = ['id_fakultas', 'nama'];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'id_fakultas');
    }
}
