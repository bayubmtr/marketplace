<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Address;
use App\Address_type;
use App\Address_village;
use App\Address_district;
use DB;
use App\Http\Traits\user\AddressTrait;

class AddressController extends UserController {
  use AddressTrait;
  public function __construct(){
    
  }
    public function index(Request $request){
      if(request('type') == 'city'){
        $addresses = $this->searchAddressCity(request('search'));
        return ok($addresses);
      }else if(request()->has('search')){
        $addresses = $this->searchAddress(request('search'));
        return ok($addresses);
      }else{
        $addresses = $this->getAllAddressTrait();
      }
        if (!$addresses){
          return error($message = 'not found !', $errors = ['user' => 'the user does not have an address !'], $status = 404);
        }
        else {
        return ok($addresses);
        }
    }
    public function show($id){
      $address = $this->getAddressTrait($id);
      if (!$address){
        return error($message = 'not found !', $errors = ['id' => 'address id not found !'], $status = 404);
      }
      else {
      return ok($address);
      }
    }
    public function update(Request $request, $id){
      $message = null;
      if(request('type') == 'setPrimary'){
        $remove = $this->removeAddressType('1');
        $update = $this->setAddressType($id, '1');
        $message = 'Sukses Menjadikan Alamat Utama';
      }
      elseif(request('type') == 'setStoreAddress'){
        $remove = $this->removeAddressType('2');
        $update = $this->setAddressType($id, '2');
        $message = 'Sukses Menjadikan Alamat Toko';
      }
      elseif(request('type') == 'setReturnAddress'){
        $remove = $this->removeAddressType('3');
        $update = $this->setAddressType($id, '3');
        $message = 'Sukses Menjadikan Alamat Pengembalian';
      }
      else{
        $update = Address::find($id);
        $input = request()->all();
        $update->fill($input);
        $update->save();
        $message = 'Sukses Mengedit Alamat';
        if(request('isPrimaryAddress') == true){
          $this->removeAddressType('1');
          $this->setAddressType($address->id, '1');
        }elseif(request('isStoreAddress') == true){
          $this->removeAddressType('2');
          $this->setAddressType($address->id, '2');
        }elseif(request('isReturnAddress') == true){
          $this->removeAddressType('3');
          $this->setAddressType($address->id, '3');
        }
      }
      if($update){
        return ok(['message' => $message]);
      }else{
        return error($message = 'Ada Masalah, Coba Lagi Nanti', $errors = [], $status = 500);
      }
    }
    public function store(Request $request){
      $address = new Address;
      $address->user_id = auth()->user()->id;
      $address->village_id = request('village_id');
      $address->address_name = request('address_name');
      $address->recipient = request('recipient');
      $address->phone_number = request('phone_number');
      $address->address_detail = request('address_detail');
      $address->save();
      if(request('isPrimaryAddress') == true){
        $this->removeAddressType('1');
        $this->setAddressType($address->id, '1');
      }elseif(request('isStoreAddress') == true){
        $this->removeAddressType('2');
        $this->setAddressType($address->id, '2');
      }elseif(request('isReturnAddress') == true){
        $this->removeAddressType('3');
        $this->setAddressType($address->id, '3');
      }
      if($address){
        return ok(['message' => 'Berhasil Menambah Alamat', 'Address' => $address]);
      }else{
        return error($message = 'Ada Masalah, Coba Lagi Nanti', $errors = [], $status = 500);
      }
    }
    public function destroy($id){
      $data = Address::find($id)->delete();
      if($data){
          return ok(['message' => 'Alamat Berhasil Dihapus !']);
      }else{
          return error($message = 'Ada masalah !', $errors = [], $status = 500);
      }
  }
    private function searchAddress($search){
        $datas = DB::table('address_villages')
        ->join('address_districts', 'address_villages.district_id', '=', 'address_districts.id')
        ->join('address_cities', 'address_districts.city_id', '=', 'address_cities.id')
        ->join('address_provinces', 'address_cities.province_id', '=', 'address_provinces.id')
        ->where('address_villages.name','like','%'.$search.'%')
        ->orwhere('address_districts.name','like','%'.$search.'%')->groupBy('address_villages.id')
        ->select('address_villages.id as village_id', 'address_villages.zip_code',DB::raw("CONCAT(address_villages.name,' ',address_districts.name,' ', address_cities.name,' ', address_provinces.name) AS text"))->limit(5)->get()->toArray();
		return $datas;
    }
    private function searchAddressCity($search){
      $datas = DB::table('address_cities')
      ->join('address_provinces', 'address_cities.province_id', '=', 'address_provinces.id')
      ->where('address_cities.name','like','%'.$search.'%')
      ->orwhere('address_provinces.name','like','%'.$search.'%')->groupBy('address_cities.id')
      ->select('address_provinces.id as city_id',DB::raw("CONCAT(address_cities.name,' - ', address_provinces.name) AS text"))->limit(5)->get()->toArray();
  return $datas;
  }

    //useable private function
  private function setAddressType($id,$type){
    $address = Address_type::firstOrCreate(['address_id' => $id, 'address_type_id' => $type]);
    if($address){
      return  $address;
    }
  }
  private function removeAddressType($type){
    $address = Address_type::with('address:id,user_id')
    ->whereHas('address',function($q){
      $q->where('user_id', auth()->user()->id);
      })->where('address_type_id', $type)->delete();
    if($address){
      return $address;
    }                   
  }
}