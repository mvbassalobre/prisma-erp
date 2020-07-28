<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerFluxYearExpenses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_flux_year_expenses', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('name');
            $table->double('jan')->default(0);
            $table->double('fev')->default(0);
            $table->double('mar')->default(0);
            $table->double('abr')->default(0);
            $table->double('mai')->default(0);
            $table->double('jun')->default(0);
            $table->double('jul')->default(0);
            $table->double('ago')->default(0);
            $table->double('set')->default(0);
            $table->double('out')->default(0);
            $table->double('nov')->default(0);
            $table->double('dez')->default(0);
            $table->unsignedBigInteger('section_id');
            $table->foreign('section_id')
                ->references('id')
                ->on('customer_flux_year_sections')
                ->onDelete('restrict');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_flux_year_expenses');
    }
}
