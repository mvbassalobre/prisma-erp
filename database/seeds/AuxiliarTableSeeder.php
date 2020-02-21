<?php

use Illuminate\Database\Seeder;
use App\Http\Models\{
    Gender,
    MaritalStatus,
    Bank,
};

class AuxiliarTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createGenders();
        $this->createMaritalStatuses();
        $this->createBanks();
    }

    private function createGenders()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table("genders")->truncate();
        foreach (["Masculino", "Feminino", "Outro"] as $value) {
            Gender::create([
                "name" => $value,
                "tenant_id" => 1
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function createMaritalStatuses()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table("marital_statuses")->truncate();
        foreach (["Solteiro(a)", "Casado(a)", "Divorciado(a)", "Viúvo(a)"] as $value) {
            MaritalStatus::create([
                "name" => $value,
                "tenant_id" => 1
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function createBanks()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table("banks")->truncate();
        $banks = [
            ["number" => "341", "name" => "BANCO ITAÚ S.A."],
            ["number" => "008", "name" => "BANCO SANTANDER BANESPA S.A."],
            ["number" => "237", "name" => "BANCO BRADESCO S.A."]
        ];
        foreach ($banks as $value) {
            Bank::create([
                "name" => $value["name"],
                "number" => $value["number"],
                "tenant_id" => 1
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
