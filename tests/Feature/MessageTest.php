<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetMessageBeforeLogin()
    {
        $response = $this->json('GET','/api/user/messages');
        $response->assertStatus(401);
    }
    public function testGetMessageAfterLogin()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('GET','/api/user/messages');
        $response->assertStatus(200);
    }
}