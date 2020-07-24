<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerFluxEntries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_flux_entries', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('name');
            $table->decimal('jan')->default(0);
            $table->decimal('fev')->default(0);
            $table->decimal('mar')->default(0);
            $table->decimal('abr')->default(0);
            $table->decimal('mai')->default(0);
            $table->decimal('jun')->default(0);
            $table->decimal('jul')->default(0);
            $table->decimal('ago')->default(0);
            $table->decimal('set')->default(0);
            $table->decimal('out')->default(0);
            $table->decimal('nov')->default(0);
            $table->decimal('dez')->default(0);
            $table->unsignedBigInteger('year_id');
            $table->foreign('year_id')
                ->references('id')
                ->on('customer_flux_years')
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
        Schema::dropIfExists('customer_flux_entries');
    }
}
