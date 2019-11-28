<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Validator;
use App\User_address;
use App\Address_province;
use App\Address_city;
use App\Address_district;
use App\Address_village;
use App\Http\Requests\ProvinceRequest;
use App\Http\Requests\CityRequest;
use App\Http\Requests\DistrictRequest;
use App\Http\Requests\VillageRequest;

class AdministrativeController extends AdminController
{


	public function index(){
		if(request('type') == 'province'){
            $datas = Address_province::get();
		    return ok($datas);
        }
        if(request('type') == 'city'){
            if(!request('province_id')){
                return ok(['message' => 'id provinsi harus diisi !']);
            }
            $datas = Address_city::where('province_id', request('province_id'))->get();
		    return ok($datas);
        }
        if(request('type') == 'district'){
            if(!request('city_id')){
                return ok(['message' => 'id kota/kabupaten harus diisi !']);
            }
            $datas = Address_district::where('city_id', request('city_id'))->get();
		    return ok($datas);
        }
        if(request('type') == 'village'){
            if(!request('district_id')){
                return ok(['message' => 'id kecamatan harus diisi !']);
            }
            $datas = Address_village::where('district_id', request('district_id'))->get();
		    return ok($datas);
        }
    }
    public function store(Request $request){
		if(request('req_type') == 'province'){
            $datas = $this->provinceCreate(request()->all());
            return $datas;
        }
        if(request('req_type') == 'city'){
            if(!request('province_id')){
                return ok(['message' => 'id provinsi harus diisi !']);
            }
            $datas = $this->cityCreate(request()->all());
		    return $datas;
        }
        if(request('req_type') == 'district'){
            if(!request('city_id')){
                return ok(['message' => 'id kota/kabupaten harus diisi !']);
            }
            $datas = $this->districtCreate(request()->all());
		    return $datas;
        }
        if(request('req_type') == 'village'){
            if(!request('district_id')){
                return ok(['message' => 'id kecamatan harus diisi !']);
            }
            $datas = $this->villageCreate(request()->all());
		    return $datas;
        }
    }
    public function update(Request $request, $id){
		if(request('req_type') == 'province'){
            $datas = $this->provinceUpdate($id,request()->all());
            return $datas;
        }
        if(request('req_type') == 'city'){
            if(!request('province_id')){
                return ok(['message' => 'id provinsi harus diisi !']);
            }
            $datas = $this->cityUpdate($id,request()->all());
		    return $datas;
        }
        if(request('req_type') == 'district'){
            if(!request('city_id')){
                return ok(['message' => 'id kota/kabupaten harus diisi !']);
            }
            $datas = $this->districtUpdate($id,request()->all());
		    return $datas;
        }
        if(request('req_type') == 'village'){
            if(!request('district_id')){
                return ok(['message' => 'id kecamatan harus diisi !']);
            }
            $datas = $this->villageUpdate($id,request()->all());
		    return $datas;
        }
    }
    public function destroy($id){
		if(request('req_type') == 'province'){
            if(!$id){
                return ok(['message' => 'id provinsi harus diisi !']);
            }
            $datas = $this->provinceDestroy($id);
            return $datas;
        }
        if(request('req_type') == 'city'){
            if(!$id){
                return ok(['message' => 'id kota/kabupaten harus diisi !']);
            }
            $datas = $this->cityDestroy($id);
		    return $datas;
        }
        if(request('req_type') == 'district'){
            if(!$id){
                return ok(['message' => 'id kecamatan harus diisi !']);
            }
            $datas = $this->districtDestroy($id);
		    return $datas;
        }
        if(request('req_type') == 'village'){
            if(!$id){
                return ok(['message' => 'id kelurahan harus diisi !']);
            }
            $datas = $this->villageDestroy($id);
		    return $datas;
        }
    }
    public function provinceCreate(){

        $lastid = address_province::select('id')->orderBy('id', 'Desc')->first();
        $province = new address_province([
            'name' => request('name'),
            'id' => $lastid->id+1
        ]);
        $province->save();

        return response()->json(['message' => 'Province created !', 'item' => $province->name]);
    }

    public function provinceUpdate($id){

        $province = address_province::find($id);

        $province->name = request('name');
        $province->save();

        return response()->json(['message' => 'Province updated !', 'item' => $province->name]);
    }
    public function provinceDestroy($id){

        $province = Address_Province::find($id);
        $province->delete();

        return response()->json(['message' => 'Province removed!']);
    }

    public function cityCreate(){

        $lastid = address_city::select('id')->orderBy('id', 'Desc')->first();
        $city = new address_city([
            'name' => request('name'),
            'id' => $lastid->id+1,
            'province_id' => request('province_id'),
            'type' => request('type')
        ]);
        $city->save();

        return response()->json(['message' => 'City created !', 'item' => $city->name]);
    }

    public function cityUpdate($id){

        $validation = Validator::make(request()->all(),[
            'name' => 'required',
            'id' => 'required',
            'province_id' => 'required',
            'type' => 'in:Kota,Kabupaten'
        ]);

        if ($validation->fails())
            return response()->json(['message' => $validation->messages()->first()],422);

        $city = address_city::find($id);

        $city->name = request('name');
        $city->province_id = request('province_id');
        $city->type = request('type');
        $city->save();

        return response()->json(['message' => 'City updated!']);
    }
    public function cityDestroy($id){

        $city = address_city::find($id);
        $city->delete();

        return response()->json(['message' => 'City removed!']);
    }

    public function districtCreate(){

        $lastid = address_district::select('id')->orderBy('id', 'Desc')->first();
        $district = new address_district([
            'name' => request('name'),
            'id' => $lastid->id+1,
            'city_id' => request('city_id')
        ]);
        $district->save();

        return response()->json(['message' => 'District created !', 'item' => $district->name]);
    }

    public function districtUpdate($id){

        $district = address_district::find($id);

        $district->name = request('name');
        $district->city_id = request('city_id');
        $district->save();

        return response()->json(['message' => 'District updated!']);
    }
    public function districtDestroy($id){

        $district = address_district::find($id);
        $district->delete();

        return response()->json(['message' => 'District removed!']);
    }

    public function villageCreate(){

        $lastid = address_village::select('id')->orderBy('id', 'Desc')->first();
        $village = new address_village([
            'name' => request('name'),
            'id' => $lastid->id+1,
            'district_id' => request('district_id'),
            'zip_code' => request('zip_code')
        ]);
        $village->save();

        return response()->json(['message' => 'Village created !', 'item' => $village->name]);
    }

    public function villageUpdate($id){

        $village = address_village::find($id);

        $village->name = request('name');
        $village->district_id = request('district_id');
        $village->zip_code = request('zip_code');
        $village->save();

        return response()->json(['message' => 'Village updated!']);
    }
    public function villageDestroy($id){

        $village = address_village::find($id);
        $village->delete();

        return response()->json(['message' => 'Village removed!']);
    }

    public function dashboard(){
      $users_count = \App\User::count();
      $tasks_count = \App\Task::count();
      $recent_incomplete_tasks = \App\Task::whereStatus(0)->orderBy('due_date','desc')->limit(5)->get();
      return response()->json(compact('users_count','tasks_count','recent_incomplete_tasks'));
    }
}
