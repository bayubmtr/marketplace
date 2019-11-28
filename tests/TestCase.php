<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    protected function authenticate()
    {
        $user = ([
            'email' => 'test@gmail.com',
            'password' => '123123123'
        ]);
        $token = JWTAuth::attempt($user);
        return $token;
    }
    protected function authenticateAdmin()
    {
        $admin = ([
            'email' => 'admin@gmail.com',
            'password' => '123123'
        ]);
        $token = JWTAuth::attempt($admin);
        return $token;
    }
}
