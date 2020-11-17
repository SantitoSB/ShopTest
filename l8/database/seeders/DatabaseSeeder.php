<?php

namespace Database\Seeders;

use App\Models\category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        category::factory(8)->create();
        Product::factory(24)->create();

        //$this->call(ProductTableSeeder::class);
    }
}
