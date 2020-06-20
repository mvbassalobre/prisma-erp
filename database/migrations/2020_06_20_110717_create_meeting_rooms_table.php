<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meeting_rooms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tenant_id');
            $table->string("name");
            $table->string("size");
            $table->string("zipcode")->nullable();
            $table->string("state")->nullable();
            $table->string("city")->nullable();
            $table->string("district")->nullable();
            $table->string("street")->nullable();
            $table->string("number")->nullable();
            $table->string("complement")->nullable();
            $table->string("reference")->nullable();
            $table->text('data')->nullable();
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
        Schema::dropIfExists('meeting_rooms');
    }
}
