<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table("roles")->truncate();
        Role::create(["name" => "super-admin"]); //1
        Role::create(["name" => "admin"]); // 2
        Role::create(["name" => "user"]); //3
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
