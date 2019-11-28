<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductDiscussion extends Migration
{
    public function up()
    {
        Schema::create('product_discussions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('product_id');
            $table->integer('parent_id')->nullable();
            $table->string('discussion', 200);
            $table->timestamps();
            $table->foreign('user_id')->references('id')
            ->on('users')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_id')->references('id')
            ->on('products')
            ->onDelete('cascade')->onUpdate('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('product_discussions');
    }
}
