<?php

namespace Database\Seeders;
use App\Models\wallettype;
use Illuminate\Database\Seeder;

class account_type_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

       wallettype::create([

        'name'=>'savings'
       ]);

       wallettype::create([

        'name'=>'current'
       ]);
    }
}
