<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllotmentCancellationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allotment_cancellations', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->datetime('date_of_cancellation');
            $table->string('cancellation_reason');
            $table->bigInteger('allotment_id');
            $table->decimal('customer_amount_returned');
            $table->bigInteger('customer_amount_returned_account_id');
            $table->decimal('dealer_commission_to_be_returned');

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
        Schema::dropIfExists('allotment_cancellations');
    }
}
