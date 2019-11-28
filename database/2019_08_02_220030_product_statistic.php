<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductStatistic extends Migration
{
    public function up()
    {
        Schema::create('product_statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->integer('sold_count')->default($value = 0);
            $table->integer('view_count')->default($value = 0);
            $table->integer('favorite_count')->default($value = 0);
            $table->integer('review_count')->default($value = 0);
            $table->decimal('review_avg', 2, 1);
            $table->integer('one_star')->default($value = 0);
            $table->integer('two_star')->default($value = 0);
            $table->integer('three_star')->default($value = 0);
            $table->integer('four_star')->default($value = 0);
            $table->integer('five_star')->default($value = 0);
            $table->foreign('product_id')->references('id')
            ->on('products')
            ->onDelete('cascade')->onUpdate('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('product_statistics');
    }
}
