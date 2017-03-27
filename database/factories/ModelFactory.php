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

    $selectableProfileFields = UserConstants::selectableFields();

    return [
        'user_id'=> $faker->randomDigit,
        'dob' => $faker->dateTimeBetween('-60 years', '-18 years', date_default_timezone_get())->format('Y-m-d'),
        'gender' => array_rand($selectableProfileFields['gender']),
        'relationship_status' => array_rand($selectableProfileFields['relationship_status']),
        'city' => $faker->city,
        'height' => array_rand($selectableProfileFields['height']),
        'body_type' => array_rand($selectableProfileFields['body_type']),
        'eye_color' => array_rand($selectableProfileFields['eye_color']),
        'hair_color' => array_rand($selectableProfileFields['hair_color']),
        'smoking_habits' => array_rand($selectableProfileFields['smoking_habits']),
        'drinking_habits' => array_rand($selectableProfileFields['drinking_habits']),
        'country' => 'nl',
        'about_me' => $faker->realText($maxNbChars = 200, $indexSize = 2),
        'looking_for' => $faker->realText($maxNbChars = 200, $indexSize = 2),
        'province' => array_rand($selectableProfileFields['province'])
    ];
});

$factory->define(App\Article::class, function (FakerGenerator $faker) {
    return [
        'title' => $faker->unique()->sentence(6, true),
        'body' => $faker->text(rand(500, 5000)),
        'status' => rand(0, 1)
    ];
});