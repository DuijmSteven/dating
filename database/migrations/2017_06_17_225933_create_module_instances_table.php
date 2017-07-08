<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateModuleInstancesTable
 */
class CreateModuleInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_instances', function (Blueprint $table) {
            $table->integer('module_id')->unsigned();
            $table->integer('layout_part_id')->unsigned();
            $table->integer('view_id')->unsigned();
            $table->smallInteger('priority')->unsigned()->default(100);

            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
            $table->foreign('layout_part_id')->references('id')->on('layout_parts')->onDelete('cascade');
            $table->foreign('view_id')->references('id')->on('views')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module_instances');
    }
}
