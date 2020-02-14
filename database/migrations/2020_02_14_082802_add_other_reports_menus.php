<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOtherReportsMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $parent_menu_id = CommonMigrations::addMenu(0, 5000, 'Other Reports', 'fa-briefcase', '', null);
        CommonMigrations::addMenu($parent_menu_id, 5010, 'Dealers Files Report', 'fa-money', 'dealers-files-report', 'dealers_files_report');

        CommonMigrations::insertPermission('Dealers Files Report', 'dealers_files_report', 'GET', 'dealers-files-report');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        CommonMigrations::removeMenuByOrderRange(5000, 5010);
    }
}
