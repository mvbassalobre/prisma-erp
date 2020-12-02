<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class CustomerFluxYear extends DefaultModel
{
	protected $table = "customer_flux_years";

	public $casts = [
		"data" => "object"
	];

	public static function hasTenant()
	{
		return false;
	}

	public function customer()
	{
		return $this->belongsTo(\App\Http\Models\Customer::class);
	}

	public function entries()
	{
		return $this->hasMany(\App\Http\Models\CustomerFluxYearEntries::class, "year_id");
	}

	public function sections()
	{
		return $this->hasMany(\App\Http\Models\CustomerFluxYearSection::class, "year_id");
	}

	public function extend()
	{
		$this->extedEntries();
		$this->extedSections();
	}

	public function getPreviousYear()
	{
		return $this->customer->fluxYears()->where("id", "!=", $this->id)->orderBy("id", "desc")->first();
	}

	protected function extedEntries()
	{
		$previous_year = $this->getPreviousYear();
		if ($previous_year) {
			foreach ($previous_year->entries as $entry) {
				$data = [];
				$data["year_id"] = $this->id;
				$data["name"] = $entry->name;
				foreach (array_keys($entry->toArray()) as $key) if (!in_array($key, ['id', 'name', 'year_id', 'deleted_at', 'created_at', 'updated_at'])) $data[$key] = $entry->dez;
				if (@$entry->data->reference) $data["data"] = ["reference" => $entry->data->reference];
				CustomerFluxYearEntries::create($data);
			}
		}
	}

	protected function extedSections()
	{
		$previous_year = $this->getPreviousYear();
		if ($previous_year) {
			foreach ($previous_year->sections as $section) {
				$data = [];
				$data["name"] = $section->name;
				$data["year_id"] = $this->id;
				$data["data"] = $section->data;
				$data["type"] = $section->type;
				$created = CustomerFluxYearSection::create($data);
				$created->extend();
			}
		}
	}
}