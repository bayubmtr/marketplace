<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class History extends Migration
{
    public function up()
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('table', 100);
            $table->string('coloumn', 100);
            $table->string('row_id');
            $table->longText('value');
            $table->timestamp('created_at')->useCurrent();
            $table->foreign('user_id')->references('id')
            ->on('users')
            ->onDelete('restrict')->onUpdate('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('histories');
    }
}
