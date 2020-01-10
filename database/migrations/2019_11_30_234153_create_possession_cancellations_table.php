<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePossessionCancellationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('possession_cancellations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->datetime('date');
            $table->string('cancellation_reason');
            $table->bigInteger('possession_id');

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
        Schema::dropIfExists('possession_cancellations');
    }
}
