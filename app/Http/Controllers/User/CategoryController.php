<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if(request('type') == 'all'){ 
            $categories = Category::with('childs.childs')->where('parent_id', null)->get();
            return ok($categories);
        }
        $categories = Category::where('parent_id', null)->get();
        return ok($categories);
    }
    public function show($id)
    {
        $subCategories = category::where('parent_id', $id)->get();
        return ok($subCategories);
    }
}
