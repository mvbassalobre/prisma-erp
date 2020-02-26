<?php

use App\Http\Models\Customer;
use Illuminate\Database\Seeder;
use Faker\Factory;

class CustomerSeeder extends Seeder
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
        DB::table("customers")->truncate();
        for ($i = 0; $i < 89; $i++) {
            Customer::create([
                "name"  => $faker->name,
                "email" => $faker->email,
                "profession" => $faker->text(15),
                "phone" => "(" . random_int(10, 20) . ") " . $faker->phone,
                "cellphone" => "(" . random_int(10, 20) . ") " . $faker->phone,
                "cpfcnpj" => $faker->cpf,
                "ierg" => $faker->rg,
                "date_exp_rg" => $faker->date,
                "exp_rg" => "SSP",
                "uf_rg" => "SP",
                "zipcode" => "000000",
                "street" => $faker->text(50),
                "number" => 15,
                "district" => $faker->text(20),
                "complement" => $faker->text(50),
                "state" => $faker->text(50),
                "city" => $faker->text(10),
                "bank_id"  => random_int(1, 3),
                "agency"  => "000",
                "account" => 111111111,
                "birthday" => $faker->date,
                "gender_id" => random_int(1, 2),
                "user_id" => random_int(2, 3),
                "tenant_id" => 2,
                "marital_status_id" => random_int(1, 2),
                "created_at" => $faker->date,
                "updated_at" => $faker->date,
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
