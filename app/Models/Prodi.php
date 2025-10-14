<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prodi extends Model
{
    use HasFactory;

    protected $table = 'prodi';
    protected $fillable = ['id_jurusan', 'nama'];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan','id');
    }
}
