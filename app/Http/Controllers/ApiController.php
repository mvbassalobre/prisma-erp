<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Models\{Product, Customer, CustomerGoal, CustomerFluxYear, CustomerFluxYearSection, CustomerFluxSectionExpense};
use Illuminate\Http\Request;

class ApiController extends Controller
{
	protected $months = ["jan", "fev", "mar", "abr", "mai", "jun", "jul", "ago", "set", "out", "nov", "dez"];

	public function getData($type, Request $request)
	{
		return $this->{$type}($request);
	}

	protected function customerGoals(Request $request)
	{
		return CustomerGoal::where("customer_id", $request["customer_id"])->get();
	}

	protected function customerFluxYears(Request $request)
	{
		return CustomerFluxYear::where("customer_id", $request["customer_id"])->with(['entries'])->get();
	}

	protected function customerYearSections(Request $request)
	{
		return CustomerFluxYearSection::where("year_id", $request["id"])->with(["expenses"])->get();
	}

	protected function sectionExpenses(Request $request)
	{
		return CustomerFluxSectionExpense::where("section_id", $request["id"])->get();
	}

	protected function calcFluxTotal(Request $request)
	{
		$customer = Customer::findOrFail($request["customer_id"]);
		$years = CustomerFluxYear::where("customer_id", $customer->id)->where("id", "<=", $request["year_id"])->get();
		return  [
			"total" => $this->getTotalFlux($years),
			"fixed" => $this->getTotalExpense($years, "fixed"),
			"grow" => $this->getTotalExpense($years, "grow"),
			"variable" => $this->getTotalExpense($years, "variable"),
		];
	}

	protected function getTotalExpense($years)
	{
		$total = 0;
		foreach ($years as $year) {
			foreach ($year->sections()->where("type", "fixed")->get() as $section) {
				foreach ($section->expenses()->select($this->months)->get() as $expense) {
					foreach (array_keys($expense->toArray()) as $month) $total += $expense[$month];
				}
			}
		}
		return $total;
	}

	protected function getTotalFlux($years)
	{
		$total = 0;
		foreach ($years as $year) {
			foreach ($year->entries()->select($this->months)->get() as $entry) {
				foreach (array_keys($entry->toArray()) as $month) $total += $entry[$month];
			}
		}
		return $total;
	}

	protected function getProduct(Request $request)
	{
		return Product::where("type", $request["type"])->get();
	}
}