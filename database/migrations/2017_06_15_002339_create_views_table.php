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
            'Home' => 'home',
            'Contact' => 'contact.get',
            'Users Search' => 'users.search.get',
            'Users Search Results' => 'users.search.results.get',
            'Users Overview' => 'users.overview',
            'User profile' => 'users.show',
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
