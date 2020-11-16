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
		$data = $request->except(['id', 'year_id', 'data', 'updated_at', 'created_at', 'deleted_at']);
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



	// protected function customerFluxYears(Request $request)
	// {
	// 	return CustomerFluxYear::where("customer_id", $request["customer_id"])->with(['entries'])->get();
	// }

	// protected function customerYearSections(Request $request)
	// {
	// 	return CustomerFluxYearSection::where("year_id", $request["id"])->with(["expenses"])->get();
	// }

	// protected function sectionExpenses(Request $request)
	// {
	// 	return CustomerFluxSectionExpense::where("section_id", $request["id"])->get();
	// }

	// protected function calcFluxTotal(Request $request)
	// {
	// 	$customer = Customer::findOrFail($request["customer_id"]);
	// 	$years = CustomerFluxYear::where("customer_id", $customer->id)->where("id", "<=", $request["year_id"])->get();
	// 	return  [
	// 		"total" => $this->getTotalFlux($years),
	// 		"fixed" => $this->getTotalExpense($years, "fixed"),
	// 		"grow" => $this->getTotalExpense($years, "grow"),
	// 		"variable" => $this->getTotalExpense($years, "variable"),
	// 	];
	// }

	// protected function getTotalExpense($years)
	// {
	// 	$total = 0;
	// 	foreach ($years as $year) {
	// 		foreach ($year->sections()->where("type", "fixed")->get() as $section) {
	// 			foreach ($section->expenses()->select($this->months)->get() as $expense) {
	// 				foreach (array_keys($expense->toArray()) as $month) $total += $expense[$month];
	// 			}
	// 		}
	// 	}
	// 	return $total;
	// }

	// protected function getTotalFlux($years)
	// {
	// 	$total = 0;
	// 	foreach ($years as $year) {
	// 		foreach ($year->entries()->select($this->months)->get() as $entry) {
	// 			foreach (array_keys($entry->toArray()) as $month) $total += $entry[$month];
	// 		}
	// 	}
	// 	return $total;
	// }



	// public function editFluxEntry($id, Request $request)
	// {
	// 	CustomerFluxYearEntries::where("id", $request["id"])->update($request->except("id"));
	// 	$other_entries = CustomerFluxYearEntries::where("data->reference", $request["data"]["reference"])
	// 		->where("year_id", ">", $request["year_id"])
	// 		->get();
	// 	foreach ($other_entries as $entry) {
	// 		foreach ($this->months as $month) $entry->{$month} = $request["dez"];
	// 		$entry->save();
	// 	}
	// 	return ["success" => true, "years" => $this->getYears($id)];
	// }




	// public function editSection($id, Request $request)
	// {
	// 	$query = CustomerFluxYearSection::where("data->reference", $request["data"]["reference"]);
	// 	(clone $query)->update($request->except(["id", "expenses", "year_id"]));
	// 	return ["success" => true, "sections" => $query->get()];
	// }

	// public function editGoal($id, Request $request)
	// {
	// 	CustomerGoal::where("id", $request["id"])->update($request->except("id"));
	// 	return ["success" => true, "goals" => CustomerGoal::where("customer_id", $request["customer_id"])->get()];
	// }



	// public function deleteFluxEntry($id)
	// {
	// 	$entry = CustomerFluxYearEntries::findOrFail($id);
	// 	$customer_id = $entry->year->customer_id;
	// 	$entry->delete();
	// 	return ["success" => true, "years" => $this->getYears($customer_id)];
	// }


	// public function editExpense($id, Request $request)
	// {
	// 	$customer = Customer::findOrFail($id);
	// 	$data = $request->except(['updated_at', 'created_at', 'deleted_at']);
	// 	$origin_section = CustomerFluxYearSection::findOrFail($data["section_id"]);
	// 	$sections = CustomerFluxYearSection::where("data->reference", $origin_section->data->reference)->where("id", ">=", $origin_section->id)->get();
	// 	$years = [];
	// 	unset($data["id"]);
	// 	foreach ($sections as $section) {
	// 		$years[] = $section->year_id;
	// 		$data["section_id"] = $section->id;
	// 		if ($origin_section->id != $section->id) {
	// 			foreach ($data as $key => $key) if (!in_array($key, ['name', 'section_id', 'data'])) $data[$key] = $data["dez"];
	// 		}
	// 		$expense = $section->expenses()->where("data->reference", $data["data"]["reference"])->first();
	// 		$expense->fill($data);
	// 		$expense->save();
	// 	}
	// 	$customer->appendToTimeline("Fluxo de Caixa", "Edição de gasto de fluxo de caixa");
	// 	return ["success" => true, "sections" => CustomerFluxYearSection::whereIn("year_id", $years)->with(["expenses"])->get()];
	// }

	// public function deleteExpense($id)
	// {
	// 	$expense = CustomerFluxSectionExpense::findOrFail($id);
	// 	$year_id = $expense->section->year_id;
	// 	$expense->delete();
	// 	return ["success" => true, "sections" => CustomerFluxYearSection::where("year_id", $year_id)->with(["expenses"])->get()];
	// }

	// public function deleteGoal($id)
	// {
	// 	$goal = CustomerGoal::findOrFail($id);
	// 	$customer_id = $goal->customer_id;
	// 	$goal->delete();
	// 	return ["success" => true, "goals" => CustomerGoal::where("customer_id", $customer_id)->get()];
	// }

	// public function deleteFluxYear($id)
	// {
	// 	$year = CustomerFluxYear::findOrFail($id);
	// 	$customer_id = $year->customer_id;
	// 	$year->delete();
	// 	return ["success" => true, "years" => $this->getYears($customer_id)];
	// }


	// public function addYearEntry($id, Request $request)
	// {
	// 	$customer = Customer::findOrFail($id);
	// 	$years = CustomerFluxYear::where("customer_id", $customer->id)->where("id", ">=", $request["year"]["id"])->get();
	// 	$data = $request->except("year");
	// 	$entry_ref =  uniqid();
	// 	foreach ($years as $year) {
	// 		$data["year_id"] = $year->id;
	// 		$data["data"] = ["reference" => $entry_ref];
	// 		if ($year->id != $request["year"]["id"]) {
	// 			foreach ($data as $key => $key) if (!in_array($key, ['id', 'name', 'year_id', 'data', 'updated_at', 'created_at', 'deleted_at'])) $data[$key] = $data["dez"];
	// 		}
	// 		CustomerFluxYearEntries::create($data);
	// 	}
	// 	return ["success" => true, "years" => $this->getYears($customer->id)];
	// }
}