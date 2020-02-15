<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LandingPageTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->createUsers();
    }

    /** @test */
    public function canSeeRegistrationForm()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertPresent('.test--RegistrationForm');
        });
    }

    /** @test */
    public function canSeeLoginForm()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertPresent('.test--LoginForm');
        });
    }
}
