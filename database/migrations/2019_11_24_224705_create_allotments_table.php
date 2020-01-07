<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers\AllotmentStatusConstants;

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
            $table->decimal('any_amount_received_before_or_at_allotment_time', 30, 2);
            $table->datetime('amount_received_date');
            $table->bigInteger("amount_received_account_id");
            $table->bigInteger("dealer_id")->nullable();
            $table->decimal("dealer_commission_amount", 30, 2)->nullable();
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
        Schema::dropIfExists('allotments');
    }
}
