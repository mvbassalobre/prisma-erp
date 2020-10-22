<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Fields\{Card, Text, BelongsTo, Url};
use marcusvbda\vstack\Resource;
use Auth;
use App\Http\Filters\Meetings\{
	MeetingsByDateRange,
	MeetingsByCustomer,
	MeetingsByRoom,
	MeetingsByTeam
};

class Meetings extends Resource
{
	public $model = \App\Http\Models\Meeting::class;

	public function label()
	{
		return "Agendamentos";
	}

	public function resultsPerPage()
	{
		return [20, 50, 100, 200];
	}

	public function search()
	{
		return ["subject"];
	}

	public function singularLabel()
	{
		return "Agendamento";
	}

	public function menu()
	{
		return "Reuniões";
	}

	public function menuIcon()
	{
		return "el-icon-notebook-1";
	}

	public function icon()
	{
		return "el-icon-guide";
	}

	public function fields()
	{
		return [
			new Card("Informações", [
				new Text([
					"label" => "Assunto da Reunião",
					"field" => "subject",
					"required" => true,
					"placeholder" => "Assunto",
					"rules" => "required|max:255"
				]),
				new Text([
					"label" => "Tipo de Reunião",
					"field" => "type",
					"required" => true,
					"placeholder" => "Ex: orçamento, investimento etc",
					"rules" => "required|max:255"
				]),
				new BelongsTo([
					"label" => "Status",
					"field" => "status_id",
					"required" => true,
					"placeholder" => "Selecione o status",
					"model" => \App\Http\Models\MeetingStatus::class,
					"default" => (string) @\App\Http\Models\MeetingStatus::first()->id,
					"rules" => "required|min:1"
				]),
				new BelongsTo([
					"label" => "Cliente da Reunião",
					"field" => "customer_id",
					"required" => true,
					"placeholder" => "Selecione o cliente",
					"model" => \App\Http\Models\Customer::class,
					"rules" => "required|min:1"
				]),
				new Text([
					"label" => "Url de feedback",
					"field" => "feedback_url",
					"required" => true,
					"placeholder" => "Url do Google Forms",
					"rules" => "required|url|max:255"
				]),
				new BelongsTo([
					"label" => "Local da Reunião",
					"field" => "meeting_room_id",
					"required" => true,
					"placeholder" => "Selecione o local da Reunião",
					"model" => \App\Http\Models\MeetingRoom::class,
					"rules" => "required|min:1"
				])
			])
		];
	}

	public function table()
	{
		$columns =  [
			"subject" => ["label" => "Assunto", "sortable_index" => "subject"],
			"room->name" => ["label" => "Sala", "sortable_index" => "meeting_room_id"],
			"customer_url_attendance" => ["label" => "Cliente", "sortable_index" => "customer_id"],
			"f_starts_at" => ["label" => "Inicio", "sortable_index" => "starts_at"],
			"f_ends_at" => ["label" => "Término", "sortable_index" => "ends_at"],
		];
		if (Auth::user()->hasRole(["super-admin"])) {
			$columns["tenant->name"] = ["label" => "Tenant", "sortable_index" => "tenant_id"];
		}
		return $columns;
	}

	public function canImport()
	{
		return false;
	}

	public function canDelete()
	{
		if (Auth::check()) {
			return Auth::user()->hasRole(["super-admin", "admin"]);
		}
		return false;
	}

	public function filters()
	{
		return [
			new MeetingsByRoom(),
			new MeetingsByTeam(),
			new MeetingsByCustomer(),
			new MeetingsByDateRange()
		];
	}

	public function beforeListSlot()
	{
		return view('admin.meetings.calendar');
	}

	public function canExport()
	{
		return true;
	}

	public function export_columns()
	{
		return [
			"code"  => ["label" => "Código"],
			"room->name"  => ["label" => "Sala de Reunião"],
			"status->name" => ["label" => "Status"],
			"customer->name" => ["label" => "Cliente"],
			"customer->email" => ["label" => "Email"],
			"customer->phone" => ["label" => "Telefone"],
			"customer->cellphone " => ["label" => "Celular"],
			"f_start_at" => ["label" => "Início"],
			"f_end_at" => ["label" => "Término"],
		];
	}
}