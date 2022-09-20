<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Http\Models\MeetingStatus;
use DB;

class MeetingStatusSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table("meeting_statuses")->truncate();
        MeetingStatus::create(["name" => "Agendada", "color" => "#06ace8", "tenant_id" => 2]);
        MeetingStatus::create(["name" => "Cancelada", "color" => "#ff0000", "tenant_id" => 2]);
        MeetingStatus::create(["name" => "Realizada", "color" => "#04ff00", "tenant_id" => 2]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
