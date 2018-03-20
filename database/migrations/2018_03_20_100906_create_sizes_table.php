<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sizes', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal("width", 10, 0);
            $table->decimal("height", 10, 0);
            $table->decimal("price", 10, 0);
            $table->string("preview_name", 190)->nullable();
            $table->decimal("background_id", 10, 0)->nullable();
            $table->integer("artwork_id");
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
        Schema::dropIfExists('sizes');
    }
}
