<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Address extends Migration
{
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('village_id');
            $table->string('address_name', 50);
            $table->string('recipient', 50);
            $table->string('phone_number', 17);
            $table->string('address_detail', 200);
            $table->string('zip_code', 10);
            $table->foreign('village_id')->references('id')
            ->on('address_villages')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')
            ->on('users')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
