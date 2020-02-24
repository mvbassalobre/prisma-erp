<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class Tenant extends DefaultModel
{
    protected $table = "tenants";
    public $cascadeDeletes = ["settings", "users", "customers", "banks", "genders", "teams", "customer_product"];


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
}
