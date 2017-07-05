<?php

use App\Helpers\ApplicationConstants\UserConstants;
use App\Helpers\PaymentsHelper;
use App\LayoutPart;
use App\Module;
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
        'account_type' => 1,
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
        'title' => $faker->sentence(6, true),
        'body' => $faker->realText($maxNbChars = 10000, $indexSize = 2),
        'meta_description' => $faker->realText($maxNbChars = 200, $indexSize = 2),
        'status' => rand(0, 1)
    ];
});

$factory->define(App\Payment::class, function (FakerGenerator $faker) {
    $statusesCount = count(PaymentsHelper::$statuses);
    $paymentMethodsCount = count(PaymentsHelper::$methods);

    return [
        'user_id' => 2,
        'method' => rand(1, $paymentMethodsCount),
        'status' => rand(1, $statusesCount),
        'transactionId' => $faker->creditCardNumber,
        'description' => $faker->realText($maxNbChars = 100, $indexSize = 1)
    ];
});

$factory->define(App\Creditpack::class, function (FakerGenerator $faker) {
    return [
        'name' => 'random',
        'credits' => 2,
        'price' => 20,
        'description' => $faker->realText($maxNbChars = 100, $indexSize = 1),
        'image_url' => 'Dummy'
    ];
});

$factory->define(App\UserAccount::class, function () {
    return [
        'user_id' => 1,
        'credits' => rand(0, 100)
    ];
});

$factory->define(App\Role::class, function () {
    $roleNames = \App\Role::all()->pluck('name');
    return [
        'name' => $roleNames[rand(0, count($roleNames) - 1)],
    ];
});

$factory->define(App\ModuleInstance::class, function () {
    $moduleIds = Module::all()->pluck('id')->toArray();
    $layoutPartIds = LayoutPart::all()->pluck('id')->toArray();

    return [
        'module_id' => $moduleIds[array_rand($moduleIds)],
        'layout_part_id' => $moduleIds[array_rand($layoutPartIds)],
        'priority' => 1,
    ];
});

$factory->define(App\Activity::class, function (FakerGenerator $faker) {
    $faker->addProvider(new FakerProvider\Image($faker));
    $faker->addProvider(new FakerProvider\Lorem($faker));
    return [
        'type' => 'user',
        'thumbnail_url' => 'http://placehold.it/60x60',
        'title' => $faker->text(30),
        'text' => rand(0, 1) ? $faker->text(120) : null,
        'image_url' => rand(0, 1) ? 'http://placehold.it/400x300' : null,
        'user_id' => 1,
    ];
});