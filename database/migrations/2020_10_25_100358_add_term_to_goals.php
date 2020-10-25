<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTermToGoals extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('customer_goals', function (Blueprint $table) {
			$table->dropColumn('term');
			$table->dropColumn('term_type');
		});

		Schema::table('customer_goals', function (Blueprint $table) {
			$table->dropColumn('term');
			$table->dropColumn('term_type');
			$table->date('term')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('customer_goals', function (Blueprint $table) {
			//
		});
	}
}