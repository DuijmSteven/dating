<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertLpsToLpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\LP::insert([
            [
                'title' => 'First full LP',
                'description' => 'The first full LP we created that has the most content'
            ],
            [
                'title' => 'First Ads LP',
                'description' => 'First Ads LP that has the 2 steps'
            ],
            [
                'title' => 'Second Ads LP',
                'description' => 'Second Ads LP'
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
    }
}
