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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table("setting_tenant")->truncate();
        DB::table("settings")->truncate();
        Setting::create([
            "name" => "Logo Pequeno",
            "type" => "image",
        ]);
        Setting::create([
            "name" => "Logo Grande",
            "type" => "image",
        ]);

        //true
        Setting::create([
            "name" => "Pagseguro Sandbox",
            "type" => "boolean",
        ]);
        //bassalobre.vinicius@gmail.com
        Setting::create([
            "name" => "Pagseguro Email",
            "type" => "text",
        ]);
        //5F58734FD5784A2691E2692B3BA2AC21
        Setting::create([
            "name" => "Pagseguro Token",
            "type" => "text",
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
