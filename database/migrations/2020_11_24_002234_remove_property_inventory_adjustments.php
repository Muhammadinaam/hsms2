<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePropertyInventoryAdjustments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('property_inventory_ledgers');
        Schema::dropIfExists('property_inventory_adjustments');

        CommonMigrations::removeMenuByOrderRange(1013, 1013);
        CommonMigrations::removeEntityPermissions('property_inventory_adjustment', 'property_inventory_adjustments');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('property_inventory_ledgers', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('entry_id');
            $table->string('entry_type');

            $table->bigInteger("project_id");
            $table->bigInteger("phase_id");
            $table->datetime("date");


            $table->string('remarks');

            $table->decimal("marlas", 30, 2)->nullable();
            $table->bigInteger('property_type_id')->nullable();
            $table->boolean("is_farmhouse")->default(false);
            $table->boolean("is_corner")->default(false);
            $table->boolean("is_facing_park")->default(false);
            $table->boolean("is_on_boulevard")->default(false);

            $table->decimal('quantity', 30, 2);

            CommonMigrations::commonColumns($table);
        });

        Schema::create('property_inventory_adjustments', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger("project_id");
            $table->bigInteger("phase_id");
            $table->string('remarks');


            $table->datetime("date");

            $table->decimal("marlas", 30, 2)->nullable();
            $table->bigInteger('property_type_id')->nullable();
            $table->boolean("is_farmhouse")->default(false);
            $table->boolean("is_corner")->default(false);
            $table->boolean("is_facing_park")->default(false);
            $table->boolean("is_on_boulevard")->default(false);

            $table->decimal('quantity', 30, 2);

            CommonMigrations::commonColumns($table);
        });

        $parent_menu_id = \DB::table('admin_menu')->where('order', 1000)->first()->id;
        CommonMigrations::addMenu($parent_menu_id, 1013, 'Property Inventory Adjustments', 'fa-money', 'property-inventory-adjustments', 'property_inventory_adjustments');
        CommonMigrations::insertEntityPermissions('Property Inventory Adjustment', 'Property Inventory Adjustments', 'property_inventory_adjustment', 'property_inventory_adjustments', 'property-inventory-adjustments');
    }
}
