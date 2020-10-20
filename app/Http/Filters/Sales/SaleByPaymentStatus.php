<?php

namespace App\Http\Filters\Sales;

use App\Http\Models\PaymentStatus;
use  marcusvbda\vstack\Filter;

class SaleByPaymentStatus extends Filter
{

	public $component   = "select-filter";
	public $label       = "Status";
	public $placeholder = "";
	public $index = "sales_by_payment_status";
	public $option_list = [];

	public function __construct()
	{
		$this->option_list = PaymentStatus::get();
		foreach ($this->option_list as $key => $value) {
			$this->options[] = (object) ["value" =>  intval($key + 1), "label" => $value->status];
		}
		parent::__construct();
	}

	public function apply($query, $value)
	{
		$status = @$this->option_list[$value - 1];
		if (!@$status) return $query;
		return $query->join("sale_payment", "sale_payment.sale_id", "sales.id")->where("sale_payment.status", $status->status);
	}
}