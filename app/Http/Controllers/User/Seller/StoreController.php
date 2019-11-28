<?php

namespace App\Http\Controllers\User\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Store;
use App\Store_slideshow;
use App\Store_statistic;
use App\Store_follower;
use App\Store_logistic;
use App\Logistic;
use App\Product;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use File;

class StoreController extends Controller
{
    public function index(Request $request){
        $id = auth()->user()->id;
        $store_id = auth()->user()->store->id;
        if(request('type') == 'logistic'){
            $store_logistic = Store_logistic::with('logistic')->where('store_id', $store_id)->get();
            $logistic = Logistic::where('is_active', 1)->get();
            foreach ($store_logistic as $value) {
                foreach ($logistic as $key => $log) {
                    if($value->logistic_id == $log->id){
                        $logistic[$key]->is_used = true;
                    }
                }
            }
            return ok($logistic);
        }else if(request('type') == 'product'){
            $product = Product::where('store_id', $store_id)->paginate(10);
            return ok($product);
        }
        $profiles = Store::with('slideshow', 'store_statistic')->withCount(['follower', 'product' => function ($query) use($id) {
            $query->where('user_id', $id);
        }])->where('user_id', $id)->get()->first();
        if (!$profiles){
        return response()->json(['status' => 404, 'msg' => 'Store not found !']);
        }
        else {
            return ok($profiles);
        }

    }
    public function store(Request $request)
    {
        if (auth()->user()->store != null){
            return not_acceptable('Kamu Sudah Punya Toko !', []);
        }
        $new = new Store;
        $new->user_id = auth()->user()->id;
        $new->name = request('store_name');
        $new->slogan = request('store_slogan');
        $new->store_url = request('store_url');
        $new->description = request('store_description');
        $new->save();
        $statistic = new Store_statistic;
        $statistic->store_id = $new->id;
        $statistic->save();
        return ok($new);
    }
    public function destroy($id)
    { 
        return ok('Toko tidak dapat dihapus !');
    }
    public function update(Request $request, $id)
    {
        if(request('type') == 'add_slideshow'){
            $image = request('image');
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($image)->save(public_path('storage/store/slideshow/').$name);
            $slideshow = new Store_slideshow;
            $slideshow->store_id = auth()->user()->store->id;
            $slideshow->slideshow = $name;
            $slideshow->save();
            return ok($slideshow);
        }
        if(request('type') == 'delete_slideshow'){
            $file = request('file_name');
            File::delete('storage/store/slideshow/'.$file);
            $delete = Store_slideshow::find(request('slideshow_id'));
            $delete->delete();
            return ok(['message' => 'Success delete slideshow']);
        }
        if(request('type') == 'update_profile'){
            $update = Store::find(auth()->user()->store->id);
            $update->name = request('store_name');
            $update->slogan = request('slogan');
            $update->description = request('store_description');
            $update->save();
            return ok($update);
        }
        if(request('type') == 'update_logo'){
            if(request()->has('logo')){
                $image = request('logo');
                $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                \Image::make($image)->save(public_path('storage/store/logo/').$name);
                $store = Store::find($id);
                $store->logo = $name;
                $store->save();
                return ok($store);
            }
        }
        if(request('type') == 'update_background'){
            if(request()->has('background')){
                $image = request('background');
                $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                \Image::make($image)->save(public_path('storage/store/background/').$name);
                $store = Store::find($id);
                $store->background = $name;
                $store->save();
                return ok($store);
            }
        }
        if(request('type') == 'update_logistic'){
            $store_id = auth()->user()->store->id;
            $update = Store_logistic::where('logistic_id', $id)->where('store_id', $store_id)->first();
            if($update){
                $update->delete();
            }else{
                $add = new Store_logistic;
                $add->logistic_id = $id;
                $add->store_id = $store_id;
                $add->save();
            }
            return ok(['message' => 'Berhasil']);
        }
    }
    public function getLogistic(){
        $id = auth()->user()->store->id;
        $services = Logistic::get(['id','name']);
        $logistic = Store_logistic::where('store_id', $id)->get();


        foreach($services as $data){
            $find = Store_logistic::where('store_id', $id)->where('service_id', $data->id)->first();
            if($find){
                $data->status = 'active';
            }else{
                $data->status = 'inactive';
            }
        }

            return response()->json(['logistic' => $services]);

    }
    public function updateLogistic($id){
        $uid = auth()->user()->store->id;
		$courier = Store_logistic::where('service_id',$id)->where('store_id',$uid)->first();
        if($courier){
            $del = Store_courier::where('service_id',$id)->where('store_id',$uid);
            $del->delete();
            return response()->json(['message' => 'Logistic has been delete !']);
        }else {
            $create = new Store_logistic([
                'store_id' => $uid,
                'service_id' => $id
            ]);
            $create->save();
            return response()->json(['message' => 'Logistic has been add !']);
        }
    }
    public function checkStoreUrl(Request $request)
    {
        $store_url = request('store_url');
        $pattern = ' ';
        $result = false;
        if(strpos($store_url, ' ') !== false || preg_match('/[^a-zA-Z\d]/', $store_url)){
            $result = false;
        }else{
            $data = Store::where('store_url', request('store_url'))->first();
            $result = $data ? false : true;
        }
        return ok(['is_valid' => $result]);
    }
}