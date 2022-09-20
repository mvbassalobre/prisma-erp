<?php

namespace Database\Seeders;

use App\Http\Models\MeetingRoom;
use Illuminate\Database\Seeder;
use DB;

class MeetingRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table("meeting_rooms")->truncate();
        MeetingRoom::create([
            "tenant_id" => 2,
            "city" => "Duque de Caxias",
            "district" => "Vila Itamarati",
            "name" => "Sala no Rio",
            "number" => "26",
            "phones" => "(11) 95232-8892",
            "reference" => "perto do hospital SASE",
            "size" => "3",
            "state" => "Rio de Janeiro",
            "street" => "Itamaracá",
            "zipcode" => "25070-200",
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
