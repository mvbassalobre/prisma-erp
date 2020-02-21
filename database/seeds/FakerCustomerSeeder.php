<?php

use App\Http\Models\Customer;
use Illuminate\Database\Seeder;
use Faker\Factory;

class FakerCustomerSeeder extends Seeder
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
        for ($i = 0; $i < 50; $i++) {
            Customer::create([
                "name"  => $faker->name,
                "email" => $faker->email,
                "profession" => "lorem ipsum",
                "phone" => "(" . random_int(10, 20) . ") " . $faker->phone,
                "cellphone" => "(" . random_int(10, 20) . ") " . $faker->phone,
                "cpfcnpj" => $faker->cpf,
                "ierg" => $faker->rg,
                "date_exp_rg" => $faker->date,
                "exp_rg" => "SSP",
                "uf_rg" => "SP",
                "zipcode" => "000000",
                "street" => "lorem ipsum",
                "number" => 15,
                "district" => "ipsum",
                "complement" => "ipsum ipsum lorem",
                "state" => "lorem lorem",
                "city" => "tatuine",
                "bank_id"  => random_int(1, 3),
                "agency"  => "000",
                "account" => 111111111,
                "birthday" => $faker->date,
                "gender_id" => random_int(1, 2),
                "user_id" => random_int(2, 3),
                "tenant_id" => 1,
                "marital_status_id" => random_int(1, 2),
                "created_at" => $faker->date,
                "updated_at" => $faker->date,
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
