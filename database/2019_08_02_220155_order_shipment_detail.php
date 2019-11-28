<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderShipmentDetail extends Migration
{
    public function up()
    {
        Schema::create('order_shipment_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_detail_id');
            $table->unsignedInteger('logistic_id');
            $table->string('logistic_service', 50);
            $table->integer('shipment_cost');
            $table->string('tracking_number', 30);
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivery_at')->nullable();
            $table->foreign('order_detail_id')->references('id')
            ->on('order_details')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('logistic_id')->references('id')
            ->on('logistics')
            ->onDelete('restrict')->onUpdate('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('order_shipment_details');
    }
}
