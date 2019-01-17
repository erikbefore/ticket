<?php

use App\Model\Channel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTicketitTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('panichd_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('color');
        });

        Schema::create('panichd_priorities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('color');
            $table->integer('magnitude')->nullable();
        });

        Schema::create('panichd_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email_name')->nullable();
            $table->string('email')->nullable();
            $table->boolean('email_replies')->default(0);
            $table->string('color');
            $table->integer('create_level')->default('1');
        });

        Schema::create('panichd_categories_users', function (Blueprint $table) {
            $table->integer('category_id')->unsigned();
            $table->integer('user_id')->unsigned();
        });

        Schema::create('panichd_tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('uf_id')->nullable();
            $table->unsignedInteger('channel_id')->nullable(false)->default(Channel::SYSCOR['id']);
            $table->unsignedInteger('mod_id')->nullable();
            $table->unsignedInteger('type_id')->nullable();
            $table->unsignedInteger('origin_id')->nullable();
            $table->string('subject')->index();
            $table->integer('hidden')->default('0');
            $table->longText('content');
            $table->longText('html')->nullable();
            $table->longText('intervention')->nullable();
            $table->longText('intervention_html')->nullable();
            $table->integer('status_id')->unsigned()->index();
            $table->integer('priority_id')->unsigned()->index();
            $table->integer('creator_id')->nullable()->unsigned();
            $table->integer('user_id')->unsigned()->index();
            $table->integer('agent_id')->unsigned()->index();
            $table->integer('category_id')->unsigned()->index();
            $table->timestamp('completed_at')->nullable()->index();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('limit_date')->nullable();
            $table->timestamps();
        });

        Schema::create('panichd_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type',10)->default('note')->index();
            $table->longText('content');
            $table->longText('html')->nullable();
            $table->integer('user_id')->unsigned()->index();
            $table->integer('ticket_id')->unsigned()->index();
            $table->timestamps();
        });

//        Schema::create('panichd_audits', function (Blueprint $table) {
//            $table->increments('id');
//            $table->text('operation');
//            $table->integer('user_id')->unsigned();
//            $table->integer('ticket_id')->unsigned();
//            $table->timestamps();
//        });

        Schema::create('attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ticket_id');
            $table->unsignedInteger('comment_id')->nullable();
            $table->unsignedInteger('uploaded_by_id');
            $table->string('file_path', 1000);
            $table->string('original_attachment')->nullable();
            $table->string('original_filename');
            $table->unsignedInteger('bytes');
            $table->string('mimetype');
            $table->string('image_sizes')->nullable();
            $table->string('new_filename')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
        });

        Schema::create('panichd_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('text_color')->default('#0b5394');
            $table->string('bg_color')->default('#cfe2f3');
            $table->timestamps();
        });

        Schema::create('panichd_taggables', function (Blueprint $table) {
            $table->integer('tag_id');
            $table->morphs('taggable');
            $table->timestamps();
        });

        /**
         * CREATE TABLE `panichd_taggables` (
        `tag_id` int(11) NOT NULL,
        `taggable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `taggable_id` bigint(20) unsigned NOT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        KEY `panichd_taggables_taggable_type_taggable_id_index` (`taggable_type`,`taggable_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

         */

        Schema::create('panichd_closingreasons', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id');
            $table->string('text');
            $table->unsignedInteger('status_id')->nullable();
            $table->integer('ordering');
            $table->timestamps();
        });

        Schema::create('panichd_departments_persons', function (Blueprint $table) {
            $table->integer('department_id')->unsigned();
            $table->integer('person_id')->unsigned();
            $table->timestamps();
        });

        Schema::create('panichd_departments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('shortening');
            $table->integer('department_id')->unsigned();
            $table->timestamps();
        });



        Schema::table('panichd_categories_users', function (Blueprint $table) {
            $table->boolean('autoassign')->comment('new tickets autoassign enabled')->default('1');
        });


        Schema::table('panichd_tickets', function($table) {
            $table->foreign('type_id')->references('id')->on('ticket_type');
            $table->foreign('origin_id')->references('id')->on('ticket_origin');
            $table->foreign('uf_id')->references('id')->on('uf');
            $table->foreign('status_id')->references('id')->on('panichd_statuses');
            $table->foreign('priority_id')->references('id')->on('panichd_priorities');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('creator_id')->references('id')->on('users');
            $table->foreign('agent_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('panichd_categories');
            $table->foreign('mod_id')->references('id')->on('module');
            $table->foreign('channel_id')->references('id')->on('channel');
        });


        Schema::table('panichd_comments', function($table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('ticket_id')->references('id')->on('panichd_tickets');
        });

        Schema::table('panichd_closingreasons', function($table) {
            $table->foreign('category_id')->references('id')->on('panichd_categories');
            $table->foreign('status_id')->references('id')->on('panichd_statuses');
        });

        Schema::table('attachments', function($table) {
            $table->foreign('ticket_id')->references('id')->on('panichd_tickets');
            $table->foreign('comment_id')->references('id')->on('panichd_comments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       // Schema::drop('panichd_audits');
        Schema::drop('panichd_comments');
        Schema::drop('panichd_tickets');
        Schema::drop('panichd_categories_users');
        Schema::drop('panichd_categories');
        Schema::drop('panichd_priorities');
        Schema::drop('panichd_statuses');
    }
}
