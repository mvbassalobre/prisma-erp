<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Models\{CustomerGoal, CustomerFluxYear, CustomerFluxYearSection, CustomerFluxSectionExpense};
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getData($type, Request $request)
    {
        // 
        return $this->{$type}($request);
    }

    public function customerGoals(Request $request)
    {
        return CustomerGoal::where("customer_id", $request["customer_id"])->get();
    }

    public function customerFluxYears(Request $request)
    {
        return CustomerFluxYear::where("customer_id", $request["customer_id"])->with(['entries'])->get();
    }

    public function customerYearSections(Request $request)
    {
        return CustomerFluxYearSection::where("year_id", $request["id"])->with(["expenses"])->get();
    }

    public function sectionExpenses(Request $request)
    {
        return CustomerFluxSectionExpense::where("section_id", $request["id"])->get();
    }
}
