<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * @param int $amount
     * @param int $roleId
     * @return mixed
     */
    protected function createUsers(int $amount = 1, int $roleId = 2)
    {
        factory(\App\User::class)->create();

        return factory(\App\User::class, $amount)
            ->create()
            ->each(function (\App\User $user) use ($roleId) {
                $user->meta()->save(factory(\App\UserMeta::class)->make());
                $user->roles()->attach($roleId);
            });
    }
}