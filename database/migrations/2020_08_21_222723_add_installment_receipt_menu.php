<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInstallmentReceiptMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $parent_menu_id = \DB::table('admin_menu')->where('order', 3000)->first()->id;
        CommonMigrations::addMenu($parent_menu_id, 3040, 'Instalment Receipts', 'fa-list', 'instalment-receipts', 'instalment_receipts_list');
        CommonMigrations::insertEntityPermissions('Instalment Receipt', 'Instalment Receipts', 'instalment_receipt', 'instalment_receipts', 'instalment-receipts');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        CommonMigrations::removeMenu(3040, 'Instalment Receipts');
        CommonMigrations::removeEntityPermissions('instalment_receipt', 'instalment_receipts');
    }
}
