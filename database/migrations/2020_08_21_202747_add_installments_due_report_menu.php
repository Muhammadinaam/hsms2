<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInstallmentsDueReportMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $parent_menu_id = \DB::table('admin_menu')->where('order', 5000)->first()->id;
        CommonMigrations::addMenu($parent_menu_id, 5020, 'Instalments Due Report', 'fa-money', 'instalments-due-report', 'instalments_due_report');

        CommonMigrations::insertPermission('Instalments Due Report', 'instalments_due_report', 'GET', 'instalments-due-report');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        CommonMigrations::removeMenuByOrderRange(5020, 5020);
        \DB::table('admin_permissions')
            ->where('name', 'Instalments Due Report')
            ->delete();
    }
}
