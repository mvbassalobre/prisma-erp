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
			if (Schema::hasColumn('customer_goals', 'term'))	$table->dropColumn('term');
			if (Schema::hasColumn('customer_goals', 'term_type'))	$table->dropColumn('term_type');
		});

		Schema::table('customer_goals', function (Blueprint $table) {
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