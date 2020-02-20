<?php

use Illuminate\Database\Seeder;
use App\Http\Models\{
    Gender,
    MaritalStatus,
    Bank,
    AddressType
};

class AuxiliarTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createGenders();
        $this->createMaritalStatuses();
        $this->createBanks();
        $this->createAddressesTypes();
    }

    private function createGenders()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table("genders")->truncate();
        foreach (["Masculino", "Feminino", "Outro"] as $value) {
            Gender::create([
                "name" => $value,
                "tenant_id" => 1
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function createMaritalStatuses()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table("marital_statuses")->truncate();
        foreach (["Solteiro(a)", "Casado(a)", "Divorciado(a)", "Viúvo(a)"] as $value) {
            MaritalStatus::create([
                "name" => $value,
                "tenant_id" => 1
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function createAddressesTypes()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table("addresses_types")->truncate();
        foreach (["Comercial", "Residencial"] as $value) {
            AddressType::create([
                "name" => $value,
                "tenant_id" => 1
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function createBanks()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table("banks")->truncate();
        $banks = [
            ["number" => "654", "name" => "BANCO A.J. RENNER S.A."],
            ["number" => "246", "name" => "BANCO ABC BRASIL S.A."],
            ["number" => "356", "name" => "BANCO ABN AMRO REAL S.A."],
            ["number" => "025", "name" => "BANCO ALFA S.A."],
            ["number" => "641", "name" => "BANCO ALVORADA S.A."],
            ["number" => "213", "name" => "BANCO ARBI S.A."],
            ["number" => "029", "name" => "BANCO BANERJ S.A."],
            ["number" => "038", "name" => "BANCO BANESTADO S.A."],
            ["number" => "740", "name" => "BANCO BARCLAYS S.A."],
            ["number" => "107", "name" => "BANCO BBM S/A"],
            ["number" => "031", "name" => "BANCO BEG S.A."],
            ["number" => "739", "name" => "BANCO BGN S.A."],
            ["number" => "096", "name" => "BANCO BM&F DE SERVIÇOS DE LIQUIDAÇÃO E CUSTÓDIA S.A."],
            ["number" => "394", "name" => "BANCO BMC S.A."],
            ["number" => "318", "name" => "BANCO BMG S.A."],
            ["number" => "752", "name" => "BANCO BNP PARIBAS BRASIL S.A."],
            ["number" => "248", "name" => "BANCO BOAVISTA INTERATLANTICO S.A."],
            ["number" => "218", "name" => "BANCO BONSUCESSO S.A."],
            ["number" => "036", "name" => "BANCO BRADESCO BBI S.A."],
            ["number" => "237", "name" => "BANCO BRADESCO S.A."],
            ["number" => "225", "name" => "BANCO BRASCAN S.A."],
            ["number" => "044", "name" => "BANCO BVA S.A."],
            ["number" => "263", "name" => "BANCO CACIQUE S.A."],
            ["number" => "222", "name" => "BANCO CALYON BRASIL S.A."],
            ["number" => "412", "name" => "BANCO CAPITAL S.A."],
            ["number" => "040", "name" => "BANCO CARGILL S.A."],
            ["number" => "266", "name" => "BANCO CEDULA S.A."],
            ["number" => "745", "name" => "BANCO CITIBANK S.A."],
            ["number" => "241", "name" => "BANCO CLASSICO S.A."],
            ["number" => "753", "name" => "BANCO COMERCIAL URUGUAI S.A."],
            ["number" => "215", "name" => "BANCO COMERCIAL DE DE INVESTIMENTO SUDAMERIS S.A."],
            ["number" => "756", "name" => "BANCO COOPERATIVO DO BRASIL S.A. - BANCOOB"],
            ["number" => "748", "name" => "BANCO COOPERATIVO SICREDI S.A."],
            ["number" => "075", "name" => "BANCO CR2 S/A"],
            ["number" => "721", "name" => "BANCO CREDIBEL S.A."],
            ["number" => "505", "name" => "BANCO CREDIT SUISSE (BRASIL) S.A."],
            ["number" => "229", "name" => "BANCO CRUZEIRO DO SUL S.A."],
            ["number" => "003", "name" => "BANCO DA AMAZONIA S.A. "],
            ["number" => "707", "name" => "BANCO DAYCOVAL S.A."],
            ["number" => "300", "name" => "BANCO DE LA NACION ARGENTINA"],
            ["number" => "495", "name" => "BANCO DE LA PROVINCIA DE BUENOS AIRES"],
            ["number" => "494", "name" => "BANCO DE LA REPUBLICA ORIENTAL DEL URUGUAY"],
            ["number" => "024", "name" => "BANCO DE PERNAMBUCO S.A. - BANDEPE"],
            ["number" => "456", "name" => "BANCO DE TOKYO-MITSUBISHI UFJ BRASIL S/A"],
            ["number" => "214", "name" => "BANCO DIBENS S.A."],
            ["number" => "001", "name" => "BANCO DO BRASIL S.A."],
            ["number" => "027", "name" => "BANCO DO ESTADO DE SANTA CATARINA S.A."],
            ["number" => "047", "name" => "BANCO DO ESTADO DE SERGIPE S.A."],
            ["number" => "037", "name" => "BANCO DO ESTADO DO PARÁ S.A."],
            ["number" => "039", "name" => "BANCO DO ESTADO DO PIAUÍ S.A. - BEP"],
            ["number" => "041", "name" => "BANCO DO ESTADO DO RIO GRANDE DO SUL S.A."],
            ["number" => "004", "name" => "BANCO DO NORDESTE DO BRASIL S.A."],
            ["number" => "265", "name" => "BANCO FATOR S.A."],
            ["number" => "224", "name" => "BANCO FIBRA S.A."],
            ["number" => "626", "name" => "BANCO FICSA S.A."],
            ["number" => "175", "name" => "BANCO FINASA S.A."],
            ["number" => "252", "name" => "BANCO FININVEST S.A."],
            ["number" => "233", "name" => "BANCO GE CAPITAL S.A."],
            ["number" => "734", "name" => "BANCO GERDAU S.A"],
            ["number" => "612", "name" => "BANCO GUANABARA S.A."],
            ["number" => "063", "name" => "BANCO IBI S.A. - BANCO MÚLTIPLO"],
            ["number" => "604", "name" => "BANCO INDUSTRIAL DO BRASIL S.A."],
            ["number" => "320", "name" => "BANCO INDUSTRIAL E COMERCIAL S.A."],
            ["number" => "653", "name" => "BANCO INDUSVAL S.A."],
            ["number" => "630", "name" => "BANCO INTERCAP S.A."],
            ["number" => "249", "name" => "BANCO INVESTCRED UNIBANCO S.A."],
            ["number" => "184", "name" => "BANCO ITAÚ BBA S.A."],
            ["number" => "652", "name" => "BANCO ITAÚ HOLDING FINANCEIRA S.A."],
            ["number" => "341", "name" => "BANCO ITAÚ S.A."],
            ["number" => "479", "name" => "BANCO ITAUBANK S.A."],
            ["number" => "074", "name" => "BANCO J. SAFRA S.A."],
            ["number" => "376", "name" => "BANCO J.P. MORGAN S.A."],
            ["number" => "217", "name" => "BANCO JOHN DEERE S.A."],
            ["number" => "076", "name" => "BANCO KDB DO BRASIL S.A."],
            ["number" => "757", "name" => "BANCO KEB DO BRASIL S.A."],
            ["number" => "600", "name" => "BANCO LUSO BRASILEIRO S.A."],
            ["number" => "212", "name" => "BANCO MATONE S.A."],
            ["number" => "243", "name" => "BANCO MÁXIMA S.A."],
            ["number" => "389", "name" => "BANCO MERCANTIL DO BRASIL S.A."],
            ["number" => "746", "name" => "BANCO MODAL S.A."],
            ["number" => "738", "name" => "BANCO MORADA S.A"],
            ["number" => "066", "name" => "BANCO MORGAN STANLEY DEAN WITTER S.A."],
            ["number" => "151", "name" => "BANCO NOSSA CAIXA S.A."],
            ["number" => "045", "name" => "BANCO OPPORTUNITY S.A."],
            ["number" => "623", "name" => "BANCO PANAMERICANO S.A."],
            ["number" => "611", "name" => "BANCO PAULISTA S.A."],
            ["number" => "613", "name" => "BANCO PECUNIA S.A."],
            ["number" => "643", "name" => "BANCO PINE S.A."],
            ["number" => "735", "name" => "BANCO POTTENCIAL S.A."],
            ["number" => "638", "name" => "BANCO PROSPER S.A."],
            ["number" => "747", "name" => "BANCO RABOBANK INTERNATIONAL BRASIL S.A."],
            ["number" => "633", "name" => "BANCO RENDIMENTO S.A."],
            ["number" => "741", "name" => "BANCO RIBEIRAO PRETO S.A."],
            ["number" => "072", "name" => "BANCO RURAL MAIS S.A."],
            ["number" => "453", "name" => "BANCO RURAL S.A."],
            ["number" => "422", "name" => "BANCO SAFRA S.A."],
            ["number" => "008", "name" => "BANCO SANTANDER BANESPA S.A."],
            ["number" => "250", "name" => "BANCO SCHAHIN S.A."],
            ["number" => "743", "name" => "BANCO SEMEAR S.A."],
            ["number" => "749", "name" => "BANCO SIMPLES S.A."],
            ["number" => "366", "name" => "BANCO SOCIETE GENERALE BRASIL S.A."],
            ["number" => "637", "name" => "BANCO SOFISA S.A."],
            ["number" => "347", "name" => "BANCO SUDAMERIS BRASIL S.A."],
            ["number" => "464", "name" => "BANCO SUMITOMO MITSUI BRASILEIRO S.A."],
            ["number" => "634", "name" => "BANCO TRIANGULO S.A."],
            ["number" => "208", "name" => "BANCO UBS PACTUAL S.A."],
            ["number" => "247", "name" => "BANCO UBS S.A."],
            ["number" => "116", "name" => "BANCO ÚNICO S.A."],
            ["number" => "655", "name" => "BANCO VOTORANTIM S.A."],
            ["number" => "610", "name" => "BANCO VR S.A."],
            ["number" => "370", "name" => "BANCO WESTLB DO BRASIL S.A."],
            ["number" => "021", "name" => "BANESTES S.A. BANCO DO ESTADO DO ESPIRITO SANTO"],
            ["number" => "719", "name" => "BANIF - BANCO INTERNACIONAL DO FUNCHAL (BRASIL), S.A."],
            ["number" => "744", "name" => "BANKBOSTON, N.A."],
            ["number" => "204", "name" => "BANKPAR BANCO MÚLTIPLO S.A."],
            ["number" => "073", "name" => "BB BANCO POPULAR DO BRASIL S.A."],
            ["number" => "069", "name" => "BPN BRASIL BANCO MÚLTIPLO S.A."],
            ["number" => "070", "name" => "BRB - BANCO DE BRASILIA S.A."],
            ["number" => "104", "name" => "CAIXA ECONOMICA FEDERAL"],
            ["number" => "477", "name" => "CITIBANK N.A."],
            ["number" => "487", "name" => "DEUTSCHE BANK S.A.BANCO ALEMAO"],
            ["number" => "751", "name" => "DRESDNER BANK BRASIL S.A. BANCO MULTIPLO"],
            ["number" => "210", "name" => "DRESDNER BANK LATEINAMERIKA AKTIENGESELLSCHAFT"],
            ["number" => "062", "name" => "HIPERCARD BANCO MÚLTIPLO S.A."],
            ["number" => "399", "name" => "HSBC BANK BRASIL S.A. - BANCO MULTIPLO"],
            ["number" => "492", "name" => "ING BANK N.V."],
            ["number" => "488", "name" => "JPMORGAN CHASE BANK, NATIONAL ASSOCIATION"],
            ["number" => "065", "name" => "LEMON BANK BANCO MULTIPLO S.A."],
            ["number" => "254", "name" => "PARANA BANCO S.A."],
            ["number" => "409", "name" => "UNIBANCO-UNIAO DE BANCOS BRASILEIROS S.A."],
            ["number" => "230", "name" => "UNICARD BANCO MÚLTIPLO S.A."]
        ];
        foreach ($banks as $value) {
            Bank::create([
                "name" => $value["name"],
                "number" => $value["number"],
                "tenant_id" => 1
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
