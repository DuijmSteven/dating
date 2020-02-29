<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUserEmailTypeInstanceColumnsAndForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_email_type_instances', function (Blueprint $table) {
            $table->dropForeign('user_email_type_instances_viewed_id_foreign');
            $table->dropForeign('user_email_type_instances_viewer_id_foreign');

            $table->renameColumn('viewed_id', 'receiver_id');
            $table->renameColumn('viewer_id', 'actor_id');
        });

        Schema::table('user_email_type_instances', function (Blueprint $table) {
            $table->integer('actor_id')->nullable()->change();

            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_email_type_instances', function (Blueprint $table) {
            $table->renameColumn('receiver_id', 'viewed_id');
            $table->renameColumn('actor_id', 'viewer_id');

            $table->dropForeign('user_email_type_instances_receiver_id_foreign');
            $table->dropForeign('user_email_type_instances_actor_id_foreign');

            $table->foreign('viewed_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('viewer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
}
