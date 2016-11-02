<?php

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

    $userProfileFields = \UserConstants::PROFILE_FIELDS;

    return [
        'user_id'=> $faker->randomDigit,
        'dob' => $faker->dateTimeBetween('-60 years', '-18 years', date_default_timezone_get())->format('Y-m-d'),
        'gender' => $userProfileFields['gender'][array_rand($userProfileFields['gender'])],
        'relationship_status' => $userProfileFields['relationship_status'][array_rand($userProfileFields['relationship_status'])],
        'city' => $faker->city,
        'height' => $userProfileFields['height'][array_rand($userProfileFields['height'])],
        'body_type' => $userProfileFields['body_type'][array_rand($userProfileFields['body_type'])],
        'eye_color' => $userProfileFields['eye_color'][array_rand($userProfileFields['eye_color'])],
        'hair_color' => $userProfileFields['hair_color'][array_rand($userProfileFields['hair_color'])],
        'smoking_habits' => $userProfileFields['smoking_habits'][array_rand($userProfileFields['smoking_habits'])],
        'drinking_habits' => $userProfileFields['drinking_habits'][array_rand($userProfileFields['drinking_habits'])],
        'country' => 'nl',
        'about_me' => $faker->realText($maxNbChars = 200, $indexSize = 2),
        'looking_for' => $faker->realText($maxNbChars = 200, $indexSize = 2),
        'province' => $userProfileFields['province'][array_rand($userProfileFields['province'])],
    ];
});