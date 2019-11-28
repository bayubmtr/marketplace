<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Category extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->unsignedTinyInteger('parent_id')->nullable();
        });
    }
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
