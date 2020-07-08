<?php

namespace App\Http\Filters\Meetings;

use App\Http\Models\MeetingRoom;
use  marcusvbda\vstack\Filter;

class MeetingsByRoom extends Filter
{

    public $component   = "select-filter";
    public $label       = "Sala";
    public $placeholder = "";
    public $index = "meetings_by_room";

    public function __construct()
    {
        foreach (MeetingRoom::get() as $room) {
            $this->options[] = (object) ["value" =>  strval($room->id), "label" => $room->name];
        }
        parent::__construct();
    }

    public function apply($query, $value)
    {
        return $query->where("meeting_room_id", $value);
    }
}
