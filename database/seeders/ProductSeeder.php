<?php

namespace Database\Seeders;

use App\Http\Models\Product;
use Illuminate\Database\Seeder;
use Faker\Factory;
use DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $faker = Factory::create('pt_BR');
        DB::table("products")->truncate();
        for ($i = 0; $i < 20; $i++) {
            Product::create([
                "name"  => str_replace(".", "", $faker->text(25)),
                "price" => rand(1, 50) + (rand(1, 10) / 10),
                "tenant_id" => 2,
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
