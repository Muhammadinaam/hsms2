<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstalmentReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instalment_receipts', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->datetime('date');
            $table->bigInteger("property_file_id");
            $table->string("description");
            $table->decimal('amount', 30, 2);
            $table->bigInteger("amount_received_account_id");

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
        Schema::dropIfExists('instalment_receipts');
    }
}
