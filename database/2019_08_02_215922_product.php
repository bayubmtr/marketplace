<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Product extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('store_id');
            $table->unsignedInteger('category_id');
            $table->unsignedTinyInteger('product_status_id');
            $table->string('name', 100);
            $table->integer('price');
            $table->integer('discount')->default($value = 0);
            $table->text('description');
            $table->integer('weight');
            $table->enum('condition', ['New', 'Used']);
            $table->longText('specification')->nullable();
            $table->integer('stock')->default($value = 0);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('store_id')->references('id')
            ->on('stores')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('category_id')->references('id')
            ->on('categories')
            ->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('product_status_id')->references('id')
            ->on('ref_product_statuses')
            ->onDelete('cascade')->onUpdate('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
