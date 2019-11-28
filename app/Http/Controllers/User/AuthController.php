<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use App\Notifications\Activation;
use App\Notifications\Activated;
use App\Notifications\PasswordReset;
use App\Notifications\PasswordResetted;
use App\User;
use App\Admin;
use Carbon\Carbon;
use Config;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class AuthController extends UserController
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $token = null;
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'Email atau Password anda salah.'], 422);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'Ada masalah, silahkan coba lagi nanti !'], 500);
        }
        $user = \App\User::whereEmail(request('email'))->first();
        if($user->user_status_id == '1')
            return response()->json(['message' => 'Akun anda belum aktif. silahkan cek email anda'], 422);
        if($user->user_status_id == '11')
            return response()->json(['message' => 'Akun anda sudah dibanned. Silahkan hub admin.'], 422);
        if($user->user_status_id > '12')
            return response()->json(['message' => 'Ada masalah dengan akun anda. Silahkan hub admin.'], 422);
        if($user->user_status_id == '2')
            return response()->json(['message' => 'Anda berhasil logn !','token' => $token]);
    }
    public function getAuthUser(){
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json(['authenticated' => false]);
        }
        $user = JWTAuth::parseToken()->authenticate();
        $data = new \stdClass();
        $data->id = $user->id;
        $data->email = $user->email;
        $data->avatar = $user->profile->avatar;
        $data->name = $user->profile->first_name.' '.$user->profile->last_name;
        if(isset(auth()->user()->store->id)){
            $data->store_id = auth()->user()->store->id;
        }else{
            $data->store_id = null;
        }
        return ok($data);
    }
    public function check()
    {
        try {
            $user=JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response(['authenticated' => false]);
        }
        $online = User::find(auth()->user()->id);
        $online->last_activity = Carbon::now();
        $online->save();
        return response(['authenticated' => true]);
    }
    public function logout()
    {
        try {
            $token = JWTAuth::getToken();
            if ($token) {
                JWTAuth::invalidate($token);
            }
        } catch (JWTException $e) {
            return response()->json($e->getMessage(), 500);
        }
        return response()->json(['message' => 'You are successfully logged out!']);
    }
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password'
        ]);
        if($validation->fails())
            return response()->json(['message' => $validation->errors()],422);
        $user = \App\User::create([
            'email' => request('email'),
            'user_status_id' => '1',
            'password' => bcrypt(request('password')),
            'user_role_id' => '1'
        ]);
        $user->activation_token = Uuid::uuid1();
        $user->save();
        $profile = new \App\User_profile;
        $profile->first_name= request('first_name');
        if(request()->has('last_name')){
            $profile->last_name= request('last_name');
        }
        $user->profile()->save($profile);
        $user->notify(new Activation($user));
        return response()->json(['message' => 'You have registered successfully. Please check your email for activation!']);
    }
    public function activate(){
        $activation_token = request('token');
        $user = \App\User::whereActivationToken($activation_token)->first();
        if(!$user)
            return response()->json(['message' => 'Invalid activation token!'],422);
        if($user->user_status_id == 2)
            return response()->json(['message' => 'Your account has already been activated!'],422);
        if($user->user_status_id != 1)
            return response()->json(['message' => 'Invalid activation token!'],422);
        $user->user_status_id = 2;
        $user->activation_token = null;
        $user->save();
        $user->notify(new Activated($user));
        return response()->json(['message' => 'Your account has been activated!']);
    }
    public function password(Request $request){
        $validation = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first()],422);
        $user = \App\User::whereEmail(request('email'))->first();
        if(!$user)
            return response()->json(['message' => 'We couldn\'t found any user with this email. Please try again!'],422);
        $token = Uuid::uuid1();
        $delete = \App\Password_reset::where('email', request('email'))->delete();
        \DB::table('password_resets')->insert([
            'email' => request('email'),
            'token' => $token
        ]);
        $user->notify(new PasswordReset($user,$token));
        return response()->json(['message' => 'We have sent reminder email. Please check your inbox!']);
    }
    public function validatePasswordReset(Request $request){
        $validate_password_request = \DB::table('password_resets')->where('token','=',request('token'))->first();
        if(!$validate_password_request)
            return response()->json(['message' => 'Invalid password reset token!'],422);
        if(date("Y-m-d H:i:s", strtotime($validate_password_request->created_at . "+30 minutes")) < date('Y-m-d H:i:s'))
            return response()->json(['message' => 'Password reset token is expired. Please request reset password again!'],422);
        return response()->json(['message' => 'Token Valid', 'email' => $validate_password_request->email]);
    }
    public function reset(Request $request){
        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password'
        ]);
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first()],422);
        $user = \App\User::whereEmail(request('email'))->first();
        if(!$user)
            return response()->json(['message' => 'We couldn\'t found any user with this email. Please try again!'],422);
        $validate_password_request = \DB::table('password_resets')->where('email','=',request('email'))->where('token','=',request('token'))->first();
        if(!$validate_password_request)
            return response()->json(['message' => 'Invalid password reset token!'],422);
        if(date("Y-m-d H:i:s", strtotime($validate_password_request->created_at . "+30 minutes")) < date('Y-m-d H:i:s'))
            return response()->json(['message' => 'Password reset token is expired. Please request reset password again!'],422);
        $user->password = bcrypt(request('password'));
        $user->save();
        $user->notify(new PasswordResetted($user));
        return response()->json(['message' => 'Your password has been reset. Please login again!']);
    }
    public function changePassword(){
        $validation = Validator::make(request()->all(),[
            'password' => 'required',
            'new_password' => 'required|different:password|min:8',
            'confirm_new_password' => 'required|same:new_password'
        ]);
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first()],422);
        $user = JWTAuth::parseToken()->authenticate();
        if(!\Hash::check(request('password'),$user->password))
            return response()->json(['message' => 'Old password does not match! Please try again!'],422);
        $user->password = bcrypt(request('new_password'));
        $user->save();
        return response()->json(['message' => 'Your password has been changed successfully!']);
    }
}
