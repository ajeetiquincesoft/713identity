<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreatmentOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatment_options', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->bigInteger('treatment_id')->unsigned();
			$table->foreign('treatment_id')->references('id')->on('treatments')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->longText('image')->nullable();
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
        Schema::dropIfExists('treatment_options');
    }
}
