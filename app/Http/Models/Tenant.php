<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class Tenant extends DefaultModel
{
    protected $table = "tenants";
    public $cascadeDeletes = ["settings", "users", "customers", "banks", "genders", "teams", "customer_product", "logos"];

    public $casts = [
        "big_logo" => "Array",
        "small_logo" => "Array",
    ];

    public static function hasTenant()
    {
        return false;
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function settings()
    {
        return $this->belongsToMany(Setting::class, 'setting_tenant');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function banks()
    {
        return $this->hasMany(Bank::class);
    }

    public function genders()
    {
        return $this->hasMany(Gender::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function customer_product()
    {
        return $this->hasMany(CustomerProduct::class);
    }

    public function getLogosAttribute()
    {
        if (@$this->small_logo[0] && @$this->big_logo[0])
            return "<div class='d-flex flew-row'><img class='avatar-rounded mr-2' src='" . @$this->small_logo[0] . "' /><img class='avatar-rounded' src='" . @$this->big_logo[0] . "' /></div>";
        if (@$this->small_logo[0])
            return "<div class='d-flex flew-row'><img class='avatar-rounded mr-2' src='" . @$this->small_logo[0] . "' /></div>";
        if (@$this->big_logo[0])
            return "<div class='d-flex flew-row'><img class='avatar-rounded mr-2' src='" . @$this->big_logo[0] . "' /></div>";
    }
}
