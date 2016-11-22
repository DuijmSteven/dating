<?php

use App\Helpers\ApplicationConstants\UserConstants;
use App\RoleUser;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator;

class UserTablesSeeder extends Seeder
{
    public function __construct(Generator $faker) {
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
        DB::table('users')->truncate();
        DB::table('user_meta')->truncate();
        DB::table('role_user')->truncate();

        foreach (UserConstants::getSelectableField('gender') as $key => $value) {

            $userAmount = 60;

            for($count = 0; $count < $userAmount; $count++) {
                $createdUser = factory(App\User::class)->create();

                $createdUser->meta()->save(factory(App\UserMeta::class)->make([
                    'user_id' => $createdUser->id,
                    'gender' => $key,
                ]));

                $roleUserInstance = new RoleUser([
                    'user_id' => $createdUser->id,
                    'role_id' => 3
                ]);

                $roleUserInstance->save();
            }
        }

        $adminUser = User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'active' => 1,
            'password' => Hash::make('12qwaszx')
        ]);

        $adminUserMetaInstance = new \App\UserMeta(['user_id' => $adminUser->id]);
        $adminUserMetaInstance->save();

        $adminUserRoleInstance = new \App\RoleUser([
            'user_id' => $adminUser->id,
            'role_id' => 1
        ]);
        $adminUserRoleInstance->save();

        // supposed to only apply to a single connection and reset it's self
        // but I like to explicitly undo what I've done for clarity
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
}