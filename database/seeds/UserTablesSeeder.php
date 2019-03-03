<?php

use App\Helpers\ApplicationConstants\UserConstants;
use App\RoleUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

/**
 * Class UserTablesSeeder
 */
class UserTablesSeeder extends Seeder
{
    private $faker;

    private $citiesWithCoordinates = [
        [
            'name' => 'Amsterdam',
            'lat' => 52.367984300000000,
            'lng' => 4.903561399999944
        ],
        [
            'name'=> 'Venlo',
            'lat' => 51.370374800000000,
            'lng' => 6.172403099999997
        ],
        [
            'name' => 'Den Haag',
            'lat' => 52.070538000000000,
            'lng' => 4.319340000000000
        ],
        [
            'name' => 'Rotterdam',
            'lat' => 51.928824000000000,
            'lng' => 4.478083000000000
        ],
        [
            'name' => 'Eindhoven',
            'lat' => 51.441717,
            'lng' => 5.477084
        ],
        [
            'name' => 'Leiden',
            'lat' => 52.160259,
            'lng' => 4.495118
        ],
        [
            'name' => 'Groningen',
            'lat' => 53.217584,
            'lng' => 6.565175
        ],
        [
            'name' => 'Utrecht',
            'lat' => 52.088694,
            'lng' => 5.121296
        ],
    ];

    /**
     * UserTablesSeeder constructor.
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

        $randomCityWithCoordinates = $this->citiesWithCoordinates[rand(0, count($this->citiesWithCoordinates) - 1)];

        $adminUserMetaInstance = $createdAdmin->meta()->save(factory(App\UserMeta::class)->make([
            'user_id' => $createdAdmin->id,
            'gender' => 1,
            'city' => $randomCityWithCoordinates['name'],
            'lat' => $randomCityWithCoordinates['lat'],
            'lng' => $randomCityWithCoordinates['lng']
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

                    if ($role === 'bot') {
                        $active = 1;
                        $deactivatedAt = null;
                    } else {
                        $active = $this->getRandomWeightedElement(
                            [1 => 85, 0 => 15]
                        );

                        if ($active) {
                            $deactivatedAt = null;
                        } else {
                            $deactivatedAt = $this->faker->dateTimeBetween(
                                '-1 years',
                                '-2 days'
                            )
                            ->format('Y-m-d');
                        }
                    }

                    $createdUser = factory(App\User::class)->create([
                        'account_type' => $accountType,
                        'active' => $active,
                        'deactivated_at' => $deactivatedAt,
                    ]);

                    $randomCityWithCoordinates = $this->citiesWithCoordinates[rand(0, count($this->citiesWithCoordinates) - 1)];

                    $createdUser->meta()->save(factory(App\UserMeta::class)->make([
                        'user_id' => $createdUser->id,
                        'gender' => $key,
                        'city' => $randomCityWithCoordinates['name'],
                        'lat' => $randomCityWithCoordinates['lat'],
                        'lng' => $randomCityWithCoordinates['lng']
                    ]));

                    if ($role == 'peasant') {
                        $createdUser->account()->save(factory(App\UserAccount::class)->make([
                            'user_id' => $createdUser->id
                        ]));
                    }

                    if($role == 'bot') {
                        $botCategories = UserConstants::selectableField('category', $role, 'array_values');

                        $categoryAmount = rand(1, 2);

                        for ($index = 0; $index < $categoryAmount; $index++) {
                            $categoryName = $botCategories[$index];
                            $botCategoryUserInstance = new \App\BotCategoryUser([
                                'user_id' => $createdUser->id,
                                'bot_category_id' => UserConstants::selectableField('category', $role, 'array_flip')[$categoryName]
                            ]);

                            $botCategoryUserInstance->save();
                        }
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

    /**
     * getRandomWeightedElement()
     * Utility function for getting random values with weighting.
     * Pass in an associative array, such as array('A'=>5, 'B'=>45, 'C'=>50)
     * An array like this means that "A" has a 5% chance of being selected, "B" 45%, and "C" 50%.
     * The return value is the array key, A, B, or C in this case.  Note that the values assigned
     * do not have to be percentages.  The values are simply relative to each other.  If one value
     * weight was 2, and the other weight of 1, the value with the weight of 2 has about a 66%
     * chance of being selected.  Also note that weights should be integers.
     *
     * @param array $weightedValues
     */
    function getRandomWeightedElement(array $weightedValues) {
        $rand = mt_rand(1, (int) array_sum($weightedValues));

        foreach ($weightedValues as $key => $value) {
            $rand -= $value;
            if ($rand <= 0) {
                return $key;
            }
        }
    }
}