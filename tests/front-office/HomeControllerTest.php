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
        $this->be(\App\User::find(1));
        $response = $this->get(url('/'));
        $response->assertSee('Homepage');
    }

    /** @test */
    public function showsContactPage()
    {
        $this->be(\App\User::find(1));
        $response = $this->get(url('/contact'));
        $response->assertSee('Contact');
        $response->assertSee('Contact -');
    }
}
