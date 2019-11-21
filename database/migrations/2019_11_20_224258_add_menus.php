<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setup_menu_id = CommonMigrations::addMenu(0, 8, 'Setup', 'fa-briefcase', '', null);

        CommonMigrations::addMenu($setup_menu_id, 9, 'Projects', 'fa-plus', 'projects', 'projects_list');
        CommonMigrations::addMenu($setup_menu_id, 10, 'Phases', 'fa-gear', 'phases', 'phases_list');
        CommonMigrations::addMenu($setup_menu_id, 11, 'Blocks', 'fa-cubes', 'blocks', 'blocks_list');
        CommonMigrations::addMenu($setup_menu_id, 12, 'Property Types', 'fa-list', 'property-types', 'property_types_list');

        CommonMigrations::addMenu(0, 13, 'Properties', 'fa-home', 'properties', 'properties_list');
        CommonMigrations::addMenu(0, 14, 'Persons', 'fa-users', 'people', 'persons_list');
        CommonMigrations::addMenu(0, 15, 'Bookings', 'fa-book', 'bookings', 'bookings_list');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        CommonMigrations::removeMenu(9, 'Projects');
        CommonMigrations::removeMenu(10, 'Phases');
        CommonMigrations::removeMenu(11, 'Blocks');
        CommonMigrations::removeMenu(12, 'Property Types');
    }
}
