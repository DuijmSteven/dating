<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicChatItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_chat_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sender_id')->unsigned();
            $table->integer('operator_id')->unsigned()->nullable();
            $table->smallInteger('type')->unsigned();
            $table->string('body', 1000);
            $table->dateTime('published_at');
            $table->softDeletes();
            $table->timestamps();

            $table->index([
                'sender_id',
            ]);

            $table->index([
                'operator_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('public_chat_items');
    }
}
