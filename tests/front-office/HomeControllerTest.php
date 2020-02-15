<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Class HomeControllerTest
 */
class HomeControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->createUsers();
    }

    /** @test */
    public function unauthenticatedRedirectsToLogin()
    {
        $response = $this->get('/');
        $response->assertSee('landingPage');
    }

    /** @test */
    public function showsContactPage()
    {
        $this->be(\App\User::find(1));

        $response = $this->get('/contact');
        $response->assertSee('CompanyInfo__Tile__body');
    }
}
