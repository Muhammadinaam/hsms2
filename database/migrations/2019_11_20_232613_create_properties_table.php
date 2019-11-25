<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers\PropertyStatusConstants;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger("project_id");
            $table->bigInteger("phase_id");
            $table->bigInteger("block_id");
            $table->bigInteger("property_type_id");
            $table->string("name");
            $table->decimal("marlas");
            $table->boolean("is_corner");
            $table->boolean("is_facing_park");
            $table->boolean("is_on_boulevard");
            $table->decimal("cash_price");
            $table->decimal("installment_price");
            $table->enum('property_status', [PropertyStatusConstants::$available, 
                PropertyStatusConstants::$allotted, PropertyStatusConstants::$possessed])->default(PropertyStatusConstants::$available);

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
        Schema::dropIfExists('properties');
    }
}
