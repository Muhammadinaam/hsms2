<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveReceiptPaymentMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        CommonMigrations::removeMenu(3020, 'Payments');
        CommonMigrations::removeMenu(3030, 'Receipts');
        CommonMigrations::removeEntityPermissions('payment', 'payments');
        CommonMigrations::removeEntityPermissions('receipt', 'receipts');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
