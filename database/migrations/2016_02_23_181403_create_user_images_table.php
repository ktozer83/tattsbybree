<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	Schema::create('user_images', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('quote_id')->unsigned();
            $table->string('filename')->unique();
            $table->string('user');
            $table->timestamps();

            $table->foreign('quote_id')->references('id')->on('quotes');
	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_images');
    }
}
