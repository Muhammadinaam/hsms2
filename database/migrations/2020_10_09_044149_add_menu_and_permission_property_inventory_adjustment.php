<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMenuAndPermissionPropertyInventoryAdjustment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $parent_menu_id = \DB::table('admin_menu')->where('order', 1000)->first()->id;
        CommonMigrations::addMenu($parent_menu_id, 1013, 'Property Inventory Adjustments', 'fa-money', 'property-inventory-adjustments', 'property_inventory_adjustments');
        CommonMigrations::insertEntityPermissions('Property Inventory Adjustment', 'Property Inventory Adjustments', 'property_inventory_adjustment', 'property_inventory_adjustments', 'property-inventory-adjustments');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        CommonMigrations::removeMenuByOrderRange(1013, 1013);
        CommonMigrations::removeEntityPermissions('property_inventory_adjustment', 'property_inventory_adjustments');
    }
}
