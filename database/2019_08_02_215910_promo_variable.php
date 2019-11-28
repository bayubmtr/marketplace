<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PromoVariable extends Migration
{
    public function up()
    {
        Schema::create('promo_variables', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
        });
    }
    public function down()
    {
        Schema::dropIfExists('promo_variables');
    }
}
