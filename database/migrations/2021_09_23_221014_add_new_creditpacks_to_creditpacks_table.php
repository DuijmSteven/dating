<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewCreditpacksToCreditpacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Creditpack::insert([
            [
                'name' => 'small',
                'credits' => 6,
                'price' => 999
            ],
            [
                'name' => 'medium',
                'credits' => 15,
                'price' => 2199
            ],
            [
                'name' => 'large',
                'credits' => 35,
                'price' => 4599
            ],
            [
                'name' => 'xl',
                'credits' => 55,
                'price' => 6969
            ],
            [
                'name' => 'xxl',
                'credits' => 100,
                'price' => 10999
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
