<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledgers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('project_id');
            $table->bigInteger('phase_id');
            $table->dateTime('date');
            $table->string('entry_type');
            $table->string('entry_id');
            $table->bigInteger('account_head_id');
            $table->bigInteger('person_id')->nullable();
            $table->bigInteger('file_id')->nullable();
            $table->string('description');
            $table->decimal('amount');
            CommonMigrations::commomColumns($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ledgers');
    }
}
