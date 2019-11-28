<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Message extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('store_id');
            $table->integer('conversation_id');
            $table->text('message');
            $table->boolean('is_read')->default($value = 0);
            $table->timestamps();
            $table->foreign('buyer_id')->references('id')
            ->on('users')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('store_id')->references('id')
            ->on('stores')
            ->onDelete('cascade')->onUpdate('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
