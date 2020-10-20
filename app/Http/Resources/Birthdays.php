<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use Auth;
use App\Http\Filters\Birthdays\{
	BirthdayByResource,
	BirthdayByMonth
};

class Birthdays extends Resource
{
	public $model = \App\Http\Models\Birthday::class;

	public function menu()
	{
		return "Outros";
	}

	public function menuIcon()
	{
		return "el-icon-coin";
	}

	public function label()
	{
		return "Aniversariantes";
	}

	public function singularLabel()
	{
		return "Aniversariante";
	}

	public function icon()
	{
		return 'el-icon-present';
	}

	public function canViewList()
	{
		return true;
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

	public function canCreate()
	{
		return false;
	}

	public function beforeListSlot()
	{
		return view('admin.month_birthdays.notification')->render();
	}

	public function noResultsFoundText()
	{
		return "Nenhum aniversariante encontrado";
	}

	public function nothingStoredText()
	{
		return "<h4>Nenhum aniversariante este mês...<h4>";
	}

	public function nothingStoredSubText()
	{
		return "";
	}

	public function table()
	{
		$columns =  [
			"f_name" => ["label" => "Nome", "sortable_index" => "name"],
			"f_resource" => ["label" => "Tipo", "sortable_index" => "resource"],
			"birthday" => ["label" => "Aniversário", "sortable" => false],
		];
		if (Auth::user()->hasRole(["super-admin"])) {
			$columns["tenant->name"] = ["label" => "Tenant", "sortable_index" => "tenant_id"];
		}
		return $columns;
	}

	public function filters()
	{
		return [
			new BirthdayByResource(),
			new BirthdayByMonth(),
		];
	}
}