<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_meta', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->text('about_me')->nullable();
            $table->date('dob')->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->tinyInteger('looking_for_gender')->nullable();
            $table->tinyInteger('relationship_status')->nullable();
            $table->string('city', 50)->nullable();
            $table->tinyInteger('province')->nullable();
            $table->tinyInteger('height')->nullable();
            $table->tinyInteger('body_type')->nullable();
            $table->tinyInteger('eye_color')->nullable();
            $table->tinyInteger('hair_color')->nullable();
            $table->tinyInteger('smoking_habits')->nullable();
            $table->tinyInteger('drinking_habits')->nullable();
            $table->string('country', 2)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_meta');
    }
}
