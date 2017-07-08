<?php

use App\NoteCategory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoteCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('note_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        NoteCategory::insert([
            [
                'name' => 'Naam'
            ],
            [
                'name' => 'Woonplaats'
            ],
            [
                'name' => 'Beroep'
            ],
            [
                'name' => 'Familie/Relatie'
            ],
            [
                'name' => 'Hobby’s'
            ],
            [
                'name' => 'Belangrijke data'
            ],
            [
                'name' => 'Seksuele voorkeuren'
            ],
            [
                'name' => 'Overige'
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
        Schema::dropIfExists('note_categories');
    }
}
