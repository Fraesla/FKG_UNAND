<?php

namespace App\Models\admin\Akun;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model

class MahasiswaModels extends Model
{
    use HasFactory;

    protected $fillable = ['nim', 'nama', 'gender', 'tgl_lahir', 'alamat', 'no_hp', 'foto'];
}
