<?php

use App\Http\Models\Meeting;
use App\Http\Resources\MeetingStatuses;
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
            CustomerSeeder::class,
            ProductSeeder::class,
            MeetingStatusSeed::class,
        ]);
    }
}
