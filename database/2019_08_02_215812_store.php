<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Store extends Migration
{
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('store_url', 20)->nullable();
            $table->string('name', 40);
            $table->string('slogan', 50)->nullable();
            $table->text('description');
            $table->string('logo', 100)->nullable();
            $table->string('background', 100)->nullable();
            $table->boolean('is_active')->default($value = 1);
            $table->timestamps();
            $table->foreign('user_id')->references('id')
            ->on('users')
            ->onDelete('cascade')->onUpdate('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('stores');
    }
}
