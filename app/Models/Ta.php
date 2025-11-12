<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ta extends Model
{
    use HasFactory;

    protected $table = 'ta';
    protected $fillable = ['id_mahasiswa','id_dosen','tgl_bimbingan','catatan'];
}
