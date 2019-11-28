<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StoreStatistic extends Migration
{
    public function up()
    {
        Schema::create('store_statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('store_id');;
            $table->integer('product_count')->default($value = 0);
            $table->integer('sold_count')->default($value = 0);
            $table->integer('review_count')->default($value = 0);
            $table->integer('review_avg')->default($value = 0);
            $table->foreign('store_id')->references('id')->on('stores')
            ->onDelete('cascade')->onUpdate('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('store_statistics');
    }
}
