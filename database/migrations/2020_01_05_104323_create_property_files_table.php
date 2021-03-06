<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_files', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger("project_id");
            $table->bigInteger("phase_id");
            $table->string('file_number')->unique();

            $table->string("marlas")->nullable();
            $table->bigInteger('property_type_id')->nullable();
            $table->boolean("is_farmhouse")->default(false);
            $table->boolean("is_corner")->default(false);
            $table->boolean("is_facing_park")->default(false);
            $table->boolean("is_on_boulevard")->default(false);
            $table->decimal("cash_price", 30, 2)->nullable();
            $table->decimal("installment_price", 30, 2)->nullable();
            $table->decimal("cost", 30, 2)->nullable();

            $table->string('property_number')->nullable();
            $table->bigInteger('block_id')->nullable();
            
            $table->bigInteger("dealer_id")->nullable();
            $table->bigInteger("sold_by_dealer_id")->nullable();
            $table->bigInteger("holder_id")->nullable();
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
        Schema::dropIfExists('property_files');
    }
}
