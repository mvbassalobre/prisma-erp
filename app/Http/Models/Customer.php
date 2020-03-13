<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use marcusvbda\vstack\Models\Scopes\UserScope;
use marcusvbda\vstack\Models\Observers\UserObserver;
use Carbon\Carbon;
use marcusvbda\vstack\Models\Scopes\TenantScope;
use marcusvbda\vstack\Models\Observers\TenantObserver;
use Auth;

class Customer extends DefaultModel
{
    protected $table = "customers";
    public $cascadeDeletes = ["sales"];
    // public $restrictDeletes = [];
    protected $appends = ['code', 'f_created_at', 'last_update', 'phones', 'actions'];

    public $casts = [
        "timeline" => "Array"
    ];

    public static function hasTenant()
    {
        return false;
    }

    public static function boot()
    {
        $user = Auth::user();
        parent::boot();
        if (Auth::check()) {
            if ($user->hasRole(["admin", "user"])) {
                static::observe(new TenantObserver());
                static::addGlobalScope(new TenantScope());
            }
        }
        self::creating(function ($model) use ($user) {
            $user = $user ? $user->name : "root";
            $model->timeline = [[
                "title" => "Cadastro",
                "description" => "cadastrado no sistema por <b>$user</b>",
                "datetime" => Carbon::now()->format('d/m/Y - H:i:s')
            ]];
        });

        if (!$user) return;
        if ($user->hasRole(["super-admin", "admin"])) return;
        static::observe(new UserObserver());
        static::addGlobalScope(new UserScope());
    }

    public function getLastUpdateAttribute()
    {
        if (!$this->updated_at) return;
        return $this->updated_at->diffForHumans();
    }

    public function getActionsAttribute()
    {
        return "<div class='d-flex flex-column'>
                    <div>Atualizado " . $this->last_update . "</div>
                    <a href='" . route('admin.customers.attendance.index', ['code' => $this->code]) . "' class='crud-btns link d-flex align-items-center'>
                        <i class='el-icon-s-finance mr-1'></i> Atendimento
                    </a>
                </div>";
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function getFCreatedAtAttribute()
    {
        if (!$this->created_at) return;
        return @$this->created_at->format("d/m/Y - H:i:s");
    }

    public function getPhonesAttribute()
    {
        return "<p class='mb-0'>" . $this->phone . "</p><p class='mb-0'>" . $this->cellphone . "</p>";
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function maritalStatus()
    {
        return $this->belongsTo(MaritalStatus::class);
    }

    public function bank()
    {
        return $this->belongsTo(\Bank::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
