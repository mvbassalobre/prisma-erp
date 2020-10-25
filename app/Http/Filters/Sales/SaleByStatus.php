<?php

namespace App\Http\Filters\Sales;

use App\Http\Models\PaymentStatus;
use  marcusvbda\vstack\Filter;

class SaleByStatus extends Filter
{

	public $component   = "select-filter";
	public $label       = "Status";
	public $placeholder = "";
	public $index = "sales_by_payment_status";
	public $option_list = [];
	public $_type = null;

	public function __construct($type)
	{
		$this->_type = $type;
		if ($this->_type == "Serviço") $this->option_list = PaymentStatus::get();
		else {
			$this->option_list = [
				(object)["status" => "Aguardando"],
				(object)["status" => "Pago"],
				(object)["status" => "Cancelado"]
			];
		}
		foreach ($this->option_list as $key => $value) {
			$this->options[] = (object) ["value" =>  intval($key + 1), "label" => $value->status];
		}
		parent::__construct();
	}

	public function apply($query, $value)
	{
		$status = @$this->option_list[$value - 1];
		if (!@$status) return $query;
		if ($this->_type == "Serviço") return $query->join("sale_payment", "sale_payment.sale_id", "sales.id")->where("sale_payment.status", $status->status);
		else return $query->where("sales.product_status", $status->status);
	}
}