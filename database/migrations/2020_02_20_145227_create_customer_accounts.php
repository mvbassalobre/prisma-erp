<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_accounts', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('ref_name')->nullable();
            $table->string('account');
            $table->string('account_digit')->nullable();
            $table->string('agency');
            $table->string('agency_digit')->nullable();
            $table->unsignedBigInteger('bank_id');
            $table->foreign('bank_id')
                ->references('id')
                ->on('banks')
                ->onDelete('restrict');
            $table->unsignedBigInteger('account_type_id');
            $table->foreign('account_type_id')
                ->references('id')
                ->on('account_types')
                ->onDelete('restrict');
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');
            $table->unsignedBigInteger('tenant_id');
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_accounts');
    }
}
