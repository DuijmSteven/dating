<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Class UserControllerTest
 */
class UserControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->createUsers();
    }

    /** @test */
    public function showsHomepage()
    {
        $this->be(\App\User::find(1));

        $response = $this->get(url('/'));
        $response->assertSee('Homepage');
    }
}
