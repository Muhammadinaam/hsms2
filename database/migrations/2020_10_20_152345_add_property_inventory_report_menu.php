<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPropertyInventoryReportMenu extends Migration
{
    public function up()
    {
        $parent_menu_id = \DB::table('admin_menu')->where('order', 5000)->first()->id;
        CommonMigrations::addMenu($parent_menu_id, 5050, 'Property Inventory', 'fa-money', 'property-inventory', 'property_inventory');
        CommonMigrations::insertPermission('Property Inventory', 'property_inventory', 'GET', 'property-inventory');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        CommonMigrations::removeMenuByOrderRange(5030, 5040);
        \DB::table('admin_permissions')
            ->whereIn('name', ['Payment Inventory'])
            ->delete();
    }
}
