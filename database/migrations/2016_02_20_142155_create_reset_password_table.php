<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResetPasswordTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('password_resets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->string('token')->unique();
            $table->timestamp('created_at');
            $table->timestamp('expire_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('password_resets');
    }

}
