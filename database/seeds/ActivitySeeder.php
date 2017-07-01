<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

/**
 * Class ActivitySeeder
 */
class ActivitySeeder extends Seeder
{
    /**
     * ActivitySeeder constructor.
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
        DB::table('activity')->truncate();

        $activityAmount = 20;

        $users = \App\User::whereHas('roles', function ($query) {
            $query->where('name', 'bot');
        })
        ->take($activityAmount)
        ->get();

        foreach ($users as $user) {
            factory(App\Activity::class)->create([
                'user_id' => $user->id,
            ]);
        }

        // supposed to only apply to a single connection and reset it's self
        // but I like to explicitly undo what I've done for clarity
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
}