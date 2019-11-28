<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderItem extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_detail_id');
            $table->unsignedInteger('product_id');
            $table->integer('price');
            $table->integer('weight');
            $table->integer('discount')->default($value = 0);
            $table->integer('quantity');
            $table->foreign('order_detail_id')->references('id')
            ->on('order_details')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_id')->references('id')
            ->on('products')
            ->onDelete('restrict')->onUpdate('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
