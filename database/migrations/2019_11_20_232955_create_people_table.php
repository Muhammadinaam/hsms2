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
            $table->string('picture')->nullable();
            $table->string("name");
            $table->string("business_name")->nullable();
            $table->string("father_name")->nullable();
            $table->string("husband_name")->nullable();
            $table->string("cnic");
            $table->string("address");
            $table->string("phone");
            $table->enum("person_type", [
                \App\Person::PERSON_TYPE_DEALER,
                \App\Person::PERSON_TYPE_CUSTOMER,
                \App\Person::PERSON_TYPE_SUPPLIER,
                \App\Person::PERSON_TYPE_EMPLOYEE]);
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
