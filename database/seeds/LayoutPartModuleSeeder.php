<?php

use App\LayoutPart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

/**
 * Class LayoutPartModuleSeeder
 */
class LayoutPartModuleSeeder extends Seeder
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
        DB::table('layout_part_module')->truncate();

        $layoutParts = LayoutPart::all()->toArray();

        $moduleAmountToAssign = rand(1, 2);

        foreach ($layoutParts as $layoutPart) {
            for ($i = 0; $i < $moduleAmountToAssign; $i++) {
                factory(App\LayoutPartModule::class)->create([
                    'layout_part_id' => $layoutPart['id'],
                    'module_id' => min($i + 1, 3),
                    'priority' => $i + 1
                ]);
            }
        }

        // supposed to only apply to a single connection and reset it's self
        // but I like to explicitly undo what I've done for clarity
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}