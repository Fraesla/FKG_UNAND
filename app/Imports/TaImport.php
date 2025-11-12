<?php

namespace App\Imports;

use App\Models\Ta;
use Maatwebsite\Excel\Concerns\ToModel;

class TaImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Ta([
            //
        ]);
    }
}
