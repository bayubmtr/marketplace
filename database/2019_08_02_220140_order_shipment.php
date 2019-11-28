<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderShipment extends Migration
{
    public function up()
    {
        Schema::create('order_shipments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('village_id');
            $table->string('recipient', 50);
            $table->string('phone_number', 17);
            $table->string('address_detail', 17);
            $table->timestamp('expired_at')->nullable();
            $table->foreign('order_id')->references('id')
            ->on('orders')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('village_id')->references('id')
            ->on('address_villages')
            ->onDelete('restrict')->onUpdate('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('order_shipments');
    }
}
