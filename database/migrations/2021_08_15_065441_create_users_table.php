<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_users', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('user_username')->unique();
            $table->string('user_email')->unique();;
            $table->string('password')->nullable(false);
            $table->string('user_password_str')->nullable(false);
            $table->integer('user_status_verifikasi')->default(0);
            $table->integer('user_role_id')->unsigned();
            $table->string('user_role_name');
            $table->foreign('user_role_id')->references('role_id')->on('m_roles');
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
        Schema::dropIfExists('users');
    }
}
