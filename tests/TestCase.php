<?php

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Class TestCase
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    public function setUp()
    {
        parent::setUp();

        factory(\App\User::class, 2)
            ->create()
            ->each(function (\App\User $user) {
                $user->meta()->save(factory(\App\UserMeta::class)->make());
                $user->roles()->attach(1);
            });

        factory(\App\User::class, 20)
            ->create()
            ->each(function (\App\User $user) {
                $user->meta()->save(factory(\App\UserMeta::class)->make());
                $user->roles()->attach(2);
            });

        factory(\App\User::class, 20)
            ->create()
            ->each(function (\App\User $user) {
                $user->meta()->save(factory(\App\UserMeta::class)->make());
                $user->roles()->attach(3);
            });
    }

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        putenv('DB_CONNECTION=sqlite_testing');

        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }
}
