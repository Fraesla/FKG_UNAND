<?php

namespace App\Models\admin\Akun;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model

class DosenModels extends Model
{
    use HasFactory;

    protected $fillable = ['nidm', 'nama', 'gender', 'tgl_lahir', 'alamat', 'no_hp', 'foto'];
}
