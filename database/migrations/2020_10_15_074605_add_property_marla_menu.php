<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPropertyMarlaMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $parent_menu_id = \DB::table('admin_menu')->where('order', 8)->first()->id;
        CommonMigrations::addMenu($parent_menu_id, 60, 'Property Marlas', 'fa-money', 'property-marlas', 'property_marlas');
        CommonMigrations::insertEntityPermissions('Property Marla', 'Property Marlas', 'property_marla', 'property_marlas', 'property-marlas');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        CommonMigrations::removeMenuByOrderRange(60, 60);
        CommonMigrations::removeEntityPermissions('property_marla', 'property_marlas');
    }
}
