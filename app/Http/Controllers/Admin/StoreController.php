<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Validator;
use JWTAuth;
use App\Store;
use Carbon\Carbon;

class StoreController extends AdminController
{
	public function index(){
	$users = Store::with('user_profile:user_id,first_name,last_name', 'store_statistic')->withCount('order_detail');

    if(request()->has('name'))
      $users->where('name','like','%'.request('name').'%');

    if(request()->has('owner'))
      $users->whereHas('user',function($q){
      $q->where('first_name','like','%'.request('owner').'%');
      });
	if(request()->has('store_url'))
        $users->where('store_url',request('store_url'));
    if(request()->has('status'))
        $users->where('is_active',request('status'));
    if(request('sortBy') == 'status')
        $users->orderBy('is_active',request('order'));
    else if(request()->has('order') && request()->has('sortBy'))
		$users->orderBy(request('sortBy'),request('order'));
	return $users->paginate(request('pageLength'));
	}
    public function update(Request $request, $id){
        if(request('type') == 'inactive'){
            $banned = Store::find($id);
            $banned->is_active = 0;
            $banned->deleted_at = Carbon::now();
            $banned->save();
            return ok(['message' => 'Toko berhasil di nonaktifkan !']);
        }elseif(request('type') == 'active'){
            $banned = Store::find($id);
            $banned->is_active = 1;
            $banned->deleted_at = null;
            $banned->save();
            return ok(['message' => 'Toko Berhasil di aktifkan !']);
        }
    }
}
