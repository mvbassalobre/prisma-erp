<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use \Carbon\Carbon;

class ReportsController extends Controller
{
    public function customerByTeam()
    {
        return view("admin.reports.customer_by_team");
    }

    public function getCustomerByTeamTable($type, Request $request)
    {
        $user = Auth::user();
        $data = DB::table('customers')
            ->selectRaw("customers.*, 
                    if(teams.name is null, 'Sem Time', teams.name)  as team_name,
                    user_team.team_id as team_id,
                    DATE_FORMAT(customers.created_at,'%d/%m/%Y') as f_created_at,
                    if(customers.updated_at is null,'Nunca Alterado', DATE_FORMAT(customers.updated_at,'%d/%m/%Y')) as f_last_update
                ")
            ->leftJoin("users", "users.id", "=", "customers.user_id")
            ->leftJoin("user_team", "user_team.user_id", "=", "users.id")
            ->leftJoin("teams", "teams.id", "=", "user_team.team_id")
            ->where("customers.tenant_id", "=", $user->tenant_id)
            ->orderBy("customers.created_at", "desc");
        $data = $this->applyFilterToSales($data, $request);
        switch ($type) {
            case "csv":
                $csv = $data->get();
                foreach ($csv as $key => $value) $csv[$key]->code = \Hashids::encode($value->id);
                return ["success" => true, "csv" => $csv];
                break;
            case "table":
                $table = $data->paginate(25);
                foreach ($table as $key => $value) {
                    $table[$key]->code = \Hashids::encode($value->id);
                    if ($value->team_id) $table[$key]->team_code = \Hashids::encode($value->team_id);
                }
                return ["success" => true, "data" => $table];
                break;
            case "team":
                $chart_data = $this->applyFilterToSales($data, $request);
                $chart_data = $chart_data->selectRaw("count(*) as qty,  if(teams.name is null, 'Sem Time', teams.name)  as team_name")
                    ->groupBy("teams.id")
                    ->orderBy("qty", "desc")
                    ->pluck('qty', 'team_name')
                    ->all();
                return ["success" => true, "chart_data" => $chart_data];
                break;

            default:
                return ["success" => false];
                break;
        }
    }

    private function applyFilterToSales($data, $request)
    {
        if (@$request["code"]) $data = $data->where("customers.id", \Hashids::decode($request["code"])[0]);
        if (@$request["name"]) $data->where("customers.name", "like", "%" . $request["name"] . "%");
        if (@$request["created_at_interval"][0] && @$request["created_at_interval"][1]) {
            $date_start = Carbon::create($request["created_at_interval"][0])->format("Y-m-d");
            $date_end = Carbon::create($request["created_at_interval"][1])->format("Y-m-d");
            $data = $data->whereRaw("DATE(customers.created_at) >= Date('{$date_start}') and DATE(customers.created_at) <= Date('{$date_end}')");
        }
        if (@$request["team"]) {
            if ($request["team"] != "all") {
                $data->where("teams.id", "=", $request["team"]);
            }
        }
        return $data;
    }
}
