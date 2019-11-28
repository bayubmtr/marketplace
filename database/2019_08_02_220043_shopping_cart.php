<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShoppingCart extends Migration
{
    public function up()
    {
        Schema::create('shopping_carts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('user_id');
            $table->integer('quantity')->default($value = 1);
            $table->unsignedInteger('address_id')->nullable();
            $table->unsignedInteger('logistic_id')->nullable();
            $table->string('logistic_service', 50)->nullable();
            $table->integer('shipment_cost')->nullable();
            $table->string('note', 200)->nullable();
            $table->boolean('is_checkout')->default($value = 0);
            $table->timestamps();
            $table->foreign('user_id')->references('id')
            ->on('users')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_id')->references('id')
            ->on('products')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('address_id')->references('id')
            ->on('addresses')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('logistic_id')->references('id')
            ->on('logistics')
            ->onDelete('cascade')->onUpdate('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('shopping_carts');
    }
}
