<?php

namespace App\Http\Models;

use App\Http\Models\Sale;
use App\Http\Models\Scopes\SaleServiceScope;

class SaleService extends Sale
{
	protected $table = "sales";

	public static function boot()
	{
		parent::boot();
		static::addGlobalScope(new SaleServiceScope());
	}

	public function getFPagtoAttribute()
	{
		$payment = $this->payment;
		if (!@$payment) return "Transferência Bancária";
		return $payment->status;
	}
}