<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use ResourcesHelpers;
use marcusvbda\vstack\Controllers\ResourceController;
use Illuminate\Http\Request;
use App\Http\Models\{
    Customer,
    CustomerFluxYearEntries,
    Sale,
    SalePayment
};
use Auth;
use marcusvbda\vstack\Services\Messages;
use marcusvbda\vstack\Services\SendMail;
use App\Http\Models\{CustomerGoal, CustomerFluxYear, CustomerFluxYearSection, CustomerFluxSectionExpense};


class CustomersController extends Controller
{
    public function attendance($code)
    {
        $canAddSale = $this->canAddSale();
        $customer = Customer::with("sales", "sales.user", "sales.payment")->findOrFail($code);
        $formatted_meetings = $this->formatMeetings($customer);
        $data = $this->getViewData($code, $customer);
        return view("admin.customers.attendance", compact("customer", "data", "canAddSale", "formatted_meetings"));
    }

    public function formatMeetings($customer, $clientView = false)
    {
        $res = [];
        foreach ($customer->meetings()->with("status")->get() as $key => $meeting) {
            $res[$meeting->id] = [
                "id" => (string) $meeting->id,
                "rowId" => ($key + 1) % 4,
                "label" => $meeting->subject . " - " . $meeting->status->name,
                "code" => $meeting->code,
                "link" => $clientView ? $meeting->makeEventLink() : route("meeting.edit", $meeting->code),
                "style" => ["backgroundColor" => $meeting->status->color],
                "time" => [
                    "start" => $meeting->starts_at->timestamp * 1000,
                    "end" => $meeting->ends_at->timestamp * 1000
                ]
            ];
        }

        return $res;
    }

    private function canAddSale()
    {
        $user = Auth::user();
        return ($user->getSettings("pagseguro-email") || $user->getSettings("pagseguro-email"));
    }

    private function getViewData($code, $customer)
    {
        $resource = ResourcesHelpers::find("customers");
        if (!$resource->canView()) abort(403);
        $data = (new ResourceController())->makeViewData($code, $resource, $customer);
        return $data;
    }

    public function postNewSale(Request $request)
    {
        $data = $request->all();
        $customer = Customer::findOrFail($data["customer_id"]);
        $user = Auth::user();

        $sale = Sale::create([
            "customer_id" => $customer->id,
            "items" => $data["items"],
            "subtotal" => floatval($data["subtotal"]),
            "user_id" => $user->id
        ]);

        if ($data["payment"]) $this->makePayment($sale, $customer);

        $customer->appendToTimeline("Lançamento adicionado", "O lançamento <b>" . $sale->code . "</b> foi realizada pelo usuario <b>" . $user->name . "</b>");

        Messages::send("success", "Lançamento adicionado com sucesso !!");
        return ["success" => true];
    }

    private function makePayment($sale, $customer)
    {
        $payment = (new PagseguroController());
        $payment->makePayment($customer, $sale->code);
        foreach ($sale->items as $item) $payment->setItem($item["id"], $item["name"], $item["price"], $item["qty"]);
        $url = $payment->generateUrl();
        SalePayment::create([
            "sale_id" => $sale->id,
            "url" => $url,
            "reference" => $sale->code,
        ]);
    }

    public function destroySale(Request $request)
    {
        $customer = Customer::findOrFail($request["customer_id"]);
        $user = Auth::user();
        Sale::where("id", $request["sale"]["id"])->delete();
        $customer->appendToTimeline("Exclusão de Lançamento", "O lançamento <b>" . $request["sale"]["f_code"] . "</b> foi excluido pelo usuario <b>" . $user->name . "</b>");
        Messages::send("success", "Lançamento excluido com sucesso !!");
        return ["success" => true];
    }

    public function addGoal($id, Request $request)
    {
        $customer = Customer::findOrFail($request["customer_id"]);
        CustomerGoal::create($request->all());
        $customer->appendToTimeline("Metas e Objetivos", "Adição de Entrada nas metas e objetivos");
        return ["success" => true, "goals" => CustomerGoal::where("customer_id", $customer->id)->get()];
    }

    public function addYearEntry($id, Request $request)
    {
        $year = CustomerFluxYear::findOrFail($request["year"]["id"]);
        $data = $request->except("year");
        $data["year_id"] = $year->id;
        CustomerFluxYearEntries::create($data);
        return ["success" => true, "years" => $this->getYears($year->customer_id)];
    }

    private function getYears($customer_id)
    {
        return CustomerFluxYear::where("customer_id", $customer_id)->with(["entries", "sections", "sections.expenses"])->get();
    }

