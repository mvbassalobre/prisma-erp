<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class SalePayment extends DefaultModel
{
	protected $table = "sale_payment";
	// public $cascadeDeletes = [];
	// public $restrictDeletes = [];

	protected $appends = ['f_created_at'];

	public $casts = [
		"data" => "Object",
	];

	public function Sale()
	{
		return $this->belongsTo(\App\Sale::class);
	}

	public function tenant()
	{
		return $this->belongsTo(\App\Http\Models\Tenant::class);
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

	public function getFCreatedAtAttribute()
	{
		if (!$this->created_at) return;
		return @$this->created_at->format("d/m/Y - H:i:s");
	}
}