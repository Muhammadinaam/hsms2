<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFineIncomeAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('account_heads')
            ->insert([
                'idt' => \App\AccountHead::IDT_FORM_FINE_INCOME,
                'name' => 'Fine Income', 
                'type' => \App\AccountHead::OTHER_INCOME, 
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
        DB::table('account_heads')->where('idt', \App\AccountHead::IDT_FORM_FINE_INCOME)->delete();
    }
}
