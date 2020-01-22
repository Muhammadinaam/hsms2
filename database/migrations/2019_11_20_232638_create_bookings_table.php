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
            
            $table->decimal("form_processing_fee_received", 30, 2);
            $table->bigInteger("form_processing_fee_received_account_id");
            
            $table->enum('booking_type', [
                \App\Booking::BOOKING_TYPE_CASH,
                \App\Booking::BOOKING_TYPE_INSTALLMENT,
            ]);

            $table->decimal("down_payment_received", 30, 2);
            $table->bigInteger("down_payment_received_account_id");
            
            $table->bigInteger("dealer_id")->nullable();
            $table->decimal("dealer_commission_amount", 30, 2)->nullable()->default(0);
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
