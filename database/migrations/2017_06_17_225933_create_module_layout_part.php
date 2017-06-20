<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateModuleLayoutPart
 */
class CreateModuleLayoutPart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_layout_part', function (Blueprint $table) {
            $table->increments('layout_part_id');
            $table->string('module_id');
            $table->smallInteger('priority');
        });
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module_layout_part');
    }
}
