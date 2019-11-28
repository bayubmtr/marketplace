<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\tbl_kodepos;
use App\tbl_provinsi;
use App\address_province;
use App\address_city;
use App\address_kota;
use App\address_district;
use App\address_village;
use RajaOngkir;

class kodepos extends Controller
{
    //
    public function kodepos(){
        

        $kec = address_district::get();
        $vil = address_village::get();
        $reg = address_city::get();

        $kot = address_city::where('type', 'Kota')->get();
        $kab = address_city::where('type', 'Kabupaten')->get();

        $tkab = count($kab);
        $tkot = count($kot);

        
        

        $xxx = RajaOngkir::Kota()->search('type', $name = "kota")->get();
        dd($xxx);
   
        $usersUnique = $city->unique('kecamatan');
        $usersDupes = $reg->diff($usersUnique);
        dd($usersDupes);

    	foreach ($xxx as $kot) {
            
            $kel = address_city::where('id', $kot['city_id'])->first();

            $kel->type = $kot['type'];
    		
            $kel->save();
           
    	}
        $data = address_district::get();
        dd($xxx);


    }
    public function gantiCode(){
        $data = RajaOngkir::Kota()->all();
        return $data;
        foreach($data as $key => $value){
          $update = address_city::where('name','like','%'.$value['city_name'].'%')->get()->first();
          if($update){
              $update->id = $value['city_id'];
              $update->update = 1;
              $update->save();
          }
        }
           // ->update(['name' => '1']);
          //  }
          //foreach($data as $key => $value) {
           // $update = address_kotaa::where('name','like','%'.$value['city_name'].'%')->get()->first();
            //if($update){
            //    $update->provinsi_id = $value['province_id'];
             //   $update->save();
            //}
        //} 
    dd($update);
        
    }
}