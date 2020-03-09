<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PagSeguro;

class PagseguroController extends Controller
{
    public function teste()
    {
        // $this->simpleExample();
        // $this->planExample();
    }

    public function redirect(Request $request)
    {
        dd("redirect", $request->all);
    }

    public static function notification($information)
    {
        \Log::debug(print_r($information->getStatus()->getCode(), 1));
    }

    private function simpleExample()
    {
        $data = [
            'items' => [
                [
                    'id' => '99999',
                    'description' => 'Descrição do item',
                    'quantity' => '1',
                    'amount' => '500',
                    'weight' => '0',
                    'shippingCost' => '0',
                    'width' => '0',
                    'height' => '0',
                    'length' => '0',
                ]
            ],
            // 'shipping' => [
            //     // 'address' => [
            //     //     'postalCode' => '06410030',
            //     //     'street' => 'Rua Leonardo Arruda',
            //     //     'number' => '12',
            //     //     'district' => 'Jardim dos Camargos',
            //     //     'city' => 'Barueri',
            //     //     'state' => 'SP',
            //     //     'country' => 'BRA',
            //     // ],
            //     // 'type' => 2,
            //     // 'cost' => 30.4,
            // ],
            'sender' => [
                'email' => 'sender@sandbox.pagseguro.com.br',
                'name' => 'Isaque de Souza Barbosa',
                'documents' => [
                    [
                        'number' => '01234567890',
                        'type' => 'CPF'
                    ]
                ],
                'phone' => [
                    'number' => '985445522',
                    'areaCode' => '11',
                ],
                'bornDate' => '1988-03-21',
            ]
        ];
        $checkout = PagSeguro::checkout()->createFromArray($data);
        $information = $checkout->send($this->makeCredential());
        printf('<pre>%s</pre>', print_r($information, 1));
        printf('<a target="_BLANK" href="%s">Clique para pagar</a>', $information->getLink());
    }

    private function planExample()
    {
        $plan = [
            'body' => [
                'reference' => 'plano laravel pagseguro',
            ],
            'preApproval' => [
                'name' => 'Plano ouro - mensal',
                'charge' => 'AUTO', // outro valor pode ser MANUAL
                'period' => 'MONTHLY', //WEEKLY, BIMONTHLY, TRIMONTHLY, SEMIANNUALLY, YEARLY
                'amountPerPayment' => '125.00', // obrigatório para o charge AUTO - mais que 1.00, menos que 2000.00
                // 'membershipFee' => '50.00', //opcional - cobrado com primeira parcela
                'trialPeriodDuration' => 30, //opcional
                'details' => 'Decrição do plano', //opcional
            ]
        ];
        $plan = PagSeguro::plan()->createFromArray($plan);
        $information = $plan->send($this->makeCredential());
        printf('<pre>%s</pre>', print_r($information, 1));
        printf('<a target="_BLANK" href="%s">Clique para pagar</a>', $information->getLink());
    }

    private function makeCredential()
    {
        // pegar credencial das configs do tenant
        return PagSeguro::credentials()->create("5F58734FD5784A2691E2692B3BA2AC21", "bassalobre.vinicius@gmail.com");
    }
}
