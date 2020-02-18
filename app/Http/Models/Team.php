<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class Team extends DefaultModel
{
    protected $table = "teams";
    // public $cascadeDeletes = [];
    // public $restrictDeletes = [];
    protected $appends = ['code', 'f_created_at', 'last_update', 'f_flag'];

    public  $casts = [
        "flag" => "array"
    ];

    public function getLastUpdateAttribute()
    {
        if (!$this->updated_at) return $this->created_at->diffForHumans();
        return $this->updated_at->diffForHumans();
    }

    public function getFCreatedAtAttribute()
    {
        if (!$this->created_at) return;
        return @$this->created_at->format("d/m/Y - H:i:s");
    }

    public function getFFlagAttribute()
    {
        if (!$this->flag) return;
        return "<img class='avatar-rounded ' src='" . $this->flag[0] . "' />";
    }
}
