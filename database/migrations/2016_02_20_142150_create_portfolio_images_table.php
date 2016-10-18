<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePortfolioImagesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('portfolio_images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('filename')->unique();
            $table->integer('category_id')->unsigned();
            $table->boolean('hidden');
            $table->boolean('featured')->default(0);
            $table->string('image_title')->nullable();
            $table->string('image_caption')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('portfolio_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('portfolio_images');
    }

}
