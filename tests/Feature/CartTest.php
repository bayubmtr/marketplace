<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetCartBeforeLogin()
    {
        $response = $this->json('GET','/api/user/carts');
        $response->assertStatus(401);
    }
    public function testGetCartAfterLogin()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('GET','/api/user/carts');
        $response->assertStatus(204);
    }
}
