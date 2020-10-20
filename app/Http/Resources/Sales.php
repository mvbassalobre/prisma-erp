<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use  App\Http\Filters\Sales\{
	SalesByUser,
	SalesByTeam,
	SalesByDateRange,
	SalesByItem,
	SaleByPaymentStatus
};

class Sales extends Resource
{
	public $model = \App\Http\Models\Sale::class;

	public function label()
	{
		return "Vendas";
	}

	public function resultsPerPage()
	{
		return [20, 50, 100, 200];
	}

	public function singularLabel()
	{
		return "Venda";
	}

	public function menu()
	{
		return "Lançamentos";
	}

	public function menuIcon()
	{
		return "el-icon-share";
	}

	public function icon()
	{
		return "el-icon-money";
	}

	public function canImport()
	{
		return false;
	}

	public function canView()
	{
		return false;
	}

	public function canUpdate()
	{
		return false;
	}

	public function canDelete()
	{
		return false;
	}

	public function table()
	{
		return [
			"attendance_url" => ["label" => "Código", "sortable_index" => "id"],
			"f_customer" => ["label" => "Cliente", "sortable_index" => "customer_id"],
			"f_pagto" => ["label" => "Status", "sortable" => false],
			"payment->description" => ["label" => "Descrição", "sortable" => false],
			"f_items" => ["label" => "Items", "sortable" => false],
			"total_items" => ["label" => "Total", "sortable" => false],
			"f_user" => ["label" => "Responsável", "sortable_index" => "user_id"],
			"f_created_at" => ["label" => "Data de Lançamento", "sortable_index" => "created_at"],
		];
	}

	public function beforeListSlot()
	{
		return view('admin.sales.metrics');
	}

	public function canExport()
	{
		return true;
	}

	public function export_columns()
	{
		return [
			"code"  => ["label" => "Código"],
			"customer->name"  => ["label" => "Cliente"],
			"f_pagto" => ["label" => "Pagto"],
			"user" => ["label" => "Responsável", "handle" => function ($row) {
				return $row->user->name;
			}],
			"f_created_at" => ["label" => "Data de Lançamento"],
			"items" => ["label" => "Items", "handle" => function ($row) {
				$text = "";
				foreach ($row->items as $item) $text .= $item["name"] . " R$" . number_format($item["price"], 2, ',', '.') . " x " . $item["qty"] . " = R$" . number_format($item["total"], 2, ',', '.') . ",  ";
				return $text;
			}],
		];
	}

	public function filters()
	{
		return [
			new SaleByPaymentStatus(),
			new SalesByUser(),
			new SalesByTeam(),
			new SalesByItem(),
			new SalesByDateRange()
		];
	}
}