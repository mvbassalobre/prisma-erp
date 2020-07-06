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

    public function getInfo($type, Request $request)
    {
        $user = Auth::user();
        return $this->{$type}($user, $request->all());
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
        $data = DB::table("customers")
            ->leftJoin("users", "users.id", "=", "customers.user_id")
            ->where("customers.tenant_id", $user->tenant_id);

        if (@$filter["daterange"]) {
            $dates = array_map(function ($date) {
                return Carbon::create($date)->format("Y-m-d 00:00:00");
            }, $filter["daterange"]);
            $data = $data->whereRaw("DATE(customers.created_at) >='{$dates[0]}'" . " and " . "DATE(customers.created_at) <='{$dates[1]}'");
        }

        $data = $data->selectRaw("count(*) as qty, if(users.name is null,'Sem Operador', users.name) as name")
            ->groupByRaw("users.id")
            ->orderBy("qty", "desc")
            ->limit(5)
            ->pluck('qty', 'name')
            ->all();
        return $data;
    }

    public function topTeams($user, $filter)
    {
        $data = DB::table("customers")
            ->selectRaw("count(*) as qty, if(teams.name is null, 'Sem Time',teams.name) as name")
            ->leftJoin("users", "users.id", "=", "customers.user_id")
            ->leftJoin("user_team", "user_team.user_id", "=", "users.id")
            ->leftJoin("teams", "teams.id", "=", "user_team.team_id")
            ->where("customers.tenant_id", $user->tenant_id);

        if (@$filter["daterange"]) {
            $dates = array_map(function ($date) {
                return Carbon::create($date)->format("Y-m-d 00:00:00");
            }, $filter["daterange"]);
            $data = $data->whereRaw("DATE(customers.created_at) >='{$dates[0]}'" . " and " . "DATE(customers.created_at) <='{$dates[1]}'");
        }

        $data = $data
            ->groupBy("teams.id")
            ->orderBy("qty", "desc")
            ->limit(5)
            ->pluck('qty', 'name')
            ->all();
        return $data;
    }

    public function topUsersNewMeeting($user, $filter)
    {
        $data = DB::table("meetings")
            ->leftJoin("users", "users.id", "=", "meetings.user_id")
            ->where("meetings.tenant_id", $user->tenant_id);

        if (@$filter["daterange"]) {
            $dates = array_map(function ($date) {
                return Carbon::create($date)->format("Y-m-d 00:00:00");
            }, $filter["daterange"]);
            $data = $data->whereRaw("DATE(meetings.created_at) >= Date('{$dates[0]}')  and  DATE(meetings.created_at) <= Date('{$dates[1]}') ");
        }
        $data = $data->selectRaw("count(*) as qty, if(users.name is null, 'Sem Operador',users.name) as name ")
            ->groupBy("users.id")
            ->orderBy("qty", "desc")
            ->limit(5)
            ->pluck('qty', 'name')
            ->all();

        return $data;
    }

    public function topTeamNewMeeting($user, $filter)
    {
        $data = DB::table("meetings")
            ->selectRaw("count(*) as qty, if(teams.name is null,'Sem Time',teams.name) as team")
            ->leftJoin("users", "users.id", "=", "meetings.user_id")
            ->leftJoin("user_team", "user_team.user_id", "=", "users.id")
            ->leftJoin("teams", "teams.id", "=", "user_team.team_id")
            ->where("users.tenant_id", $user->tenant_id);

        if (@$filter["daterange"]) {
            $dates = array_map(function ($date) {
                return Carbon::create($date)->format("Y-m-d 00:00:00");
            }, $filter["daterange"]);
            $data = $data->whereRaw("DATE(meetings.created_at) >='{$dates[0]}' and DATE(meetings.created_at) <='{$dates[1]}'");
        }
        $data = $data->groupBy("users.id")
            ->groupBy("users.id")
            ->orderBy("qty", "desc")
            ->limit(5)
            ->pluck('qty', 'team')
            ->all();

        return $data;
    }

    public function meetingPerStatus($user, $filter)
    {
        $data = DB::table("meetings")
            ->leftJoin("meeting_statuses", "meeting_statuses.id", "=", "meetings.status_id")
            ->where("meetings.tenant_id", $user->tenant_id);

        if (@$filter["daterange"]) {
            $dates = array_map(function ($date) {
                return Carbon::create($date)->format("Y-m-d 00:00:00");
            }, $filter["daterange"]);
            $data = $data->whereRaw("DATE(meetings.created_at) >='{$dates[0]}'" . " and " . "DATE(meetings.created_at) <='{$dates[1]}'");
        }
        $data = $data->selectRaw("count(*) as qty, meeting_statuses.name as status")
            ->groupBy("meetings.status_id")
            ->orderBy("qty", "desc")
            ->limit(5)
            ->pluck('qty', 'status')
            ->all();

        return $data;
    }

    public function meetingPerTeam($user, $filter)
    {
        $data = DB::table("meetings")
            ->leftJoin("meeting_statuses", "meeting_statuses.id", "=", "meetings.status_id")
            ->leftJoin("users", "users.id", "=", "meetings.user_id")
            ->leftJoin("user_team", "user_team.user_id", "=", "users.id")
            ->leftJoin("teams", "teams.id", "=", "user_team.team_id")
            ->where("meetings.tenant_id", $user->tenant_id);

        if (@$filter["daterange"]) {
            $dates = array_map(function ($date) {
                return Carbon::create($date)->format("Y-m-d 00:00:00");
            }, $filter["daterange"]);
            $data = $data->whereRaw("DATE(meetings.created_at) >='{$dates[0]}'" . " and " . "DATE(meetings.created_at) <='{$dates[1]}'");
        }
        $data = $data->selectRaw("count(*) as qty, if(teams.name is null,'Sem Time',teams.name) as name")
            ->groupBy("teams.id")
            ->orderBy("qty", "desc")
            ->limit(5)
            ->pluck('qty', 'name')
            ->all();

        return $data;
    }

    public function sold($user, $filter)
    {
        $today = @$filter['daterange'][1]  ? Carbon::create($filter['daterange'][1]) : Carbon::now();

        $salesToday = DB::table('sales')->where("sales.tenant_id", $user->tenant_id)->whereRaw("DATE(created_at) = DATE('{$today->format('Y-m-d')}')");
        $totalQty = 0;
        $totalAmount = 0;

        $amountToday = $salesToday->sum("subtotal");

        $yesterday =  Carbon::create($today->format("Y-m-d"))->subdays(1)->format("Y-m-d");
        $salesYesterday = DB::table('sales')->where("sales.tenant_id", $user->tenant_id)->whereRaw("DATE(created_at) = DATE('{$yesterday}')")->whereRaw("DATE(created_at) <= DATE('{$yesterday}')");
        $amountYesterday = $salesYesterday->sum("subtotal");
        $percentage =  ($amountYesterday == 0) ? "Infinito " : (($amountToday == 0) ? 0 : round((($amountToday * 100) / $amountYesterday) - 100, 2));
        $totalQty += $salesYesterday->count();
        $chartData = [];

        $diff = 5;
        if (@$filter['daterange'][1] && @$filter['daterange'][0]) $diff = Carbon::create($filter['daterange'][1])->diffInDays(Carbon::create($filter["daterange"][0]));

        for ($i = $diff; $i >= 0; $i--) {
            $date = (clone $today)->subdays($i);
            $query = DB::table('sales')->where("sales.tenant_id", $user->tenant_id)->whereRaw("DATE(created_at) =  DATE('{$date->format('Y-m-d')}')");
            $totalQty += $query->count();
            $amount = (clone $query)->sum('subtotal');
            $totalAmount += $amount;
            $chartData[$date->format("d/m")] = round($amount, 2);
        }
        $amountToday = $this->amountFormat($amountToday);

        return [
            "percentage" => $percentage,
            "total_amount" => $this->amountFormat($totalAmount),
            "qty" => $totalQty,
            "today" => ["amount" => $amountToday],
            "chart_data" => $chartData
        ];
    }

    private function amountFormat($amount)
    {
        return "R$ " . number_format($amount, 2, ".", ",");
    }
}
