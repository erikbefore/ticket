<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_system_origin')->index()->nullable(false);
            $table->string('menu')->nullable(false);
            $table->string('menu_sub')->nullable(false);
            $table->string('name')->nullable(false);
            $table->addColumn('tinyInteger', 'active', ['lenght' => 1, 'default' => '1'])->nullable(false);
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
        Schema::dropIfExists('module');
    }
}
