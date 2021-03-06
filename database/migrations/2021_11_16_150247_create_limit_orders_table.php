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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('pair_id');
            $table->unsignedTinyInteger('operation_type');
            $table->unsignedTinyInteger('order_type');

            $table->decimal('qty',20, 8);
            $table->decimal('qty_filled',20, 8)->default(0);
            $table->decimal('price',20, 8);

            $table->unsignedTinyInteger('status');

            $table->timestamps(3);

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
