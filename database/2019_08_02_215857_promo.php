<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Promo extends Migration
{
    public function up()
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 20)->unique();
            $table->string('title', 50);
            $table->string('image', 100);
            $table->text('content');
            $table->boolean('is_active')->default($value = 0);
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('promos');
    }
}
