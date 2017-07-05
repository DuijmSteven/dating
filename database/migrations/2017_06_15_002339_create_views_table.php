<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateViewsTable
 */
class CreateViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('views', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('route_name');
            $table->timestamps();
        });

        $views = [
            'home' => 'home',
            'contact' => 'contact.get'
        ];

        foreach ($views as $viewName => $routeName) {
            \App\View::insert(
                [
                    'name' => $viewName,
                    'route_name' => $routeName
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('views');
    }
}
