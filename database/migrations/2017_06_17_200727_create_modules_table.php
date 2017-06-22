<?php

use App\Module;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateModulesTable
 */
class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable();
        });

        $files = File::allFiles(resource_path('views/frontend/modules/'));
        $modules = [];
        foreach ($files as $file) {
            $exploded = explode('/', $file);
            $filename = $exploded[count($exploded) - 1];
            $moduleName = explode('.', $filename)[0];
            $modules[] = $moduleName;
        }

        foreach ($modules as $module) {
            Module::insert(
                [
                    'name' => $module
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
        Schema::dropIfExists('modules');
    }
}
