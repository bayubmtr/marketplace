<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Promo;
use File;

class PromoController extends AdminController
{


	public function index(){
		$promos = Promo::with('promo_term.promo_variable');

		return ok($promos->paginate(request('pageLength')));
	}

    public function store(Request $request){
        $image = request('image');
        $logo = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
        $resize = \Image::make($image);
        $resize->resize(200, 120);
        $resize->save(public_path('storage/website/promo/').$logo);
        $promo = new Promo;
        $promo->code = request('code');
        $promo->title = request('title');
        $promo->content = request('content');
        $promo->is_active = request('status');
        $promo->image = $logo;
        $promo->save();
        return ok($promo);
    }
    public function update(Request $request, $id){
        $image = request('image');
        $promo = Promo::find($id);
        $string = $promo->image;
        $result = substr($string, strrpos($string, '/') + 1);
        if(request('type') == 'active'){
            $promo->is_active = 1;
            $promo->save();
            return ok(['message' => 'berhasil mengubah status']);
        }
        if(request('type') == 'inactive'){
            $promo->is_active = 0;
            $promo->save();
            return ok(['message' => 'berhasil mengubah status']);
        }
        $promo->code = request('code');
        $promo->title = request('title');
        $promo->content = request('content');
        $promo->is_active = request('status');
        if($image != null){
            File::delete('storage/website/promo/'.$result);
            $logo = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            $resize = \Image::make($image);
            $resize->resize(200, 120);
            $resize->save(public_path('storage/website/promo/').$logo);
            $promo->image = $logo;
        }
        $promo->save();
        return ok($promo);
    }
    public function destroy($id){
        $promo = Promo::find($id);
        $string = $promo->image;
        $result = substr($string, strrpos($string, '/') + 1);
        File::delete('storage/website/promo/'.$result);
        $promo->delete();
        return ok(['message' => 'berhasil menghapus logistik '.$promo->title]);
    }
    public function show($id){
        $promo = Promo::find($id);
        return ok($promo);
    }
}
