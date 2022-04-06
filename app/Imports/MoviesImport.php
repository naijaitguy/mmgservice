<?php

namespace App\Imports;

use App\Models\location;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToModel;

class MoviesImport implements ToModel ,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new location([
            //

            'lga' => $row['lga'],
            'state' => $row['state'],
        ]);
    }
}
