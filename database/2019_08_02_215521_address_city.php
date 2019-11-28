<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddressCity extends Migration
{
    public function up()
    {
        Schema::create('address_cities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('province_id');
            $table->string('name', 100);
            $table->enum('type', ['Kota', 'Kabupaten']);
            $table->string('ibukota', 100)->nullable();
            $table->char('c_bsni', 5);
            $table->foreign('province_id')->references('id')
            ->on('address_provinces')
            ->onDelete('cascade')->onUpdate('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('address_cities');
    }
}
