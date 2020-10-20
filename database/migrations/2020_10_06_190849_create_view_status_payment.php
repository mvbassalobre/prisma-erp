<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewStatusPayment extends Migration
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
		return " create view payment_status as
        SELECT status,tenant_id FROM `sale_payment` GROUP by tenant_id,status
        ";
	}

	private function dropView(): string
	{
		return  "DROP VIEW IF EXISTS payment_status";
	}
}