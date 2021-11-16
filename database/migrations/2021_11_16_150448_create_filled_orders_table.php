<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilledOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // todo filled_orders (id, pair_id, limit_order_id, operation_id, qty, created_at) - кого заполнили

        Schema::create('filled_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pair_id');
            $table->unsignedBigInteger('limit_order_id');
            $table->unsignedBigInteger('operation_id');

            $table->decimal('qty',20, 8);

            $table->timestamps();

            $table->foreign('limit_order_id')->references('id')->on('limit_orders');
            $table->foreign('operation_id')->references('id')->on('operations');
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
        Schema::dropIfExists('filled_orders');
    }
}
