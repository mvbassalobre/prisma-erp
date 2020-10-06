<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use marcusvbda\vstack\Models\Scopes\TenantScope;

class Birthday extends Model
{
    public $table = "view_birthday";

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(new TenantScope());
    }

    public function getBirthdayAttribute()
    {
        return STR_PAD($this->day, 2, 0, STR_PAD_LEFT) . "/" .  STR_PAD($this->month, 2, 0, STR_PAD_LEFT);
    }

    public function getFResourceAttribute()
    {
        return $this->resource == 'users' ? 'Colaborador' : 'Cliente';
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function getFNameAttribute()
    {
        return "<a href='/admin/" . $this->resource . "/" . \Hashids::encode($this->id) . "'>" . $this->name . "</a>";
    }
}
