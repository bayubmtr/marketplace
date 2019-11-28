<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Validator;
use JWTAuth;
use App\User;

class UserController extends AdminController
{

    protected $avatar_path = 'images/users/';

	public function index(){
	$users = User::with('profile', 'user_status', 'user_role')->select('id', 'email', 'user_status_id', 'provider', 'user_role_id', 'created_at', 'updated_at')->withCount('order')->whereNotIn('user_role_id', [11, 111]);

    if(request()->has('name'))
      $users->whereHas('profile',function($q){
      $q->where('first_name','like','%'.request('name').'%');
      });

		if(request()->has('last_name'))
      $users->whereHas('profile',function($q){
      $q->where('last_name','like','%'.request('last_name').'%');
      });

		if(request()->has('email'))
			$users->where('email','like','%'.request('email').'%');

    if(request()->has('status'))
      $users->whereStatus(request('status'));

    

		return $users->paginate(request('pageLength'));
	}
    public function update(Request $request, $id){
        if(request('type') == 'banned'){
            $banned = User::find($id);
            $banned->user_status_id = 11;
            $banned->save();
            return ok(['message' => 'User Berhasil Dibanned !']);
        }elseif(request('type') == 'active'){
            $banned = User::find($id);
            $banned->user_status_id = 2;
            $banned->save();
            return ok(['message' => 'User Berhasil Aktifkan !']);
        }
    }
}
