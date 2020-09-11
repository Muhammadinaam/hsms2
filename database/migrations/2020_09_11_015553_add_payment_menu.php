<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $parent_menu_id = \DB::table('admin_menu')->where('order', 3000)->first()->id;
        CommonMigrations::addMenu($parent_menu_id, 3050, 'Payments', 'fa-list', 'payments', 'payments_list');
        CommonMigrations::insertEntityPermissions('Payment', 'Payments', 'payment', 'payments', 'payments');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        CommonMigrations::removeMenu(3050, 'Payments');
        CommonMigrations::removeEntityPermissions('payment', 'payments');
    }
}
