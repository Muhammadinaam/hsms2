<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentPlanSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_plan_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('payment_plan_id');
            $table->bigInteger("property_file_id");
            $table->date('date');
            $table->decimal('amount', 30, 2);

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
        Schema::dropIfExists('payment_plan_schedules');
    }
}
