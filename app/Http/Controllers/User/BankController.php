<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

use App\User_bank;

class BankController extends UserController
{
    public function index(){
        $data = auth()->user()->bank->where('is_delete', 0);
        return ok($data);
    }
    public function show($id){
        $data = auth()->user()->wallet;
        return ok($data);
    }
    public function store(Request $request){
        $user_id = auth()->user()->id;
        $data = new User_bank;
        $data->user_id = $user_id;
        $data->bank_name = request('bank_name');
        $data->account_number = request('account_number');
        $data->account_name = request('account_name');
        $data->save();
        return ok($data);
    }
    public function destroy($id){
        $user_id = auth()->user()->id;
        $del = User_bank::find($id);
        $del->is_delete = 1;
        $del->save();
        return ok(['message' => 'sukkses menghapus rekening']);
    }
}