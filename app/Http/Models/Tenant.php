<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class Tenant extends DefaultModel
{
	protected $table = "tenants";
	public $cascadeDeletes = ["pagseguro_url_notification", "settings", "users", "customers", "banks", "genders", "teams", "customer_product", 'f_created_at', 'last_update', 'code'];

	public static function hasTenant()
	{
		return false;
	}

	public function getPagseguroUrlNotificationAttribute()
	{
		return route("pagseguro.notification", ["code" => $this->code]);
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
		$tenant = $this;
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
}