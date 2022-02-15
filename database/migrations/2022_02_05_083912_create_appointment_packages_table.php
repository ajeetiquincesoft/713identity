<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('appointment_id')->unsigned();
            $table->foreign('appointment_id')->references('id')->on('appointments');
            $table->bigInteger('treatmentoption_id')->unsigned();
            $table->foreign('treatmentoption_id')->references('id')->on('treatment_options');
            $table->bigInteger('treatmentoptionpackage_id')->unsigned();
            $table->foreign('treatmentoptionpackage_id')->references('id')->on('treatment_option_packages');
            $table->integer('quantity')->nullable();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('appointment_packages');
    }
}
