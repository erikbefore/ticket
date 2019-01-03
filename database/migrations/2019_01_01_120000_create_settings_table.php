<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('panichd_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lang')->unique()->nullable()->index();
            $table->string('slug')->unique()->index();
            $table->mediumText('value');
            $table->mediumText('default');
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
        Schema::drop('panichd_settings');
    }
}