<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Fields\{
    Card,
    Text,
    BelongsTo,
};
use Auth;

class Customers extends Resource
{
    public $model = \App\Http\Models\Customer::class;

    public function globallySearchable()
    {
        return true;
    }

    public function menu()
    {
        return "Cadastros";
    }

    public function menuIcon()
    {
        return "el-icon-document-add";
    }

    public function label()
    {
        return "Clientes";
    }

    public function singularLabel()
    {
        return "Cliente";
    }

    public function icon()
    {
        return "el-icon-s-custom";
    }

    public function search()
    {
        return ["name"];
    }

    public function table()
    {
        $user = Auth::user();
        $columns = [];
        $columns["name"] = ["label" => "Nome"];
        $columns["email"] = ["label" => "Email"];
        $columns["phones"] = ["label" => "Telefones", "sortable" => false];
        $columns["profession"] = ["label" => "Profissão"];
        if ($user->hasRole(["super-admin"])) $columns["tenant->name"] = ["label" => "Tenant", "sortable_index" => "tenant_id"];
        if ($user->hasRole(["super-admin", "admin"])) $columns["user->name"] = ["label" => "Responsável", "sortable_index" => "user_id"];
        $columns["f_created_at"] = ["label" => "Data de Criação", "sortable_index" => "created_at"];
        $columns["last_update"] = ["label" => "Ultima atualização", "sortable" => false];
        return $columns;
    }

    public function fields()
    {
        $fields = [
            new Text([
                "label" => "Nome Completo",
                "field" => "name",
                "required" => true,
                "placeholder" => "Digite o nome aqui ...",
                "rules" => "required|max:255"
            ]),
            new Text([
                "label" => "Email",
                "field" => "email",
                "required" => true,
                "placeholder" => "Digite o email aqui ...",
                "rules" => "required|max:255",
                "description" => "Notificações e lembretes serão enviados para este email"
            ]),
            new Text([
                "label" => "Data de Nascimento",
                "field" => "birthday",
                "type"  => "date",
            ]),
            new BelongsTo([
                "label" => "Gênero",
                "field" => "gender_id",
                "placeholder" => "Selecione o gênero ...",
                "model" => \App\Http\Models\Gender::class,
                "rules" => "required",
            ]),
            new BelongsTo([
                "label" => "Estado Civil",
                "field" => "marital_status_id",
                "placeholder" => "Selecione o estato civil ...",
                "model" => \App\Http\Models\MaritalStatus::class,
                "rules" => "required",
                "description" => "Apenas para o caso de pessoa física"
            ]),
        ];
        if (Auth::user()->hasRole(["super-admin"])) {
            $fields[] = new BelongsTo([
                "label" => "Tenant",
                "field" => "tenant_id",
                "model" => \App\Http\Models\Tenant::class,
                "rules" => "required"
            ]);
        }
        if (Auth::user()->hasRole(["super-admin", "admin"])) {
            $fields[] = new BelongsTo([
                "label" => "Responsável",
                "field" => "user_id",
                "model" => \App\User::class,
                "rules" => "required"
            ]);
        }
        $cards =  [new Card("Informações", $fields)];
        $cards[] =  new Card("Profissional", [
            new Text([
                "label" => "Profissão",
                "field" => "profession",
                "placeholder" => "Digite a profissão aqui ...",
            ])
        ]);
        $cards[] =  new Card("Contato", [
            new Text([
                "label" => "Telefone Fixo",
                "field" => "phone",
                "placeholder" => "Digite o telefone fixo aqui ...",
                "mask" => "(##) ####-####"
            ]),
            new Text([
                "label" => "Celular",
                "field" => "cellphone",
                "placeholder" => "Digite o celular aqui ...",
                "mask" => ['(##) ####-####', '(##) #####-####']
            ])
        ]);
        $cards[] =  new Card("Endereço", [
            new Text([
                "label" => "CEP",
                "field" => "zipcode",
                "placeholder" => "Digite o CEP aqui ...",
                "rules" => "required|max:255",
            ]),
            new Text([
                "label" => "Rua",
                "field" => "street",
                "placeholder" => "Digite a rua aqui ...",
            ]),
            new Text([
                "label" => "Número",
                "field" => "number",
                "placeholder" => "Digite o número aqui ...",
            ]),
            new Text([
                "label" => "Complemento",
                "field" => "complement",
                "placeholder" => "Digite o complemento aqui ...",
            ]),
            new Text([
                "label" => "Bairro",
                "field" => "district",
                "placeholder" => "Digite a bairro aqui ...",
            ]),
            new Text([
                "label" => "Estado",
                "field" => "state",
                "placeholder" => "Digite a estado aqui ...",
            ]),
            new Text([
                "label" => "Cidade",
                "field" => "city",
                "placeholder" => "Digite a cidade aqui ...",
            ]),
        ]);
        $cards[] =  new Card("Documentos", [
            new Text([
                "label" => "CPF/CNPJ",
                "field" => "cpfcnpj",
                "placeholder" => "Digite o CPF ou CNPJ aqui ...",
                "mask" => ['###.###.###-##', '##.###.###/####-##'],
                "rules" => "required|max:255",
                "required" => true,
                "description" => "Digite o CPF do cliente para o caso de cliente pessoa física ou CNPJ para cliente pessoa jurídica"
            ]),
            new Text([
                "label" => "RG/IE",
                "field" => "ierg",
                "placeholder" => "Digite o RG ou IE aqui ...",
                "rules" => "required|max:255",
                "required" => true,
                "description" => "Digite o RG do cliente para o caso de cliente pessoa física ou IE para cliente pessoa jurídica"
            ]),
            new Text([
                "type" => "date",
                "label" => "Data de Expedição",
                "field" => "date_exp_rg",
            ]),
            new Text([
                "label" => "Orgão Expedidor",
                "field" => "exp_rg",
                "placeholder" => "Digite o orgão expedidor aqui ...",
                "rules" => "max:3",
                "max" => 3,
                "mask"  => "AAA"
            ]),
            new Text([
                "label" => "UF de Emissão",
                "field" => "uf_rg",
                "placeholder" => "Digite o UF de emissão aqui ...",
                "rules" => "max:2",
                "max" => 2,
                "mask"  => "AA"
            ]),
        ]);
        $cards[] =  new Card("Dados Bancários", [
            new BelongsTo([
                "label" => "Banco",
                "field" => "bank_id",
                "placeholder" => "Selecione o banco ...",
                "model" => \App\Http\Models\Bank::class,
            ]),
            new Text([
                "label" => "Agencia",
                "field" => "agency",
                "placeholder" => "Digite a agência aqui ...",
                "mask" => ['####', '####-#', '####-##']
            ]),
            new Text([
                "label" => "Conta Corrente",
                "field" => "account",
                "placeholder" => "Digite a conta bancária ...",
                "mask" => ['#####-#', '######-#', '#######-#', '########-#', '#########-#', '###########-#']
            ]),
        ]);
        return $cards;
    }
}
