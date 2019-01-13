<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('id_system_origin')->after('id')->nullable()->index();
            $table->string('cpf', 11)->after('id_system_origin')->nullable()->index();

            $table->addColumn('tinyInteger', 'active', ['lenght' => 1, 'default' => '1'])->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('cpf');
            $table->dropColumn('active');
            $table->dropColumn('id_system_origin');

        });
    }
}
