<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\User_profile;
use Carbon\Carbon;
use Validator;

class AccountController extends AuthController
{
    public function index(Request $request)
    {
        $account = auth()->user();
        $account->profile = auth()->user()->profile;
        return ok($account);
    }
    public function create()
    {
        return 'create';
    }
    public function store(Request $request)
    {
        return 'store';
    }
    public function show($id)
    {
        $sale = $this->getSaleDetailTrait($id);
        return $sale;
    }
    public function edit($id)
    {
        return 'edit';
    }
    public function update(Request $request, $id)
    {
        if(request('type') == 'profile'){
            $update = $this->updateProfile($id, request()->all());
            if(!$update){
                return not_acceptable('Error', ['msg' => 'error when trying to process request !']);
            }
            return ok($update);
        }elseif(request('type') == 'password'){
            $update = $this->changePassword(request()->all());
            return $update;
        }
    }
    public function destroy($id)
    { 
    }
    private function updateProfile($id){
        $profile = User_profile::where('user_id', $id)->first();
        $profile->first_name = request('first_name');
        $profile->last_name = request('last_name');
        $profile->date_of_birth = request('date_of_birth');
        $profile->gender = request('gender');
        $profile->phone_number = request('phone_number');
        $profile->save();
        if($profile){
            return $profile;
        }
        return;
    }
}
