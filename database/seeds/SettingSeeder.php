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
            "name" => "Mensagem Dashboard",
            "type" => "text",
            "default" => "Olá, aqui no dashboard você verá um informativo resumido a respeito de sua empresa",
            "description" => "Está mensagem aparecerá no topo do dashboard toda vez que for acessado"
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
