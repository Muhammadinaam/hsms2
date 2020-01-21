<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountHeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_heads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('idt')->unique()->nullable();
            $table->string('name');

            $table->enum('type', \App\AccountHead::ACCOUNT_TYPES);

            $table->boolean('is_reserved')->default(false);
            CommonMigrations::commonColumns($table);
        });

        DB::table('account_heads')
            ->insert([
                'idt' => \App\AccountHead::IDT_LAND_COST,
                'name' => 'Land Cost', 
                'type' => \App\AccountHead::NON_CURRENT_ASSETS,
                'is_reserved' => true
            ]);

        DB::table('account_heads')
            ->insert([
                'idt' => \App\AccountHead::IDT_PROPERTY_SALES_INCOME,
                'name' => 'Property Sales Income', 
                'type' => \App\AccountHead::SALES_SERVICE_INCOME,
                'is_reserved' => true
            ]);

        DB::table('account_heads')
            ->insert([
                'idt' => \App\AccountHead::IDT_PROPERTY_SALES_COST,
                'name' => 'Property Sales Cost', 
                'type' => \App\AccountHead::COST_DIRECT,
                'is_reserved' => true
            ]);

        DB::table('account_heads')
            ->insert([
                'idt' => \App\AccountHead::IDT_ACCOUNT_RECEIVABLE_PAYABLE,
                'name' => 'Account Receivable / Payable', 
                'type' => \App\AccountHead::RECEIVABLE_PAYABLE, 
                'is_reserved' => true
            ]);

        DB::table('account_heads')
            ->insert([
                'name' => 'Cash', 
                'type' => \App\AccountHead::CASH_BANK, 
                'is_reserved' => false
            ]);

        DB::table('account_heads')
            ->insert([
                'idt' => \App\AccountHead::IDT_FORM_PROCESSING_FEE_INCOME,
                'name' => 'Form Processing Fee Income', 
                'type' => \App\AccountHead::OTHER_INCOME, 
                'is_reserved' => true
            ]);

        DB::table('account_heads')
            ->insert([
                'idt' => \App\AccountHead::IDT_DEALER_COMMISSION_EXPENSE,
                'name' => 'Dealer Commission Expense', 
                'type' => \App\AccountHead::SELLING_EXPENSES, 
                'is_reserved' => true
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_heads');
    }
}
