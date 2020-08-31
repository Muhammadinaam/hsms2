<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentPlanTypeIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_plan_schedules', function (Blueprint $table) {
            $table->dropColumn(['description']);
            $table->bigInteger('payment_plan_type_id');
        });

        Schema::table('payment_plan_details', function (Blueprint $table) {
            $table->dropColumn(['description']);
            $table->bigInteger('payment_plan_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_plan_schedules', function (Blueprint $table) {
            $table->string('description');
            $table->dropColumn(['payment_plan_type_id']);
        });

        Schema::table('payment_plan_details', function (Blueprint $table) {
            $table->string('description');
            $table->dropColumn(['payment_plan_type_id']);
        });
    }
}
