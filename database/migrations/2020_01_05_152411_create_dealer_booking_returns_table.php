<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealerBookingReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealer_booking_returns', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->datetime("date");
            $table->bigInteger("dealer_id");
            $table->string("return_reason");
            $table->decimal("dealer_amount_returned", 30, 2);
            $table->bigInteger("dealer_amount_returned_account_id");
            
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
        Schema::dropIfExists('dealer_booking_returns');
    }
}
