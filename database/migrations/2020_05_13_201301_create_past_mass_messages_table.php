<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePastMassMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('past_mass_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('body');
            $table->timestamps();
        });

        \App\PastMassMessage::insert([
            [
                'body' => 'Eet smakelijk, heb jij nog wat te doen vanavond?',
                'created_at' => '2020-05-13 15:25:30',
                'updated_at' => '2020-05-13 15:25:30'
            ],
            [
                'body' => 'Hoiii, hoe is je dag?',
                'created_at' => '2020-05-12 10:30:26',
                'updated_at' => '2020-05-12 10:30:26'
            ],
            [
                'body' => 'Hoi hoi, grauw dagje wel.. Al lijkt het zonnetje gelukkig wel door te komen! Tijd voor een leuke date of de hele dag mams in de watten aan het leggen? X',
                'created_at' => '2020-05-10 11:27:46',
                'updated_at' => '2020-05-10 11:27:46'
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('past_mass_messages');
    }
}