    public function editFluxEntry($id, Request $request)
    {
        CustomerFluxYearEntries::where("id", $request["id"])->update($request->except("id"));
        return ["success" => true, "years" => $this->getYears($id)];
    }

    public function editSection($id, Request $request)
    {
        CustomerFluxYearSection::where("id", $request["id"])->update($request->except(["id", "expenses"]));
        return ["success" => true, "section" => CustomerFluxYearSection::findOrFail($request["id"])->with(["expenses"])];
    }

    public function editGoal($id, Request $request)
    {
        CustomerGoal::where("id", $request["id"])->update($request->except("id"));
        return ["success" => true, "goals" => CustomerGoal::where("customer_id", $request["customer_id"])->get()];
    }

    public function deleteSection($id)
    {
        $section = CustomerFluxYearSection::findOrFail($id);
        $year_id = $section->year_id;
        $section->delete();
        return ["success" => true, "sections" => CustomerFluxYearSection::where("year_id", $year_id)->with(["expenses"])->get()];
    }

    public function deleteFluxEntry($id)
    {
        $entry = CustomerFluxYearEntries::findOrFail($id);
        $customer_id = $entry->year->customer_id;
        $entry->delete();
        return ["success" => true, "years" => $this->getYears($customer_id)];
    }

    public function editExpense($id, Request $request)
    {
        CustomerFluxSectionExpense::where("id", $request["id"])->update($request->except("id"));
        $expense = CustomerFluxSectionExpense::findOrFail($request["id"]);
        $year_id = $expense->section->year_id;
        return ["success" => true, "sections" => CustomerFluxYearSection::where("year_id", $year_id)->with(["expenses"])->get()];
    }

    public function deleteExpense($id)
    {
        $expense = CustomerFluxSectionExpense::findOrFail($id);
        $year_id = $expense->section->year_id;
        $expense->delete();
        return ["success" => true, "sections" => CustomerFluxYearSection::where("year_id", $year_id)->with(["expenses"])->get()];
    }

    public function deleteGoal($id)
    {
        $goal = CustomerGoal::findOrFail($id);
        $customer_id = $goal->customer_id;
        $goal->delete();
        return ["success" => true, "goals" => CustomerGoal::where("customer_id", $customer_id)->get()];
    }

    public function deleteFluxYear($id)
    {
        $year = CustomerFluxYear::findOrFail($id);
        $customer_id = $year->customer_id;
        $year->delete();
        return ["success" => true, "years" => $this->getYears($customer_id)];
    }

    public function addFluxYear($id, Request $request)
    {
        $customer = Customer::findOrFail($id);
        CustomerFluxYear::create([
            "value" => $request["value"],
            "customer_id" => $customer->id
        ]);
        $customer->appendToTimeline("Fluxo de Caixa", "Novo ano adicionado no fluxo de caixa");
        return ["success" => true, "years" => $this->getYears($customer->id)];
    }

    public function addExpense($id, Request $request)
    {
        $customer = Customer::findOrFail($id);
        CustomerFluxSectionExpense::create($request->all());
        $customer->appendToTimeline("Fluxo de Caixa", "Nova despesa adicionada no fluxo de caixa");
        return ["success" => true, "expenses" => CustomerFluxSectionExpense::where("section_id", $request["section_id"])->get()];
    }

    public function addSections($id, Request $request)
    {
        $year = CustomerFluxYear::findOrFail($request["year"]["id"]);
        $name = $request["section"];
        CustomerFluxYearSection::create(["name" => $name, "year_id" => $year->id]);
        return ["success" => true, "sections" => CustomerFluxYearSection::where("year_id", $year->id)->get()];
    }

    public function createAreaAccess($id)
    {
        $user = Auth::user();
        $customer = Customer::findOrFail($id);
        $customer->username =  $this->generateAreaCustomerUsername($customer);
        $pass = $this->generateAreaCustomerPassword($customer);
        $customer->password = md5($pass);
        $this->sendAccessEmail($customer->name, @$customer->email, $customer->username, $pass);
        $customer->save();
        $customer->appendToTimeline("Area do Cliente", "O usuário <b>$user->name</b> criou um acesso para area de cliente");
        Messages::send("success", "Usuário criado com sucesso e um email com os dados foram enviados para o cliente !!");
        return ["success" => true];
    }

