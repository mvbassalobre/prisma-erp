<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class CustomerFluxYearSection extends DefaultModel
{
	protected $table = "customer_flux_year_sections";

	public $casts = [
		"data" => "object"
	];

	public static function hasTenant()
	{
		return false;
	}

	public function year()
	{
		return $this->belongsTo(CustomerFluxYear::class, "year_id");
	}

	public function expenses()
	{
		return $this->hasMany(CustomerFluxSectionExpense::class, "section_id");
	}

	public function extend()
	{
		$year = $this->year->getPreviousYear();
		foreach ($year->sections as $section) {
			foreach ($section->expenses as $expense) {
				$data["name"] = $expense->name;
				$data["section_id"] = $this->id;
				$data["data"] = $this->data;
				foreach (array_keys($expense->toArray()) as $key) if (!in_array($key, ['id', 'name', 'section_id', 'deleted_at', 'created_at', 'updated_at', 'data'])) $data[$key] = $expense->dez;
				CustomerFluxSectionExpense::create($data);
			}
		}
	}
}