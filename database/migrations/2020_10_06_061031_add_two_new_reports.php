<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTwoNewReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $parent_menu_id = \DB::table('admin_menu')->where('order', 5000)->first()->id;
        CommonMigrations::addMenu($parent_menu_id, 5030, 'Property Files Collections', 'fa-money', 'property-files-collections', 'property_files_collections');
        CommonMigrations::insertPermission('Property Files Collections', 'property_files_collections', 'GET', 'property-files-collections');

        CommonMigrations::addMenu($parent_menu_id, 5040, 'Payment Plan Letter', 'fa-money', 'payment-plan-letter', 'payment_plan_letter');
        CommonMigrations::insertPermission('Payment Plan Letter', 'payment_plan_letter', 'GET', 'payment-plan-letter');
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
            ->whereIn('name', ['Payment Plan Letter', 'Property Files Collections'])
            ->delete();
    }
}
