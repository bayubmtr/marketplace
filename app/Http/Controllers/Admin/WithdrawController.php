<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\User_withdraw;

class WithdrawController extends AdminController
{
	public function index(){
        $products = User_withdraw::with('user_profile', 'user_bank', 'withdraw_status');
        if(request()->has('name'))
            $products->whereHas('user_profile',function($q){
                $q->where('first_name','like','%'.request('name').'%')->orwhere('last_name','like','%'.request('name').'%');
            });
        if(request()->has('account_name'))
            $products->whereHas('user_bank',function($q){
                $q->where('account_name','like','%'.request('account_name').'%');
            });
        if(request()->has('status'))
            $products->where('withdraw_status_id',request('status'));
            $products->orderBy(request('sortBy'),request('order'));
		return $products->paginate(request('pageLength'));
	}
    public function update(Request $request, $id){
        $update = User_withdraw::find($id);
        $update->withdraw_status_id = request('status');
        $update->save();
        return ok(['message' => 'Berhasil Mengubah Status Withdraw']);
    }
}
