<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Http\Models\Customer;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        return view("admin.home");
    }

    public function getInfo(Request $request)
    {
        $user = Auth::user();
        return $this->{$request["type"]}($user, $request->except(["type"]));
    }

    public function qtyCustomers($user)
    {
        if (Auth::user()->hasRole(["admin"])) return ["qty" => DB::table("customers")->where("tenant_id", $user->tenant_id)->count()];
        return ["qty" => Customer::count()];
    }

    public function qtyProducts($user)
    {
        if (Auth::user()->hasRole(["admin"])) return ["qty" => DB::table("products")->where("tenant_id", $user->tenant_id)->count()];
        return ["qty" => Customer::count()];
    }

    public function qtyTeams($user)
    {
        if (Auth::user()->hasRole(["admin"])) return ["qty" => DB::table("teams")->where("tenant_id", $user->tenant_id)->count()];
        return ["qty" => Customer::count()];
    }

    public function qtyUsers($user)
    {
        if (Auth::user()->hasRole(["admin"])) return ["qty" => DB::table("users")->where("tenant_id", $user->tenant_id)->count()];
        return ["qty" => Customer::count()];
    }

    public function topUsers($user, $filter)
    {
        $data = DB::table("customers")->join("users", "users.id", "=", "customers.user_id")
            ->where("customers.tenant_id", $user->tenant_id)
            ->where("users.tenant_id", $user->tenant_id);

        if (@$filter["daterange"]) {
            $dates = array_map(function ($date) {
                return Carbon::create($date)->format("Y-m-d 00:00:00");
            }, $filter["daterange"]);
            $data = $data->whereRaw("DATE(customers.created_at) >='{$dates[0]}'" . " and " . "DATE(customers.created_at) <='{$dates[1]}'");
        }

        $data = $data->selectRaw("count(*) as qty, users.name as name")
            ->groupByRaw("users.id")
            ->orderBy("qty", "desc")
            ->limit(5)
            ->pluck('qty', 'name')
            ->all();
        return $data;
    }

    public function topTeams($user, $filter)
    {
        $data = DB::table("users")
            ->leftJoin("user_team", "user_team.user_id", "=", "users.id")
            ->leftJoin("teams", "teams.id", "=", "user_team.team_id")
            ->leftJoin("sales", "sales.user_id", "=", "users.id")
            ->where("users.tenant_id", $user->tenant_id);

        if (@$filter["daterange"]) {
            $dates = array_map(function ($date) {
                return Carbon::create($date)->format("Y-m-d 00:00:00");
            }, $filter["daterange"]);
            $data = $data->whereRaw("DATE(sales.created_at) >='{$dates[0]}'" . " and " . "DATE(sales.created_at) <='{$dates[1]}'");
        }
        $data = $data->selectRaw("count(*) as qty, teams.name as team ")
            ->orderBy("qty", "desc")
            ->limit(5)
            ->pluck('qty', 'team')
            ->all();

        return $data;
    }

    public function topTeamNewMeeting($user, $filter)
    {
        $data = DB::table("meetings")
            ->leftJoin("customers", "customers.id", "=", "meetings.customer_id")
            ->leftJoin("users", "users.id", "=", "customers.user_id")
            ->where("users.tenant_id", $user->tenant_id);

        if (@$filter["daterange"]) {
            $dates = array_map(function ($date) {
                return Carbon::create($date)->format("Y-m-d 00:00:00");
            }, $filter["daterange"]);
            $data = $data->whereRaw("DATE(sales.created_at) >='{$dates[0]}'" . " and " . "DATE(sales.created_at) <='{$dates[1]}'");
        }
        $data = $data->selectRaw("count(*) as qty, users.name as name ")
            ->orderBy("qty", "desc")
            ->limit(5)
            ->pluck('qty', 'name')
            ->all();

        return $data;
    }
}
