<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('land_purchases', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->datetime('date');
            $table->bigInteger("project_id");
            $table->bigInteger("phase_id");
            $table->string('land_information');
            $table->string('purchase_document')->nullable();
            $table->decimal('cost', 30, 2);
            $table->bigInteger('credit_account_id');

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
        Schema::dropIfExists('land_purchases');
    }
}
