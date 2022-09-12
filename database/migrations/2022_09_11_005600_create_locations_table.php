<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->dateTime('arrival');
            $table->dateTime('departure')->nullable();
            $table->bigInteger('furniture_id', false, true);
            $table->foreign('furniture_id')->references('id')->on('furniture');
            $table->bigInteger('room_id', false, true);
            $table->foreign('room_id')->references('id')->on('rooms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('furniture_rooms');
    }
};
