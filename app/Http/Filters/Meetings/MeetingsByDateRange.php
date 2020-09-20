<?php

namespace App\Http\Filters\Meetings;

use  marcusvbda\vstack\Filter;

class MeetingsByDateRange extends Filter
{

    public $component   = "rangedate-filter";
    public $label       = "Periodo da Reunião";
    public $placeholder = "";
    public $index = "meetings_by_date_range";

    public function apply($query, $value)
    {
        $dates = explode(",", $value);
        return $query->whereRaw("(DATE(meetings.starts_at) >= DATE('{$dates[0]}') and DATE(meetings.starts_at) <= DATE('{$dates[1]}'))");
    }
}
