<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_teachers', function (Blueprint $table) {
            $table->increments('teachers_id');
            $table->string('teachers_name');
            $table->string('teachers_telpn');
            $table->string('teachers_address');
            $table->string('teachers_birth_place');
            $table->timestamp('teachers_birth_date')->nullable();
            $table->integer('teachers_user_id')->unsigned();
            $table->foreign('teachers_user_id')->references('user_id')->on('m_users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teachers');
    }
}
