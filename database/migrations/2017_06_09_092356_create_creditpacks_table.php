<?php

use App\Creditpack;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditpacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creditpacks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('description', 300);
            $table->string('image_url')->nullable();
            $table->mediumInteger('credits')->unsigned();
            $table->mediumInteger('price')->unsigned();
            $table->timestamps();
        });

        Creditpack::insert([
            [
                'name' => 'small',
                'credits' => 70,
                'price' => 6000,
                'description' => 'Dummy',
                'image_url' => 'Dummy'
            ],
            [
                'name' => 'medium',
                'credits' => 110,
                'price' => 9000,
                'description' => 'Dummy',
                'image_url' => 'Dummy'
            ],
            [
                'name' => 'large',
                'credits' => 170,
                'price' => 12000,
                'description' => 'Dummy',
                'image_url' => 'Dummy'
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
        Schema::dropIfExists('creditpacks');
    }
}
