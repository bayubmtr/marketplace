<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderDetail extends Migration
{
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('store_id');
            $table->unsignedTinyInteger('order_status_id');
            $table->integer('sub_total_price');
            $table->integer('sub_total_weight');
            $table->longText('meta')->nullable();
            $table->foreign('order_id')->references('id')
            ->on('orders')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('store_id')->references('id')
            ->on('stores')
            ->onDelete('cascade')->onUpdate('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
