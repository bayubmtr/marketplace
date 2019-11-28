<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetProfileBeforeLogin()
    {
        $response = $this->json('GET','/api/user/accounts');
        $response->assertStatus(401);
    }
    public function testGetProfileAfterLogin()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('GET','/api/user/accounts');
        $response->assertStatus(200);
    }
}
