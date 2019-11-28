<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddressProvince extends Migration
{
    public function up()
    {
        Schema::create('address_provinces', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->char('p_bsni', 5);
        });
    }
    public function down()
    {
        Schema::dropIfExists('address_provinces');
    }
}
