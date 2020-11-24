<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyInventoryLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_inventory_ledgers', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('entry_id');
            $table->string('entry_type');

            $table->bigInteger("project_id");
            $table->bigInteger("phase_id");
            $table->datetime("date");


            $table->string('remarks');

            $table->string("marlas")->nullable();
            $table->bigInteger('property_type_id')->nullable();
            $table->boolean("is_farmhouse")->default(false);
            $table->boolean("is_corner")->default(false);
            $table->boolean("is_facing_park")->default(false);
            $table->boolean("is_on_boulevard")->default(false);

            $table->decimal('quantity', 30, 2);

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
        Schema::dropIfExists('property_inventory_ledgers');
    }
}
