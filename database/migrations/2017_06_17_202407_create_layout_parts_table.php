<?php

use App\LayoutPart;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateLayoutPartsTable
 */
class CreateLayoutPartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('layout_parts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        LayoutPart::insert([
            ['name' => 'left-sidebar'],
            ['name' => 'right-sidebar'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('layout_parts');
    }
}
