<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Promo;

class WebsiteController extends UserController
{
    public function getPromos(){
        $data = Promo::where('is_active', 1)->get();
        return ok($data);
    }
    public function getPromo($id){
        $data = Promo::where('code', $id)->where('is_active', 1)->first();
        return ok($data);
    }
}