<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_sections', function (Blueprint $table) {
            $table->increments('section_id');
            $table->string('section_name');
            $table->string('section_code');
            $table->tinyInteger('section_status');
            $table->integer('section_class_id')->unsigned();
            $table->foreign('section_class_id')->references('class_id')->on('m_class');
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
        Schema::dropIfExists('m_sections');
    }
}
