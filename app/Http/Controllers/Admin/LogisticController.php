<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Logistic;
use File;

class LogisticController extends AdminController
{


	public function index(){
		$datas = Logistic::get();
		return ok($datas);
	}
    public function store(Request $request){
        $image = request('logo');
        $logo = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
        $resize = \Image::make($image);
        $resize->resize(200, 120);
        $resize->save(public_path('storage/icon/logistic/').$logo);
        $logistic = new Logistic;
        $logistic->code = request('code');
        $logistic->name = request('name');
        $logistic->about = request('about');
        $logistic->is_active = request('status');
        $logistic->logo = $logo;
        $logistic->save();
        return ok($logistic);
    }
    public function update(Request $request, $id){
        $image = request('logo');
        $logistic = Logistic::find($id);
        $string = $logistic->logo;
        $result = substr($string, strrpos($string, '/') + 1);
        if(request('type') == 'active'){
            $logistic->is_active = 1;
            $logistic->save();
            return ok(['message' => 'berhasil mengubah status']);
        }
        if(request('type') == 'inactive'){
            $logistic->is_active = 0;
            $logistic->save();
            return ok(['message' => 'berhasil mengubah status']);
        }
        $logistic->code = request('code');
        $logistic->name = request('name');
        $logistic->about = request('about');
        $logistic->is_active = request('status');
        if($image){
            File::delete('storage/icon/logistic/'.$result);
            $logo = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            $resize = \Image::make($image);
            $resize->resize(200, 120);
            $resize->save(public_path('storage/icon/logistic/').$logo);
            $logistic->logo = $logo;
        }
        $logistic->save();
        return ok($logistic);
    }
    public function destroy($id){
        $logistic = Logistic::find($id);
        $string = $logistic->logo;
        $result = substr($string, strrpos($string, '/') + 1);
        File::delete('storage/icon/logistic/'.$result);
        $logistic->delete();
        return ok(['message' => 'berhasil menghapus logistik '.$logistic->name]);
    }
    public function show($id){
        $logistic = Logistic::find($id);
        return ok($logistic);
    }
}
