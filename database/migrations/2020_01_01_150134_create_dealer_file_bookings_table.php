<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealerFileBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealer_file_bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->datetime("date");
            $table->bigInteger("dealer_id");
            $table->decimal("dealer_amount_received");
            $table->bigInteger("dealer_amount_received_account_id");
            
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
        Schema::dropIfExists('dealer_file_bookings');
    }
}
