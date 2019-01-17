<?php

use App\Model\Channel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_system_origin')->nullable()->index();
            $table->smallInteger('uf_id_origin')->nullable()->index();
            $table->smallInteger('can_id_origin')->nullable()->index()->default(Channel::SYSCOR['id']);
            $table->string('name');
            $table->string('cpf', 11)->nullable()->index();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->boolean('panichd_admin')->default(0);
            $table->boolean('panichd_agent')->default(0);
            $table->boolean('ticketit_department')->nullable();
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
        Schema::dropIfExists('users');
    }
}
