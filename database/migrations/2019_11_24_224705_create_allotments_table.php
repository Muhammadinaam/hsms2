<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllotmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allotments', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->datetime('date_of_allotment');
            $table->bigInteger('booking_id');
            $table->bigInteger('property_id');
            $table->decimal('any_amount_received_before_or_at_allotment_time');
            $table->datetime('amount_received_date');
            $table->bigInteger("amount_received_account_id");
            $table->bigInteger("agent_id")->nullable();
            $table->decimal("agent_commission_amount")->nullable();

            CommonMigrations::commonColumns($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('allotments');
    }
}
