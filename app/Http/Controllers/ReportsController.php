<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use \Carbon\Carbon;

class ReportsController extends Controller
{
    public function salesByUser()
    {
        return view("admin.reports.sales_by_user");
    }

    public function salesByTeam()
    {
        return view("admin.reports.sales_by_team");
    }

    public function getDataSales($type, Request $request)
    {
        $user = Auth::user();
        $data = DB::table("sales")
            ->selectRaw("sales.*,customers.name as customer_name,customers.id as customer_id,
            if(users.name is null, 'Sem Responsável',users.name) as user_name, users.id as user_id, teams.id as team_id,
            if(teams.name is null,'Sem Time',teams.name) as team_name,
            if(sale_payment.status is null,'Sem Link de Pagto',sale_payment.status) as status_payment,
            DATE_FORMAT(sales.created_at,'%d/%m/%Y') as f_created_at")
            ->leftJoin("sale_payment", "sales.id", "=", "sale_payment.sale_id")
            ->leftJoin("customers", "customers.id", "=", "sales.customer_id")
            ->leftJoin("users", "users.id", "=", "sales.user_id")
            ->leftJoin("user_team", "user_team.user_id", "=", "users.id")
            ->leftJoin("teams", "teams.id", "=", "user_team.team_id")
            ->where("sales.tenant_id", "=", $user->tenant_id)
            ->orderBy("sales.created_at", "desc");
        switch ($type) {
            case "csv":
                $data = $this->applyFilterSales($data, $request);
                $csv = $data->get();
                foreach ($csv as $key => $value) $csv[$key]->code = \Hashids::encode($value->id);
                return ["success" => true, "csv" => $csv];
                break;
            case "table":
                $data = $this->applyFilterSales($data, $request);
                $table = $data->paginate(25);
                foreach ($table as $key => $value) {
                    if ($value->customer_id) $table[$key]->customer_code = \Hashids::encode($value->customer_id);
                    if ($value->id) $table[$key]->code = \Hashids::encode($value->id);
                    if ($value->user_id) $table[$key]->user_code = \Hashids::encode($value->user_id);
                    if ($value->team_id) $table[$key]->team_code = \Hashids::encode(1);
                }
                return ["success" => true, "data" => $table];
                break;
            case "status":
                $chart_data = $this->applyFilterSales($data, $request);
                $chart_data = $chart_data->selectRaw("count(*) as qty,  if(sale_payment.status is null, 'Sem Link de Pagto', sale_payment.status)  as status_name")
                    ->groupBy("sale_payment.status")
                    ->orderBy("qty", "desc")
                    ->pluck('qty', 'status_name')
                    ->all();
                return ["success" => true, "chart_data" => $chart_data];
                break;
            case "team":
                $chart_data = $this->applyFilterSales($data, $request);
                $chart_data = $chart_data->selectRaw("count(*) as qty,  if(teams.name is null, 'Sem Time', teams.name)  as team_name")
                    ->groupBy("teams.id")
                    ->orderBy("qty", "desc")
                    ->pluck('qty', 'team_name')
                    ->all();
                return ["success" => true, "chart_data" => $chart_data];
                break;
            case "user":
                $chart_data = $this->applyFilterSales($data, $request);
                $chart_data = $chart_data->selectRaw("count(*) as qty,  if(users.name is null, 'Sem Usuário', users.name)  as user_name")
                    ->groupBy("users.id")
                    ->orderBy("qty", "desc")
                    ->pluck('qty', 'user_name')
                    ->all();
                return ["success" => true, "chart_data" => $chart_data];
                break;
            default:
                return ["success" => false];
                break;
        }
    }

    private function applyFilterSales($data, $request)
    {
        if (@$request["code"]) {
            $code = \Hashids::decode($request["code"]);
            if (@$code[0]) $data = $data->where("sales.id", $code[0]);
        }
        if (@$request["name"]) $data->where("sales.name", "like", "%" . $request["name"] . "%");
        if (@$request["product"]) $data->where("sales.items", "like", "%" . $request["product"] . "%");
        if (@$request["created_at_interval"][0] && @$request["created_at_interval"][1]) {
            $date_start = Carbon::create($request["created_at_interval"][0])->format("Y-m-d");
            $date_end = Carbon::create($request["created_at_interval"][1])->format("Y-m-d");
            $data = $data->whereRaw("DATE(sales.created_at) >= Date('{$date_start}') and DATE(sales.created_at) <= Date('{$date_end}')");
        }
        if (@$request["team"]) {
            if ($request["team"] != "all") {
                $data->where("teams.id", "=", $request["team"]);
            }
        }
        if (@$request["user"]) {
            if ($request["user"] != "all") {
                $data->where("sales.user_id", "=", $request["user"]);
            }
        }
        if (@$request["status"]) {
            if ($request["status"] != "all") {
                $data->where("sales.status_id", "=", $request["status"]);
            }
        }
        return $data->whereNull("sales.deleted_at");
    }
}
