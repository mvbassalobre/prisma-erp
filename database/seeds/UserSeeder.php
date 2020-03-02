<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Http\Models\Tenant;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table("tenants")->truncate();
        DB::table("users")->truncate();
        $tenant = Tenant::create(["name" => "App"]);
        $user = User::create([
            "name"     => "Vinicius Bassalobre",
            "email"    => "bassalobre.vinicius@gmail.com",
            "password" => "v1n1c1u5",
            "email_verified_at" => date("Y-m-d H:i:s"),
            "tenant_id" => $tenant->id
        ]);
        $user->assignRole("super-admin");
        Tenant::create(["name" => "Activc"]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
