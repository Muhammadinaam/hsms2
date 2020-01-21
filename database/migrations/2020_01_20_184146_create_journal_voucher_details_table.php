<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalVoucherDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journal_voucher_details', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('journal_voucher_id');
            $table->bigInteger('account_head_id');
            $table->bigInteger('person_id')->nullable();
            $table->bigInteger('property_file_id')->nullable();
            $table->string('description')->nullable();
            $table->decimal('debit', 30, 2)->nullable();
            $table->decimal('credit', 30, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('journal_voucher_details');
    }
}
