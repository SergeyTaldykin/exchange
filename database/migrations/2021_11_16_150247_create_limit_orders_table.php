<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLimitOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // todo (id, user_id, pair_id, qty, qty_left, price, status, created_at, updated_at)

        Schema::create('limit_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('pair_id');
            $table->decimal('qty',20, 8);
            $table->decimal('qty_left',20, 8);
            $table->decimal('price',20, 8);

            $table->unsignedTinyInteger('status');

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('pair_id')->references('id')->on('pairs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('limit_orders');
    }
}
