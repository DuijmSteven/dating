<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserEmailTypeInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_email_type_instances', function (Blueprint $table) {
            $table->integer('viewed_id')->unsigned();
            $table->integer('viewer_id')->unsigned();
            $table->string('email');
            $table->integer('email_type_id')->unsigned();
            $table->timestamps();

            $table->foreign('viewed_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('viewer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('email_type_id')->references('id')->on('email_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_email_type_instances');
    }
}
