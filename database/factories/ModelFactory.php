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
        'deactivated_at' => null
    ];
});

$factory->define(App\UserMeta::class, function (FakerGenerator $faker) {
    $faker->addProvider(new FakerProvider\nl_NL\Address($faker));

    $selectableProfileFields = UserConstants::selectableFields();

    return [
        'user_id'=> $faker->randomDigit,
        'dob' => $faker->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
        'gender' => array_keys($selectableProfileFields['gender'])[rand(0, count($selectableProfileFields['gender']) - 1)],
        'looking_for_gender' => array_keys($selectableProfileFields['looking_for_gender'])[rand(0, count($selectableProfileFields['looking_for_gender']) - 1)],
        'relationship_status' => array_keys($selectableProfileFields['relationship_status'])[rand(0, count($selectableProfileFields['relationship_status']) - 1)],
        'city' => $faker->city,
        'height' => array_keys($selectableProfileFields['height'])[rand(0, count($selectableProfileFields['height']) - 1)],
        'body_type' => array_keys($selectableProfileFields['body_type'])[rand(0, count($selectableProfileFields['body_type']) - 1)],
        'eye_color' => array_keys($selectableProfileFields['eye_color'])[rand(0, count($selectableProfileFields['eye_color']) - 1)],
        'hair_color' => array_keys($selectableProfileFields['hair_color'])[rand(0, count($selectableProfileFields['hair_color']) - 1)],
        'smoking_habits' => array_keys($selectableProfileFields['smoking_habits'])[rand(0, count($selectableProfileFields['smoking_habits']) - 1)],
        'drinking_habits' => array_keys($selectableProfileFields['drinking_habits'])[rand(0, count($selectableProfileFields['drinking_habits']) - 1)],
        'country' => 'nl',
        'about_me' => $faker->realText($maxNbChars = 200, $indexSize = 2),
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

$factory->define(App\Testimonial::class, function (FakerGenerator $faker) {
    return [
        'title' => rand(0, 1) ? $faker->sentence(6, true) : null,
        'body' => $faker->realText($maxNbChars = 300, $indexSize = 1),
        'status' => rand(0, 1),
        'pretend_at' => $faker->dateTimeBetween('-2 years', '-1 years', date_default_timezone_get())->format('Y-m-d'),
    ];
});


$factory->define(App\TestimonialUser::class, function (FakerGenerator $faker) {
    return [
        'name' => $faker->name,
        'testimonial_id' => 1,
        'username' => $faker->unique()->userName,
        'image_filename' => null,
        'dob' => $faker->dateTimeBetween('-35 years', '-27 years', date_default_timezone_get())->format('Y-m-d'),
        'gender' => rand(0, 1)
    ];
});

$factory->define(App\Faq::class, function (FakerGenerator $faker) {
    $sections = [
        'One',
        'Two',
        'Three',
        'Four',
        'Five'
    ];

    return [
        'section' => $sections[rand(0, count($sections) - 1)],
        'title' => $faker->sentence(6, true) . '?',
        'body' => $faker->realText($maxNbChars = 2000, $indexSize = 2),
        'status' => rand(0, 1)
    ];
});

$factory->define(App\Tac::class, function (FakerGenerator $faker) {
    $languages = [
        'en',
        'nl'
    ];

    return [
        'content' => $faker->realText($maxNbChars = 7000, $indexSize = 2),
        'language' => $languages[rand(0, 1)]
    ];
});