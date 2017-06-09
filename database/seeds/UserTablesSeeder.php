<?php

use App\Helpers\ApplicationConstants\UserConstants;
use App\RoleUser;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

class UserTablesSeeder extends Seeder
{
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
        DB::table('users')->truncate();
        DB::table('user_meta')->truncate();

        $accountTypes = UserConstants::selectableField('account_type');
        $accountTypesCount = count($accountTypes);

        /* -- Create admin user -- */
        $createdAdmin = factory(App\User::class)->create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12qwaszx')
        ]);

        $adminUserMetaInstance = $createdAdmin->meta()->save(factory(App\UserMeta::class)->make([
            'user_id' => $createdAdmin->id,
            'gender' => 0,
        ]));

        $adminUserRoleInstance = new \App\RoleUser([
            'user_id' => $createdAdmin->id,
            'role_id' => 1
        ]);
        $adminUserRoleInstance->save();

        /* -- Create peasants and bots for all genders -- */
        foreach (['peasant', 'bot'] as $role) {
            foreach (UserConstants::selectableField('gender') as $key => $value) {

                $userAmount = 25;

                for ($count = 0; $count < $userAmount; $count++) {
                    if ($role == 'bot') {
                        $accountType = 3;
                    } else {
                        $accountType = rand(1, $accountTypesCount);
                    }

                    $createdUser = factory(App\User::class)->create([
                        'account_type' => $accountType
                    ]);

                    $createdUser->meta()->save(factory(App\UserMeta::class)->make([
                        'user_id' => $createdUser->id,
                        'gender' => $key,
                    ]));

                    if ($role == 'peasant') {
                        $createdUser->account()->save(factory(App\UserAccount::class)->make([
                            'user_id' => $createdUser->id
                        ]));
                    }

                    $roleUserInstance = new RoleUser([
                        'user_id' => $createdUser->id,
                        'role_id' => UserConstants::selectableField('role', $role, 'array_flip')[$role]
                    ]);

                    $roleUserInstance->save();
                }
            }
        }

        // supposed to only apply to a single connection and reset it's self
        // but I like to explicitly undo what I've done for clarity
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
}