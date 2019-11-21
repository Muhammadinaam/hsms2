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

            $table->string("name");
            $table->string("father_name");
            $table->string("husband_name");
            $table->string("cnic");
            $table->string("address");
            $table->string("phone");
            $table->enum("person_type", ['Agent', 'Customer', 'Supplier', 'Employee']);
            $table->string("kin_name");
            $table->string("kin_father_name");
            $table->string("kin_husband_name");
            $table->string("kin_cnic");
            $table->string("kin_address");
            $table->string("kin_phone");

            CommonMigrations::five($table);
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
