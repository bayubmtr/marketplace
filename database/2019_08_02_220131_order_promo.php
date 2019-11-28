<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderPromo extends Migration
{
    public function up()
    {
        Schema::create('order_promos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->string('promo_code', 20);
            $table->integer('obtain');
            $table->foreign('order_id')->references('id')
            ->on('orders')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('promo_code')->references('code')
            ->on('promos')
            ->onDelete('cascade')->onUpdate('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('order_promos');
    }
}
