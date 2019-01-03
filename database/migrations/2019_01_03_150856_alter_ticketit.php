<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTicketit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('panichd_tickets', function (Blueprint $table) {
            $table->unsignedInteger('id_uf')->after('id')->nullable();
        });

        Schema::table('panichd_tickets', function($table) {
            $table->foreign('id_uf')->references('id')->on('uf');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('panichd_tickets', function (Blueprint $table) {
            $table->dropColumn('id_uf');
        });
    }
}
