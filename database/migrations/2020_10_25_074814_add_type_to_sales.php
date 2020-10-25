<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToSales extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('sales', function (Blueprint $table) {
			$table->string("type")->default("Serviço");
			$table->string("product_status")->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('sales', function (Blueprint $table) {
			$table->DropColumn('type');
			$table->DropColumn('product_status');
		});
	}
}