<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTicket extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('panichd_tickets', function (Blueprint $table) {
            $table->unsignedInteger('type_id')->after('id')->nullable();
            $table->unsignedInteger('origin_id')->after('type_id')->nullable();
        });

        Schema::table('panichd_tickets', function($table) {
            $table->foreign('type_id')->references('id')->on('ticket_type');
            $table->foreign('origin_id')->references('id')->on('ticket_origin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
