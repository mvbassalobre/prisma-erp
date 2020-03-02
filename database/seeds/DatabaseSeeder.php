<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RolesSeeder::class,
            UserSeeder::class,
            SettingSeeder::class,
            AuxiliarTableSeeder::class,
            // CustomerSeeder::class,
            // ProductSeeder::class,
        ]);
    }
}
