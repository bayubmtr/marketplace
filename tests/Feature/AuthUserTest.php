<?php

namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthUserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    
    
    public function testCheckLogin()
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('GET','/api/user/check');
        $response->assertStatus(200);
    }
}
