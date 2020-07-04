<?php
namespace App\Http\Models;
use marcusvbda\vstack\Models\DefaultModel;
class Meeting extends DefaultModel
{
    protected $table = "meetings";
    // public $cascadeDeletes = [];
    // public $restrictDeletes = [];
    // public $relations = []; //add relations that you want to load in resource field (ajax)
    public static function hasTenant() //default true
    {
        return true;
    }

    public function room(){
        return $this->belongsTo(MeetingRoom::class);
    }
}