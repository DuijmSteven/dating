<?php

use App\EmailType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->text('description');
        });

        EmailType::insert([
            [
                'name' => 'new_message',
                'description' => 'This email is sent when a user receives a new message',
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_types');
    }
}
