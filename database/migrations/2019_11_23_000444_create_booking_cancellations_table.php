<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingCancellationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_cancellations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->datetime('date');
            $table->string('cancellation_reason');
            $table->bigInteger('booking_id');

            $table->decimal('customer_amount_returned', 30, 2)->nullable()->default(0);
            $table->bigInteger('customer_amount_returned_account_id');

            $table->decimal('dealer_commission_to_be_returned', 30, 2)->nullable()->default(0);

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
        Schema::dropIfExists('booking_cancellations');
    }
}
