<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class CustomerFluxSectionExpense extends DefaultModel
{
	protected $table = "customer_flux_year_expenses";

	public $casts = [
		"data" => "object"
	];

	public static function hasTenant()
	{
		return false;
	}

	public function section()
	{
		return $this->belongsTo(CustomerFluxYearSection::class, "section_id");
	}
}