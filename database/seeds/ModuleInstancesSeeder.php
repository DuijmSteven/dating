<?php

use App\LayoutPart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

/**
 * Class LayoutPartModuleSeeder
 */
class ModuleInstancesSeeder extends Seeder
{
    /**
     * ArticlesSeeder constructor.
     * @param Faker $faker
     */
    public function __construct(Faker $faker) {
        $this->faker = $faker;
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        //disable foreign key check for this connection before running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('module_instances')->truncate();

        $moduleInstances = [
            [
                'module_id' => 3,
                'layout_part_id' => 1,
                'view_id' => 1,
                'priority' => 1,
            ],
            [
                'module_id' => 2,
                'layout_part_id' => 2,
                'view_id' => 1,
                'priority' => 1,
            ],
            [
                'module_id' => 2,
                'layout_part_id' => 2,
                'view_id' => 4,
                'priority' => 1,
            ],
        ];

        foreach ($moduleInstances as $instance) {
            factory(App\ModuleInstance::class)->create($instance);
        }

        // supposed to only apply to a single connection and reset it's self
        // but I like to explicitly undo what I've done for clarity
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}