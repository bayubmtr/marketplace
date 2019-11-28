<?php
namespace App\Http\Traits\user;
use App\Address;
use DB;
trait AddressTrait {
  public function getAllAddressTrait(){
    $address = Address::with('address_type.ref_address_type:id,name')->where('user_id', auth()->user()->id)
    ->join('address_villages', 'addresses.village_id', '=', 'address_villages.id')
    ->join('address_districts', 'address_villages.district_id', '=', 'address_districts.id')
    ->join('address_cities', 'address_districts.city_id', '=', 'address_cities.id')
    ->join('address_provinces', 'address_cities.province_id', '=', 'address_provinces.id')
    ->select(
              'addresses.*',
              'address_villages.name as village',
              'address_villages.zip_code as zip_code',
              'address_districts.name as district',
              'address_provinces.name as province',
              DB::raw("CONCAT(address_cities.type,' ', address_cities.name) AS city")
    )->get();
    if($address){
      return $address;
    }
  }
  public function getPrimaryAddressTrait(){
      $address = Address::with('address_village.address_district.address_city.address_province', 'address_type')
      ->whereHas('address_type',function($q){
        $q->where('address_type_id', 1);
        })->where('user_id', auth()->user()->id)->get()->first();
      if($address){
        return $address;
      }
  }
  public function getAddressTrait($id){
    $address = Address::with('address_type.ref_address_type')->where('user_id', auth()->user()->id)
    ->join('address_villages', 'addresses.village_id', '=', 'address_villages.id')
    ->join('address_districts', 'address_villages.district_id', '=', 'address_districts.id')
    ->join('address_cities', 'address_districts.city_id', '=', 'address_cities.id')
    ->join('address_provinces', 'address_cities.province_id', '=', 'address_provinces.id')
    ->select(
              'addresses.*',
              'address_villages.name as village',
              'address_villages.zip_code as zip_code',
              'address_districts.name as district',
              DB::raw("CONCAT(address_cities.type,' ', address_cities.name) AS city"),
              'address_provinces.name as province'
    )->where('addresses.id', $id)->first();
    if($address){
      return $address;
    }
  }
}