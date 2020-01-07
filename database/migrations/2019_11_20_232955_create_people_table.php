<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('system_id')->unique();
            $table->string("name");
            $table->string("father_name")->nullable();
            $table->string("husband_name")->nullable();
            $table->string("cnic");
            $table->string("address");
            $table->string("phone");
            $table->enum("person_type", ['Dealer', 'Customer', 'Supplier', 'Employee']);
            $table->string("kin_name")->nullable();
            $table->string("kin_father_name")->nullable();
            $table->string("kin_husband_name")->nullable();
            $table->string("kin_cnic")->nullable();
            $table->string("kin_address")->nullable();
            $table->string("kin_phone")->nullable();

            CommonMigrations::commonColumns($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('people');
    }
}
