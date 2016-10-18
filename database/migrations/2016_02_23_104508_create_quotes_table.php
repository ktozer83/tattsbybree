<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
		$table->increments('id');
                $table->integer('user_id')->unsigned();
		$table->string('client_name');
		$table->string('email');
		$table->string('phone_number');
		$table->string('budget_range');
		$table->boolean('black_white');
		$table->boolean('colour');
                $table->longText('description')->nullable();
		$table->string('given_quote')->nullable();
		$table->boolean('consultation_needed')->default(0);
		$table->date('consultation_date')->nullable();
		$table->time('consultation_time')->nullable();
		$table->boolean('consultation_confirmed')->default(0);
		$table->boolean('appointment_made')->default(0);
                $table->date('appointment_date')->nullable();
                $table->time('appointment_time')->nullable();
                $table->integer('appointment_status_id')->unsigned()->default(1);
		$table->string('down_payment_cost')->nullable();
		$table->boolean('down_payment_paid')->default(0);
		$table->timestamps();

		$table->foreign('appointment_status_id')->references('id')->on('appointment_status');
                
                $table->foreign('user_id')->references('id')->on('users');
	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('quotes');
    }
}
