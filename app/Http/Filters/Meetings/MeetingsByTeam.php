<?php

namespace App\Http\Filters\Meetings;

use App\Http\Models\Team;
use  marcusvbda\vstack\Filter;

class MeetingsByTeam extends Filter
{

    public $component   = "select-filter";
    public $label       = "Time";
    public $placeholder = "";
    public $index = "meetings_by_team";

    public function __construct()
    {
        foreach (Team::get() as $row) {
            $this->options[] = (object) ["value" =>  strval($row->id), "label" => $row->name];
        }
        parent::__construct();
    }

    public function apply($query, $value)
    {
        return $query->whereIn("meetings.user_id", Team::find($value)->users->pluck("id")->toArray());
    }
}
