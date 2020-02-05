<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealerBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealer_bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->datetime("date");
            $table->bigInteger("dealer_id");
            $table->decimal("dealer_amount_received", 30, 2)->nullable();
            $table->string("dealer_amount_received_description")->nullable();
            $table->bigInteger("dealer_amount_received_account_id")->nullable();
            
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
        Schema::dropIfExists('dealer_bookings');
    }
}
