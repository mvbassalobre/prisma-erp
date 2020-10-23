<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataToFlux extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('customer_goals', function (Blueprint $table) {
			$table->longtext("data")->nullable();
		});
		Schema::table('customer_flux_entries', function (Blueprint $table) {
			$table->longtext("data")->nullable();
		});
		Schema::table('customer_flux_years', function (Blueprint $table) {
			$table->longtext("data")->nullable();
		});
		Schema::table('customer_flux_year_expenses', function (Blueprint $table) {
			$table->longtext("data")->nullable();
		});
		Schema::table('customer_flux_year_sections', function (Blueprint $table) {
			$table->longtext("data")->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	}
}