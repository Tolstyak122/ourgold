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
        Schema::create('furniture', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title', 150)->nullable();
            $table->bigInteger('furniture_type_id', false, true);
            $table->foreign('furniture_type_id')->references('id')->on('furniture_types');
            $table->bigInteger('furniture_set_id', false, true);
            $table->foreign('furniture_set_id')->references('id')->on('furniture_sets');
            $table->unique(['furniture_type_id', 'furniture_set_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('furniture');
    }
};
