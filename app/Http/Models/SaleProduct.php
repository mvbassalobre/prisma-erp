<?php

namespace App\Http\Models;

use App\Http\Models\Sale;
use App\Http\Models\Scopes\SaleProductScope;

class SaleProduct extends Sale
{
	protected $table = "sales";

	public static function boot()
	{
		parent::boot();
		static::addGlobalScope(new SaleProductScope());
	}

	public function getFPagtoAttribute()
	{
		return $this->product_status;
	}
}