<?php

use App\LayoutPartView;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateLayoutPartViewTable
 */
class CreateLayoutPartViewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('layout_part_view', function (Blueprint $table) {
            $table->integer('view_id')->unsigned();
            $table->integer('layout_part_id')->unsigned();

            $table->foreign('view_id')->references('id')->on('views')->onDelete('cascade');
            $table->foreign('layout_part_id')->references('id')->on('layout_parts')->onDelete('cascade');
        });

//        LayoutPartView::insert([
//            [
//                'view_id' => 1,
//                'layout_part_id' => 1
//            ],
//            [
//                'view_id' => 1,
//                'layout_part_id' => 2
//            ],
//            [
//                'view_id' => 4,
//                'layout_part_id' => 2
//            ]
//        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('layout_part_view');
    }
}
