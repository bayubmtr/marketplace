<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Logistic extends Migration
{
    public function up()
    {
        Schema::create('logistics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 20);
            $table->string('name', 50);
            $table->string('about', 100);
            $table->string('logo', 100);
            $table->boolean('is_active')->default($value = 1);
        });
    }
    public function down()
    {
        Schema::dropIfExists('logistics');
    }
}
