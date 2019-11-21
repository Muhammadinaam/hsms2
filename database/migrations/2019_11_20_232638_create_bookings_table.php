<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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

            $table->datetime("date_of_booking");
            $table->bigInteger("customer_id");
            $table->boolean("is_corner");
            $table->boolean("is_facing_park");
            $table->boolean("is_on_boulevard");
            $table->decimal("amount_received");
            $table->bigInteger("agent_id")->nullable();
            $table->decimal("agent_commission_amount")->nullable();

            CommonMigrations::five($table);
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
