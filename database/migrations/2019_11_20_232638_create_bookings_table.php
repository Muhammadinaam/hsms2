<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers\BookingStatusConstants;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->datetime("date");
            $table->bigInteger("customer_id");
            $table->bigInteger("property_file_id");
            $table->decimal("customer_amount_received");
            $table->bigInteger("customer_amount_received_account_id");
            $table->bigInteger("dealer_id")->nullable();
            $table->decimal("dealer_commission_amount")->nullable();
            $table->string('status');

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
        Schema::dropIfExists('bookings');
    }
}
