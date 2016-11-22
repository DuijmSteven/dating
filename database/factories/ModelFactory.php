<?php

use App\Helpers\ApplicationConstants\UserConstants;
use Faker\Generator as FakerGenerator;
use Faker\Provider as FakerProvider;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (FakerGenerator $faker) {
    static $password;

    return [
        'username' => $faker->userName,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('botpassword'),
        'active' => 1,
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\UserMeta::class, function (FakerGenerator $faker) {
    $faker->addProvider(new FakerProvider\nl_NL\Address($faker));

    return [
        'user_id'=> $faker->randomDigit,
        'dob' => $faker->dateTimeBetween('-60 years', '-18 years', date_default_timezone_get())->format('Y-m-d'),
        'gender' => array_rand(UserConstants::getSelectableField('gender')),
        'relationship_status' => array_rand(UserConstants::getSelectableField('relationship_status')),
        'city' => $faker->city,
        'height' => array_rand(UserConstants::getSelectableField('height')),
        'body_type' => array_rand(UserConstants::getSelectableField('body_type')),
        'eye_color' => array_rand(UserConstants::getSelectableField('eye_color')),
        'hair_color' => array_rand(UserConstants::getSelectableField('hair_color')),
        'smoking_habits' => array_rand(UserConstants::getSelectableField('smoking_habits')),
        'drinking_habits' => array_rand(UserConstants::getSelectableField('drinking_habits')),
        'country' => 'nl',
        'about_me' => $faker->realText($maxNbChars = 200, $indexSize = 2),
        'looking_for' => $faker->realText($maxNbChars = 200, $indexSize = 2),
        'province' => array_rand(UserConstants::getSelectableField('province'))
    ];
});