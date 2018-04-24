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
            $table->string("name", 50)->nullable();
            $table->string("surname", 50)->nullable();
            $table->string("email", 190)->nullable();
            $table->string("payment_id", 190)->nullable();
            $table->string("name_shipping", 50)->nullable();
            $table->string("surname_shipping", 50)->nullable();
            $table->string("name_credit", 50)->nullable();
            $table->string("surname_credit", 50)->nullable();
            $table->string("card_type", 50)->nullable();
            $table->string("card_number", 50)->nullable();
            $table->string("payment_type", 50)->nullable();
            $table->text("address")->nullable();
            $table->integer("apartment")->nullable();
            $table->string("country", 50)->nullable();
            $table->string("postal_code", 50)->nullable();
            $table->string("phone", 50)->nullable();
            $table->integer("price")->nullable();
            $table->boolean("completed")->default(false);
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
