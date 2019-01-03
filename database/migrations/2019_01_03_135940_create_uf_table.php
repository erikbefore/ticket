<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUfTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uf', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 100)->nullable(false);
            $table->string('sigla', 2)->nullable(false);
            $table->smallInteger('codigo_ibge');
            $table->unsignedInteger('id_regional');
        });

        Schema::table('uf', function($table) {
            $table->foreign('id_regional')->references('id')->on('regional');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('uf', function (Blueprint $table) {
            //
        });
    }
}
