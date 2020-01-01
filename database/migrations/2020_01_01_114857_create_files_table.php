<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger("project_id");
            $table->bigInteger("phase_id");
            $table->string('file_number')->unique();
            $table->decimal("marlas");
            $table->bigInteger('property_id')->nullable();
            $table->bigInteger("dealer_id")->nullable();
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
        Schema::dropIfExists('files');
    }
}
