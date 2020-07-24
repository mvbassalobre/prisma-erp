<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Models\{CustomerGoal, CustomerFluxYear};
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getData($type, Request $request)
    {
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
}
