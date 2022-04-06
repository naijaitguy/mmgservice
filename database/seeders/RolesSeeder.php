<?php

namespace Database\Seeders;
use App\Models\role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

       role::create([

        'role'=>'user'
       ]);

       role::create([

        'role'=>'admin'
       ]);
    }
}
