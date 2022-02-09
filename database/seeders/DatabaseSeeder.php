<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        
        //empty tables
         Product::truncate(); User::truncate();   
        
        //populate tables
        $this->call([UserSeeder::class, ProductSeeder::class]);

        //DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
