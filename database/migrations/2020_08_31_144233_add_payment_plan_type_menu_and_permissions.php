<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentPlanTypeMenuAndPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $parent_menu_id = \DB::table('admin_menu')->where('order', 8)->first()->id;
        CommonMigrations::addMenu($parent_menu_id, 50, 'Payment Plan Types', 'fa-list', 'payment-plan-types', 'payment_plan_types_list');
        CommonMigrations::insertEntityPermissions('Payment Plan Type', 'Payment Plan Types', 'payment_plan_type', 'payment_plan_types', 'payment-plan-types');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        CommonMigrations::removeMenu(50, 'Payment Plan Types');
        CommonMigrations::removeEntityPermissions('payment_plan_type', 'payment_plan_types');
    }
}
