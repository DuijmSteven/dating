<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateLayoutPartModuleTable
 */
class CreateLayoutPartModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('layout_part_module', function (Blueprint $table) {
            $table->integer('layout_part_id')->unsigned();
            $table->integer('module_id')->unsigned();
            $table->smallInteger('priority')->unsigned()->default(100);
        });
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('layout_part_module');
    }
}
