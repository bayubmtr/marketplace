<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Category;
use App\Category_sub;
use App\Category_sub_sub;

class CategoryController extends AdminController
{


	public function index(){
		if(request('type') == 'category'){
            $datas = Category::where('parent_id', null)->get();
		return ok($datas);
        }
        if(request('type') == 'sub_category'){
            $datas = Category::where('parent_id', request('parent_id'))->get();
		return ok($datas);
        }
        if(request('type') == 'sub_sub_category'){
            $datas = Category::where('parent_id', request('parent_id'))->get();
		return ok($datas);
        }
    }
    public function store(Request $request){
        $data = new Category;
        $data->name = request('name');
        $data->parent_id = request('parent_id');
        $data->save();
        return ok($data);
    }
    public function update(Request $request, $id){
        $data = Category::find($id);
        $data->name = request('name');
        $data->parent_id = request('parent_id');
        $data->save();
        return ok($data);
    }
}
