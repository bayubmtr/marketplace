<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

use App\User_withdraw;
class WithdrawController extends WalletController
{
    public function index(){
        $user_id = auth()->user()->id;
        $data = User_withdraw::with('user_bank', 'withdraw_status')->where('user_id', $user_id)->get();
        return ok($data);
    }
    public function show($id){
        $user_id = auth()->user()->id;
        $data = User_withraw::where('user_id', $user_id)->where('id', $id)->first();
        return ok($data);
    }
    public function store(Request $request){
        $user_id = auth()->user()->id;
        $data = new User_withdraw;
        $data->user_id = $user_id;
        $data->user_bank_id = request('bank_id');
        $data->withdraw_status_id = 1;
        $data->amount = request('amount');
        $data->description = 'penarikan';
        $data->save();
        $update = $this->withdraw(request('amount'));
        return ok($data);
    }
}