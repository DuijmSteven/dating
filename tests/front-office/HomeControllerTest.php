<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Class HomeControllerTest
 */
class HomeControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->createUsers();

    }

    /** @test */
    public function unauthenticatedRedirectsToLogin()
    {
        $response = $this->get('/');
        $response->assertSee('login');
    }

   /* /** @test */
    public function showsHomepage()
    {
        $this->be(\App\User::find(1));

        $response = $this->get('/');
        $response->assertSee('Homepage');
    }

    /** @test */
    public function showsContactPage()
    {
        $this->be(\App\User::find(1));

        $response = $this->get('/contact');
        $response->assertSee('Contact');
        $response->assertSee('Contact -');
    }
}
