<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCovid19Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('covid_19', function (Blueprint $table) {
            $table->id();
            $table->integer('country_id');
            $table->date('reported_date');
            $table->integer('new_cases');
            $table->integer('cumulative_cases');
            $table->integer('new_death');
            $table->integer('cumulative_death');
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
        Schema::dropIfExists('covid_19');
    }
}
