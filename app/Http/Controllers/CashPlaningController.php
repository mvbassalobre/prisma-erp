<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\{
	Customer,
	CustomerFluxYearEntries,
};
use App\Http\Models\{CustomerGoal, CustomerFluxYear, CustomerFluxYearSection, CustomerFluxSectionExpense};


class CashPlaningController extends Controller
{
	protected $months = ["jan", "fev", "mar", "abr", "mai", "jun", "jul", "ago", "set", "out", "nov", "dez"];

	public function getYears(Request $request)
	{
		return CustomerFluxYear::where("customer_id", $request['customer_id'])->get();
	}

	private function normalizeMonths($data)
	{
		foreach ($this->months as $month) if (!@$data[$month]) $data[$month] = 0;
		return $data;
	}

	private function continueToNextYear($data)
	{
		foreach ($data as $key => $key) if (in_array($key, $this->months)) $data[$key] = $data["dez"];
		return $data;
	}

	public function getEntries(Request $request)
	{
		return CustomerFluxYear::findOrFail($request["year_id"])->entries;
	}

	public function addEntries(Request $request)
	{
		$years = CustomerFluxYear::where("customer_id", $request["customer_id"])->where("id", ">=", $request["year_id"])->get();
		$data = $request["form"];
		$entry_ref =  uniqid();
		$data = $this->normalizeMonths($data);
		foreach ($years as $year) {
			$data["year_id"] = $year->id;
			$data["data"] = ["reference" => $entry_ref];
			CustomerFluxYearEntries::create($data);
			$data = $this->continueToNextYear($data);
		}
		return ["success" => true];
	}

	public function addYears(Request $request)
	{
		$years = $request["new_years"];
		foreach ($years as $year) {
			$created = CustomerFluxYear::create([
				"value" => $year,
				"customer_id" => $request["customer_id"]
			]);
			$created->extend();
		}
		return ["success" => true];
	}

	public function deleteYear($id)
	{
		CustomerFluxYear::findOrFail($id)->delete();
		return ["success" => true];
	}

	public function deleteEntry($id)
	{
		$entry = CustomerFluxYearEntries::findOrFail($id);
		$year_ids  = [];
		foreach (CustomerFluxYearEntries::where("data->reference", $entry->data->reference)->get() as $entry) {
			$year_ids[] = $entry->year_id;
			$entry->delete();
		}
		return ["success" => true, "year_ids" => $year_ids];
	}

	public function editEntry($id, Request $request)
	{
		$entry = CustomerFluxYearEntries::findOrFail($id);
		$year_ids  = [];
		$data = $request->except(['id', 'year_id', 'data', 'updated_at', 'created_at', 'deleted_at']);
		$data = $this->normalizeMonths($data);
		foreach (CustomerFluxYearEntries::where("data->reference", $entry->data->reference)->where("year_id", ">=", $request["year_id"])->orderBy('id', 'asc')->get() as $entry) {
			$year_ids[] = $entry->year_id;
			$entry->fill($data);
			$entry->save();
			$data = $this->continueToNextYear($data);
		}
		return ["success" => true, "year_ids" => $year_ids];
	}

	public function getSections(Request $request)
	{
		return CustomerFluxYearSection::where("year_id", $request["year_id"])->get();
	}

	public function addSection(Request $request)
	{
		$years = CustomerFluxYear::where("customer_id", $request["customer_id"])->where("id", ">=", $request["year_id"])->get();
		$ref =  uniqid();
		foreach ($years as $year) {
			$data["year_id"] = $year->id;
			$data["data"] = ["reference" => $ref];
			$data["name"] = $request["section"];
			CustomerFluxYearSection::create($data);
		}
		return ["success" => true, "year_ids" =>  $years->pluck('id')];
	}

	public function deleteSection($id)
	{
		$year_ids  = [];
		$_s = CustomerFluxYearSection::findOrFail($id);
		foreach (CustomerFluxYearSection::where("data->reference", $_s->data->reference)->get() as $section) {
			$year_ids[] = $section->year_id;
			$section->delete();
		}
		return ["success" => true, "year_ids" => $year_ids];
	}

	public function editSection($id, Request $request)
	{
		$_s = CustomerFluxYearSection::findOrFail($id);
		$data = $request->except(['id', 'year_id', 'data', 'updated_at', 'created_at', 'deleted_at', 'expenses']);
		foreach (CustomerFluxYearSection::where("data->reference", $_s->data->reference)->get() as $section) {
			$year_ids[] = $section->year_id;
			$section->fill($data);
			$section->save();
		}
		return ["success" => true, "year_ids" => $year_ids];
	}

	public function addExpense(Request $request)
	{
		$_s = CustomerFluxYearSection::findOrFail($request["section_id"]);
		$ref = uniqid();
		$year_ids = [];
		$data = $request["expense"];
		$data = $this->normalizeMonths($data);
		foreach (CustomerFluxYearSection::where("data->reference", $_s->data->reference)->get() as $section) {
			$year_ids[] = $section->year_id;
			$data["section_id"] = $section->id;
			$data["data"] = ["reference" => $ref];
			CustomerFluxSectionExpense::create($data);
			$data = $this->continueToNextYear($data);
		}
		return ["success" => true, "year_ids" => $year_ids];
	}

	public function getExpenses(Request $request)
	{
		return CustomerFluxSectionExpense::where("section_id", $request["section_id"])->get();
	}

	public function deleteExpense($id)
	{
		$year_ids  = [];
		$_s = CustomerFluxSectionExpense::findOrFail($id);
		foreach (CustomerFluxSectionExpense::where("data->reference", @$_s->data->reference)->get() as $expense) {
			$year_ids[] = $expense->section->year_id;
			$expense->delete();
		}
		return ["success" => true, "year_ids" => $year_ids];
	}

	public function editExpense($id, Request $request)
	{
		$_s = CustomerFluxSectionExpense::findOrFail($id);
		$year_ids  = [];
		$data = $request->except(['id', 'section_id', 'data', 'updated_at', 'created_at', 'deleted_at']);
		$data = $this->normalizeMonths($data);
		foreach (CustomerFluxSectionExpense::where("data->reference", @$_s->data->reference)->where('id', '>=', $id)->orderBy("id", "asc")->get() as $expense) {
			$year_ids[] = $expense->section->year_id;
			$expense->fill($data);
			$expense->save();
			$data = $this->continueToNextYear($data);
		}
		return ["success" => true, "year_ids" => $year_ids];
	}


	protected function customerGoals(Request $request)
	{
		return CustomerGoal::where("customer_id", $request["customer_id"])->get();
	}
}