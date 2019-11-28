<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StoreLogistic extends Migration
{
    public function up()
    {
        Schema::create('store_logistics', function (Blueprint $table) {
            $table->unsignedInteger('logistic_id');
            $table->unsignedInteger('store_id');
            $table->foreign('store_id')->references('id')
            ->on('stores')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('logistic_id')->references('id')
            ->on('logistics')
            ->onDelete('cascade')->onUpdate('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('store_logistics');
    }
}
