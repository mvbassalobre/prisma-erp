<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewBirthdays extends Migration
{
    public function up()
    {
        DB::statement($this->createView());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement($this->dropView());
    }

    private function createView()
    {
        return " create view view_birthday as
        select users.id as id, users.name as name, MONTH(users.birthday) as month, DAY(users.birthday) as day, 'users' as resource, users.tenant_id as tenant_id from users where (DAY(users.birthday) is not null and MONTH(users.birthday) is not null)
        union
        select customers.id as id , customers.name, MONTH(customers.birthday) as month, DAY(customers.birthday) as day,'customers' as resource, customers.tenant_id as tenant_id from customers where (DAY(customers.birthday) is not null and MONTH(customers.birthday) is not null)
        ";
    }

    private function dropView(): string
    {
        return  "DROP VIEW IF EXISTS view_birthday";
    }
}
