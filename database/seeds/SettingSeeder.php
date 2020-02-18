<?php

use Illuminate\Database\Seeder;
use App\Http\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            "name" => "Lorem Ipsum",
            "type" => "boolean",
            "default" => "true",
            "description" => "Phasellus mollis urna eget dui tincidunt, non ultricies nunc suscipit. Mauris bibendum lectus sit amet tincidunt rhoncus. Integer sed viverra magna"
        ]);
    }
}
