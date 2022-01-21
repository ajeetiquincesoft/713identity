<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreatmentOptionPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatment_option_packages', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('treatment_id')->unsigned();
			$table->foreign('treatment_id')->references('id')->on('treatments')->onDelete('cascade');
			$table->bigInteger('treatmentoption_id')->unsigned();
			$table->foreign('treatmentoption_id')->references('id')->on('treatment_options')->onDelete('cascade');
            $table->string('name')->nullable();
			$table->string('packagetype')->nullable();
			$table->string('price')->nullable();
			$table->string('min')->nullable();
			$table->string('max')->nullable();
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
        Schema::dropIfExists('treatment_option_packages');
    }
}
