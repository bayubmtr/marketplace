<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PromoTerm extends Migration
{
    public function up()
    {
        Schema::create('promo_terms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('promo_id');
            $table->unsignedInteger('promo_variable_id');
            $table->string('condition');
            $table->string('value', 100);
            $table->foreign('promo_id')->references('id')
            ->on('promos')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('promo_variable_id')->references('id')
            ->on('promo_variables')
            ->onDelete('cascade')->onUpdate('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('promo_terms');
    }
}
