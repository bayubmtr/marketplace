<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderPayment extends Migration
{
    public function up()
    {
        Schema::create('order_payments', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->unsignedInteger('order_id');
            $table->unsignedTinyInteger('payment_type_id');
            $table->unsignedTinyInteger('payment_status_id');
            $table->integer('total_payment');
            $table->longText('meta')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
            $table->foreign('order_id')->references('id')
            ->on('orders')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('payment_status_id')->references('id')
            ->on('ref_payment_statuses')
            ->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('payment_type_id')->references('id')
            ->on('ref_payment_types')
            ->onDelete('restrict')->onUpdate('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('order_payments');
    }
}
