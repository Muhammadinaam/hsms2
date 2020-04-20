<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentPlanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_plan_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('payment_plan_id');
            $table->decimal('amount', 30, 2);
            $table->integer('number_of_payments');
            $table->integer('days_between_each_payment');
            $table->date('starting_date');
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
        Schema::dropIfExists('payment_plan_details');
    }
}
