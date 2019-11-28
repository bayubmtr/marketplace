<?php

namespace App\Http\Controllers\User;
use Illuminate\Http\Request;
use Socialite;
use JWTAuth;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class SocialAuthController extends UserController
{
    public function providerRedirect($provider){

        if(!in_array($provider,['facebook','google','github']))
            return redirect('/login')->withErrors('This is not a valid link.');

        return Socialite::driver($provider)->redirect();
    }

    public function providerRedirectCallback($provider = '')
    {
        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (Exception $e) {
            return redirect('/auth/social');
        }

        $user_exists = \App\User::whereEmail($user->email)->first();

        if($user_exists)
            $token = JWTAuth::fromUser($user_exists);
        else {
            $new_user = new \App\User;
            $new_user->email = $user->email;
            $new_user->provider = $provider;
            $new_user->provider_unique_id = $user->id;
            $new_user->user_status_id = 2;
            $new_user->user_role_id = 1;
            $new_user->activation_token = Uuid::uuid1();
            $new_user->save();
            $name = explode(' ',$user->name);
            $profile = new \App\User_profile;
            $profile->user()->associate($new_user);
            $profile->first_name = array_key_exists(0, $name) ? $name[0] : 'user';
            $profile->last_name = array_key_exists(1, $name) ? $name[1] : null;
            $profile->save();
            $token = JWTAuth::fromUser($new_user);
        }
        return redirect('/auth/social?token='.$token);
    }

    public function getToken(){
        $token = request('token');
        return response()->json(['message' => 'You are successfully logged in!', 'token' => $token]);
    }
}
