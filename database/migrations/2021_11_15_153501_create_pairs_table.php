<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePairsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pairs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('left_asset_id');
            $table->unsignedBigInteger('right_asset_id');
            $table->timestamps();

            $table->foreign('left_asset_id')->references('id')->on('assets');
            $table->foreign('right_asset_id')->references('id')->on('assets');
            $table->unique(['left_asset_id', 'right_asset_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pairs');
    }
}
