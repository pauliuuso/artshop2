<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArtworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artworks', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->increments('id');
            $table->integer("author_id");
            $table->string("title", 100);
            $table->smallInteger("category");
            $table->integer("year");
            $table->text("description");
            $table->decimal("smallprice", 10, 0);
            $table->decimal("mediumprice", 10, 0);
            $table->decimal("bigprice", 10, 0);
            $table->boolean("active")->default(true);
            $table->string("thumbnail_name", 190);
            $table->string("picture_name", 190);
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
        Schema::dropIfExists('artworks');
    }
}
