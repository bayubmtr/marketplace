<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddressDistrict extends Migration
{
    public function up()
    {
        Schema::create('address_districts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('city_id');
            $table->string('name', 100);
            $table->foreign('city_id')->references('id')
            ->on('address_cities')
            ->onDelete('cascade')->onUpdate('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('address_districts');
    }
}
