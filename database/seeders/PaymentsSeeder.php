<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;
use App\User;

/**
 * Class PaymentsSeeder
 */
class PaymentsSeeder extends Seeder
{
    /**
     * PaymentsSeeder constructor.
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
        DB::table('payments')->truncate();

        $peasants = User::with('roles')->whereHas('roles', function ($query) {
            $query->where('name', '=', 'peasant');
        })->where('account_type', '!=', 1)->get();

        foreach ($peasants as $peasant) {
            if (rand(0, 1)) {
                factory(App\Payment::class)->create([
                    'user_id' => $peasant->id
                ]);
            }
        }

        // supposed to only apply to a single connection and reset it's self
        // but I like to explicitly undo what I've done for clarity
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}