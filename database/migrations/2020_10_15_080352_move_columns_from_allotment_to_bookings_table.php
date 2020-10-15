<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MoveColumnsFromAllotmentToBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('property_number');
            $table->bigInteger('block_id');
        });

        Schema::table('allotments', function (Blueprint $table) {
            $table->dropColumn(['property_number', 'block_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['property_number', 'block_id']);
        });

        Schema::table('allotments', function (Blueprint $table) {
            $table->string('property_number');
            $table->bigInteger('block_id');
        });
    }
}
