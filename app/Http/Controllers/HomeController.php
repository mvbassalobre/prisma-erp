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
        // whereRaw('DATE(created_at) >= DATE(CURRENT_DATE() - INTERVAL 30 DAY)')->where("payment_method", "credit_card")
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
            ->pluck('qty', 'name')
            ->all();
        return $data;
    }
}
