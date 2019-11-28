<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetAddressBeforeLogin()
    {
        $response = $this->json('GET','/api/user/addresses');
        $response->assertStatus(401);
    }
    public function testGetAddressAfterLogin()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('GET','/api/user/addresses');
        $response->assertStatus(200);
    }
}
