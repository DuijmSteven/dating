<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Class HomeControllerTest
 */
class HomeControllerTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function unauthenticatedRedirectsToLogin()
    {
        $response = $this->get(url('/'));
        $response->assertSee('login');
    }

    /** @test */
    public function showsHomepage()
    {
        $this->withoutMiddleware();
        $response = $this->get(url('/'));
        $response->assertSee('Homepage');
    }

    /** @test */
    public function showsContactPage()
    {
        $this->withoutMiddleware();
        $response = $this->get(url('/contact'));
        $response->assertSee('Contact');
    }
}
