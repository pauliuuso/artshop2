<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("user_id")->nullable();
            $table->text("cart");
            $table->string("name", 50);
            $table->string("surname", 50);
            $table->string("email", 190);
            $table->string("payment_id", 190)->nullable();
            $table->string("name_shipping", 50);
            $table->string("surname_shipping", 50);
            $table->text("address");
            $table->integer("apartment");
            $table->string("country", 50);
            $table->string("postal_code", 50);
            $table->string("phone", 50);
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
        Schema::dropIfExists('orders');
    }
}