    private function sendAccessEmail($name, $email, $username, $password)
    {
        $link = route('customer_area.index', ['code' => Auth::user()->tenant->code]);
        $appName = Config("app.name");
        $html = "
                <p>Olá {$name},</p>
                <p>Geramos um acesso para seus usuário em nossa area de cliente</p>
                <p>Clique no link abaixo para acessar a area de cliente :</p>
                <a href='{$link}' target='_BLANK'>{$link}</a>
                <p>Efetue o acesso utilizando as credenciais abaixo :</p>
                <p><b>Usuário : </b>{$username}</p>
                <p><b>Senha : </b>{$password}</p>
                <p style='margin-top:30px'>Obrigado, {$appName}";
        SendMail::to($email, "Seu Acesso a area do cliente " . $appName, $html);
    }

    public function removeAreaAccess($id)
    {
        $user = Auth::user();
        $customer = Customer::findOrFail($id);
        $customer->username =  null;
        $customer->password = null;
        $customer->save();
        Messages::send("success", "Usuário removido com sucesso !!");
        $customer->appendToTimeline("Area do Cliente", "O usuário <b>$user->name</b> removeu o acesso para area de cliente");
        return ["success" => true];
    }

    private function generateAreaCustomerUsername($customer)
    {
        if (Customer::where("id", "!=", $customer->id)->where("data->customer_area->username", $customer->email)->count() <= 0) return $customer->email;
        if (Customer::where("id", "!=", $customer->id)->where("data->customer_area->username", $customer->cpfcnpj)->count() <= 0) return preg_replace('/[^0-9]/', '', $customer->cpfcnpj);
        if (Customer::where("id", "!=", $customer->id)->where("data->customer_area->username", $customer->date_exp_rg)->count() <= 0) return preg_replace('/[^0-9]/', '', $customer->date_exp_rg);
        if (Customer::where("id", "!=", $customer->id)->where("data->customer_area->phone", $customer->phone)->count() <= 0) return preg_replace('/[^0-9]/', '', $customer->phone);
        return uniqid();
    }

    private function generateAreaCustomerPassword($customer)
    {
        if (@$customer->phone) return preg_replace('/[^0-9]/', '', $customer->phone);
        if (@$customer->cpfcnpj) return preg_replace('/[^0-9]/', '', $customer->cpfcnpj);
        if (@$customer->date_exp_rg) return preg_replace('/[^0-9]/', '', $customer->date_exp_rg);
        return uniqid();
    }

    public function getTimeline($id)
    {
        $customer = Customer::findOrFail($id);
        return ["success" => true, "data" => $customer->timeline ? $customer->timeline : []];
    }

    public function getSales($id)
    {
        $sales = Sale::where("customer_id", $id)->with(["user"])->get();
        return ["success" => true, "data" => $sales];
    }


    public function changePass(Request $request)
    {
        $data = $request->all();
        $customer = Customer::findOrFail($data["customer_id"]);
        $customer->password = md5($data["newpass"]);
        $customer->save();
        $customer->appendToTimeline("Area do Cliente", "O próprio cliente alterou sua senha");
        Messages::send("success", 'Senha alterada com sucesso  !!');
        return ["success" => true];
    }

    public function baixa(Request $request)
    {
        $customer = Customer::findOrFail($request["customer_id"]);
        $user = Auth::user();
        $sale = Sale::where("id", $request["sale"]["id"])->firstOrFail();
        $payment = $sale->payment;
        $payment->status = "Pagamento Aprovado";
        $payment->save();
        $customer->appendToTimeline("Baixa de Lançamento", "O lançamento <b>" . $request["sale"]["f_code"] . "</b> foi baixado pelo usuario <b>" . $user->name . "</b>");
        Messages::send("success", "Lançamento baixado com sucesso !!");
        return ["success" => true];
    }

    public function getMetrics($type, Request $request)
    {
        $resource = ResourcesHelpers::find("customers");
        $resourceController = new ResourceController();
        $data = $resourceController->getData($resource, $request);
        return $this->{"getMetric" . ucfirst($type)}($data);
    }

    protected function getmetricTotal($data)
    {
        return  $data->count();
    }

    protected function getmetricTeams($data)
    {
        return $data->selectRaw("count(*) as qty,  if(teams.name is null, 'Sem Time', teams.name)  as team_name")
            ->join("users", "users.id", "=", "customers.user_id")
            ->join("user_team", "user_team.user_id", "=", "customers.user_id")
            ->join("teams", "user_team.team_id", "=", "teams.id")
            ->groupBy("teams.id")
            ->orderBy("qty", "desc")
            ->pluck('qty', 'team_name')
            ->all();
    }

    protected function getmetricUsers($data)
    {
        return $data->selectRaw("count(*) as qty,  if(users.name is null, 'Sem Responsável', users.name)  as user_name")
            ->join("users", "users.id", "=", "customers.user_id")
            ->groupBy("customers.user_id")
            ->orderBy("qty", "desc")
            ->pluck('qty', 'user_name')
            ->all();
    }
}
