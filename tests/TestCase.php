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

    /**
     * @param int $amount
     * @param int $roleId
     * @return mixed
     */
    protected function createUsers(int $amount = 1, int $roleId = 2)
    {
        return factory(\App\User::class, $amount)
            ->create()
            ->each(function (\App\User $user) use ($roleId) {
                $user->meta()->save(factory(\App\UserMeta::class)->make());
                $user->roles()->attach($roleId);
            });
    }
}
