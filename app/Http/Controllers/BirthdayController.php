<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Models\Birthday;
use ResourcesHelpers;
use Carbon\Carbon;

class BirthdayController extends Controller
{
    public function index()
    {
        // dd(Birthday::get());
        $resource = ResourcesHelpers::find("birthdays");
        if (!$resource->canViewList()) abort(403);
        return view("admin.month_birthdays.index", compact("resource"));
    }

    public function getCounterMonth()
    {
        $current_day = Carbon::now()->day;
        $current_month = Carbon::now()->month;
        return Birthday::where("month", $current_month)->where("day", ">=", $current_day)->get();
    }
}
