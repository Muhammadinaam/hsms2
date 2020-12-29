<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePartnershipDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partnership_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->bigInteger('parent_entity_id');
            $table->string('parent_entity_type');

            $table->bigInteger('partner_id');
            $table->decimal('ratio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partnership_details');
    }
}
