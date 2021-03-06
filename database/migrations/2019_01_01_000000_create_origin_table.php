<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOriginTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_origin', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descricao', 50)->nullable(false);
            $table->addColumn('tinyInteger', 'ativo', ['lenght' => 1, 'default' => '1'])->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('origin');
    }
}
