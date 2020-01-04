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
                'idt' => \App\AccountHead::IDT_ACCOUNT_RECEIVABLE_PAYABLE,
                'name' => 'Account Receivable / Payable', 
                'type' => 'Receivable / Payable', 
                'is_reserved' => true
            ]);

        DB::table('account_heads')
            ->insert([
                'name' => 'Cash', 
                'type' => 'Cash / Bank', 
                'is_reserved' => false
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
