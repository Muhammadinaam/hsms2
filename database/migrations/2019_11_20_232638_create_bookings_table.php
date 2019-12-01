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

            $table->string('booking_sequence_number')->nullable()->unique();

            $table->datetime("date_of_booking");
            $table->bigInteger("project_id");
            $table->bigInteger("phase_id");
            $table->bigInteger("property_type_id");
            $table->bigInteger("customer_id");
            $table->decimal("booking_for_marlas");
            $table->boolean("is_corner");
            $table->boolean("is_facing_park");
            $table->boolean("is_on_boulevard");
            $table->decimal("customer_amount_received");
            $table->bigInteger("customer_amount_received_account_id");
            $table->bigInteger("agent_id")->nullable();
            $table->decimal("agent_commission_amount")->nullable();
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
