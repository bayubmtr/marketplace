<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Validator;
use JWTAuth;
use App\User;

class ManageAdminController extends AdminController
{
	public function index(){
	$users = User::with('profile', 'user_status', 'user_role')->select('id', 'email', 'user_status_id', 'provider', 'user_role_id', 'created_at', 'last_activity', 'updated_at')->whereIn('user_role_id', [11]);
	return $users->paginate(request('pageLength'));
	}
    public function update(Request $request, $id){
        if(request('type') == 'inactive'){
            $banned = User::find($id);
            $banned->user_status_id = 11;
            $banned->save();
            return ok(['message' => 'AdminBerhasil Dibanned !']);
        }elseif(request('type') == 'active'){
            $banned = User::find($id);
            $banned->user_status_id = 2;
            $banned->save();
            return ok(['message' => 'Admin Berhasil Aktifkan !']);
        }
    }
    public function destroy($id){
        $delete = User::find($id);
        $delete->user_role_id = 1;
        $delete->save();
        return ok(['message' => 'admin berhasil dihapus !']);
    }
    public function store(Request $request){
        $user = User::where('email', request('email'))->first();
        if($user){
            $user->user_role_id = 11;
            $user->save();
            return ok(['message' => 'admin berhasil ditambahkan !']);
        }
        return not_acceptable('Email Tidak Ditemukan !', $errors = [], $status = 406);
    }
}
