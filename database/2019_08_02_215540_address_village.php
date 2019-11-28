<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddressVillage extends Migration
{
    public function up()
    {
        Schema::create('address_villages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('district_id');
            $table->string('name', 100);
            $table->foreign('district_id')->references('id')
            ->on('address_districts')
            ->onDelete('cascade')->onUpdate('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('address_villages');
    }
}
