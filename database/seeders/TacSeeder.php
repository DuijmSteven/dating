<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

/**
 * Class TacSeeder
 */
class TacSeeder extends Seeder
{
    /**
     * TacSeeder constructor.
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
        DB::table('tacs')->truncate();

        $languages = [
            'en',
            'nl'
        ];

        foreach ($languages as $language) {
            factory(App\Tac::class)->create([
                'language' => $language
            ]);
        }

        // supposed to only apply to a single connection and reset it's self
        // but I like to explicitly undo what I've done for clarity
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
}