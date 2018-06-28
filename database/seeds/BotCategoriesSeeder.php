<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

/**
 * Class ArticlesSeeder
 */
class BotCategoriesSeeder extends Seeder
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
        DB::table('bot_categories')->truncate();

        $botCategories =
            \App\Helpers\ApplicationConstants\UserConstants::selectableFields('bot', 'private')['category'];
        foreach ($botCategories as $categoryId => $categoryName) {
            \App\BotCategory::create([
                'id' => $categoryId,
                'name' => $categoryName
            ]);
        }

        // supposed to only apply to a single connection and reset it's self
        // but I like to explicitly undo what I've done for clarity
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
}