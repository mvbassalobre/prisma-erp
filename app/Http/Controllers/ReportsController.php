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

    public function customerByUser()
    {
        return view("admin.reports.customer_by_user");
    }

    public function getDataCustomers($type, Request $request)
    {
        $user = Auth::user();
        $data = DB::table('customers')
            ->selectRaw("customers.*, 
                    customers.email as email,
                    customers.phone as phone,
                    customers.cellphone as cellphone,
                    if(users.name is null, 'Sem Operador', users.name)  as user_name,
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

        switch ($type) {
            case "csv":
                $data = $this->applyFilterCustomer($data, $request);
                $csv = $data->get();
                foreach ($csv as $key => $value) $csv[$key]->code = \Hashids::encode($value->id);
                return ["success" => true, "csv" => $csv];
                break;
            case "table":
                $data = $this->applyFilterCustomer($data, $request);
                $table = $data->paginate(25);
                foreach ($table as $key => $value) {
                    $table[$key]->code = \Hashids::encode($value->id);
                    if ($value->team_id) $table[$key]->team_code = \Hashids::encode($value->team_id);
                    if ($value->user_id) $table[$key]->user_code = \Hashids::encode($value->user_id);
                }
                return ["success" => true, "data" => $table];
                break;
            case "team":
                $chart_data = $this->applyFilterCustomer($data, $request);
                $chart_data = $chart_data->selectRaw("count(*) as qty,  if(teams.name is null, 'Sem Time', teams.name)  as team_name")
                    ->groupBy("teams.id")
                    ->orderBy("qty", "desc")
                    ->pluck('qty', 'team_name')
                    ->all();
                return ["success" => true, "chart_data" => $chart_data];
                break;
            case "user":
                $chart_data = $this->applyFilterCustomer($data, $request);
                $chart_data = $chart_data->selectRaw("count(*) as qty,  if(users.name is null, 'Sem Operador', users.name)  as user_name")
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

    private function applyFilterCustomer($data, $request)
    {
        if (@$request["code"]) {
            $code = \Hashids::decode($request["code"]);
            if (@$code[0]) $data = $data->where("customers.id", $code[0]);
        }
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
        if (@$request["user"]) {
            if ($request["user"] != "all") {
                $data->where("users.id", "=", $request["user"]);
            }
        }
        return $data->whereNull("customers.deleted_at");
    }

    public function meetingByTeam()
    {
        return view("admin.reports.meeting_by_team");
    }

    public function meetingByUser()
    {
        return view("admin.reports.meeting_by_user");
    }

    public function getDataMeetings($type, Request $request)
    {
        $user = Auth::user();
        $data = DB::table("meetings")
            ->selectRaw("meetings.*, 
                customers.name as customer_name,
                meeting_statuses.name as status_name,
                meeting_statuses.color as status_color,
                meeting_rooms.name as room,
                customers.email as email,
                customers.phone as phone,
                customers.cellphone as cellphone,
                if(users.name is null, 'Sem Operador', users.name)  as user_name,
                if(teams.name is null, 'Sem Time', teams.name)  as team_name,
                user_team.team_id as team_id,
                DATE_FORMAT(meetings.created_at,'%d/%m/%Y') as f_created_at,
                if(meetings.updated_at is null,'Nunca Alterado', DATE_FORMAT(meetings.updated_at,'%d/%m/%Y')) as f_last_update,
                if(meetings.starts_at is null,'Sem Data', DATE_FORMAT(meetings.starts_at,'%d/%m/%Y - %H:%m:%s')) as start_at,
                if(meetings.ends_at is null,'Sem Data', DATE_FORMAT(meetings.ends_at,'%d/%m/%Y - %H:%m:%s')) as end_at
            ")
            ->leftJoin("meeting_statuses", "meeting_statuses.id", "=", "meetings.status_id")
            ->leftJoin("meeting_rooms", "meeting_rooms.id", "=", "meetings.meeting_room_id")
            ->leftJoin("customers", "customers.id", "=", "meetings.customer_id")
            ->leftJoin("users", "users.id", "=", "meetings.user_id")
            ->leftJoin("user_team", "user_team.user_id", "=", "users.id")
            ->leftJoin("teams", "teams.id", "=", "user_team.team_id")
            ->where("meetings.tenant_id", "=", $user->tenant_id)
            ->orderBy("meetings.created_at", "desc");

        switch ($type) {
            case "csv":
                $data = $this->applyFilterMeetings($data, $request);
                $csv = $data->get();
                foreach ($csv as $key => $value) $csv[$key]->code = \Hashids::encode($value->id);
                return ["success" => true, "csv" => $csv];
                break;
            case "table":
                $data = $this->applyFilterMeetings($data, $request);
                $table = $data->paginate(25);
                foreach ($table as $key => $value) {
                    $table[$key]->code = \Hashids::encode($value->id);
                    if ($value->team_id) $table[$key]->team_code = \Hashids::encode($value->team_id);
                    if ($value->user_id) $table[$key]->user_code = \Hashids::encode($value->user_id);
                    if ($value->meeting_room_id) $table[$key]->room_code = \Hashids::encode($value->meeting_room_id);
                }
                return ["success" => true, "data" => $table];
                break;
            case "team":
                $chart_data = $this->applyFilterMeetings($data, $request);
                $chart_data = $chart_data->selectRaw("count(*) as qty,  if(teams.name is null, 'Sem Time', teams.name)  as team_name")
                    ->groupBy("teams.id")
                    ->orderBy("qty", "desc")
                    ->pluck('qty', 'team_name')
                    ->all();
                return ["success" => true, "chart_data" => $chart_data];
                break;
            case "user":
                $chart_data = $this->applyFilterMeetings($data, $request);
                $chart_data = $chart_data->selectRaw("count(*) as qty,  if(users.name is null, 'Sem Operador', users.name)  as user_name")
                    ->groupBy("teams.id")
                    ->orderBy("qty", "desc")
                    ->pluck('qty', 'user_name')
                    ->all();
                return ["success" => true, "chart_data" => $chart_data];
                break;
            case "status":
                $chart_data = $this->applyFilterMeetings($data, $request);
                $chart_data = $chart_data->selectRaw("count(*) as qty,  if(meeting_statuses.name is null, 'Sem Status', meeting_statuses.name)  as status_name")
                    ->groupBy("meeting_statuses.id")
                    ->orderBy("qty", "desc")
                    ->pluck('qty', 'status_name')
                    ->all();
                return ["success" => true, "chart_data" => $chart_data];
                break;
            default:
                return ["success" => false];
                break;
        }
    }

    private function applyFilterMeetings($data, $request)
    {
        if (@$request["code"]) {
            $code = \Hashids::decode($request["code"]);
            if (@$code[0]) $data = $data->where("customers.id", $code[0]);
        }
        if (@$request["name"]) $data->where("customers.name", "like", "%" . $request["name"] . "%");
        if (@$request["created_at_interval"][0] && @$request["created_at_interval"][1]) {
            $date_start = Carbon::create($request["created_at_interval"][0])->format("Y-m-d");
            $date_end = Carbon::create($request["created_at_interval"][1])->format("Y-m-d");
            $data = $data->whereRaw("DATE(meetings.created_at) >= Date('{$date_start}') and DATE(meetings.created_at) <= Date('{$date_end}')");
        }
        if (@$request["range_data"][0] && @$request["range_data"][1]) {
            $date_start = Carbon::create($request["range_data"][0])->format("Y-m-d H:i:s");
            $date_end = Carbon::create($request["range_data"][1])->format("Y-m-d H:i:s");
            $data = $data->whereRaw("TIMESTAMP (meetings.starts_at) >= TIMESTAMP ('{$date_start}') and TIMESTAMP (meetings.ends_at) <= TIMESTAMP ('{$date_end}')");
        }
        if (@$request["team"]) {
            if ($request["team"] != "all") {
                $data->where("teams.id", "=", $request["team"]);
            }
        }
        if (@$request["room"]) {
            if ($request["room"] != "all") {
                $data->where("meetings.id", "=", $request["room"]);
            }
        }
        if (@$request["user"]) {
            if ($request["user"] != "all") {
                $data->where("users.id", "=", $request["user"]);
            }
        }
        if (@$request["status"]) {
            if ($request["status"] != "all") {
                $data->where("meetings.status_id", "=", $request["status"]);
            }
        }
        return $data->whereNull("meetings.deleted_at");
    }

    public function salesByTeam()
    {
        return view("admin.reports.sales_by_team");
    }

    public function salesByUser()
    {
        return view("admin.reports.sales_by_user");
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
                    if ($value->team_id) $table[$key]->team_code = \Hashids::encode($value->team_code);
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
        if (@$request["status"]) {
            if ($request["status"] != "all") {
                $data->where("sales.status_id", "=", $request["status"]);
            }
        }
        return $data->whereNull("sales.deleted_at");
    }
}
