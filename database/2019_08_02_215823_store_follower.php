<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StoreFollower extends Migration
{
    public function up()
    {
        Schema::create('store_followers', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('store_id');
            $table->foreign('store_id')->references('id')
            ->on('stores')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')
            ->on('users')
            ->onDelete('cascade')->onUpdate('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('store_followers');
    }
}
