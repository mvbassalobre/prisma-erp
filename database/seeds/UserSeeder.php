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
        $tenant = Tenant::create(["name" => "Lorem Ipsum"]);
        $user = User::create([
            "name"     => "superadmin",
            "email"    => "superadmin@email.com",
            "password" => "superadmin",
            "email_verified_at" => date("Y-m-d H:i:s"),
            "tenant_id" => $tenant->id
        ]);
        $user->assignRole("super-admin");
        $user = User::create([
            "name"     => "admin",
            "email"    => "admin@email.com",
            "password" => "admin",
            "email_verified_at" => date("Y-m-d H:i:s"),
            "tenant_id" => $tenant->id
        ]);
        $user->assignRole("admin");

        $user = User::create([
            "name"     => "user",
            "email"    => "user@email.com",
            "password" => "user",
            "email_verified_at" => date("Y-m-d H:i:s"),
            "tenant_id" => $tenant->id
        ]);
        $user->assignRole("user");
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
