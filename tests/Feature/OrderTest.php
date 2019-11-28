<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetOrderBeforeLogin()
    {
        $response = $this->json('GET','/api/user/purchases');
        $response->assertStatus(401);
    }
    public function testGetOrderIsEmptyAfterLogin()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('GET','/api/user/purchases');
        $response->assertStatus(404);
    }
}
