<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Http\Traits\hasCode;
use Spatie\Permission\Traits\HasRoles;
use Auth;
use App\Http\Models\Tenant;
use App\Http\Models\Setting;
use marcusvbda\vstack\Models\Scopes\TenantScope;
use marcusvbda\vstack\Models\Observers\TenantObserver;

class User extends Authenticatable implements MustVerifyEmail
{
    use SoftDeletes, Notifiable, hasCode, HasRoles;
    public $guarded = ['id'];
    protected $appends = ['code', 'roleName', 'f_created_at', 'f_active', 'last_update', 'tenantName'];
    public  $casts = [
        "active" => "boolean",
        "avatar" => "array"
    ];

    public static function boot()
    {
        parent::boot();
        if (Auth::check()) {
            if (Auth::user()->hasRole(["admin", "user"])) {
                static::observe(new TenantObserver());
                static::addGlobalScope(new TenantScope());
            }
        }
    }

    public function getCodeAttribute()
    {
        return \Hashids::encode($this->id);
    }

    public function receivesBroadcastNotificationsOn()
    {
        return 'App.User.' . $this->id;
    }

    public function setPasswordAttribute($val)
    {
        $this->attributes["password"] = bcrypt($val);
    }

    public function setNameAttribute($val)
    {
        $this->attributes["name"] = $val;
        if (Auth::check()) $this->attributes["tenant_id"] = Auth::user()->tenant_id;
    }

    public function getRoleNameAttribute()
    {
        $roles = $this->roles;
        return @$roles[0]->name;
    }

    public function getFActiveAttribute()
    {
        return $this->active ? '<span class="badge badge-primary">Ativo</span>' : '<span class="badge badge-danger">Inativo</span>';
    }

    public function getLastUpdateAttribute()
    {
        if (!$this->updated_at) return;
        return $this->updated_at->diffForHumans();
    }

    public function getFCreatedAtAttribute()
    {
        if (!$this->created_at) return;
        return @$this->created_at->format("d/m/Y - H:i:s");
    }

    public function getSettings($index = null)
    {
        $settings = Setting::all();
        $values = [];
        $tenant = $this->tenant;
        if (!$index) $index = $settings->pluck("slug")->toArray();
        if (is_array($index)) {
            $indexes = $index;
            if (count($indexes) <= 0) $indexes = $settings;
            foreach ($indexes as $index) {
                $setting = Setting::where("slug", $index)->first();
                if ($setting) {
                    $tenant_setting = @$tenant->settings()->where("setting_id", $setting->id)->select("settings.*", "setting_value")->first();
                    $values[$setting->slug] = $this->processSettingValue($tenant_setting, $setting);
                }
            }
        } else {
            $setting = Setting::where("slug", $index)->first();
            $tenant_setting = @$tenant->settings()->where("setting_id", $setting->id)->select("settings.*", "setting_value")->first();
            return $this->processSettingValue($tenant_setting, $setting);
        }
        return $values;
    }

    private function processSettingValue($tenant_setting, $setting)
    {
        if (!$setting) return null;
        $value   = @$tenant_setting ? $tenant_setting->setting_value : $setting->default;
        if ($setting->type == "boolean") $value = $value == "true";
        if ($setting->type == "integer") $value = $value ? (int) $value : null;
        if ($setting->type == "float") $value = $value ? floatval($value) : null;
        $values[$setting->slug] = $value;
        return $value;
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function getTenantNameAttribute()
    {
        if (!$this->tenant) return;
        return $this->tenant->name;
    }

    public function teams()
    {
        return $this->belongsToMany(\App\Http\Models\Team::class, "user_team");
    }
}
