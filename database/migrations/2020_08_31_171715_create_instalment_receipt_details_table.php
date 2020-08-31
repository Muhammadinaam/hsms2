<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstalmentReceiptDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instalment_receipt_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('instalment_receipt_id');
            $table->decimal('amount', 30, 2);
            $table->bigInteger('payment_plan_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instalment_receipt_details');
    }
}
