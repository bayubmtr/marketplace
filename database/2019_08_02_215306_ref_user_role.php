<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefUserRole extends Migration
{
    public function up()
    {
        Schema::create('ref_user_roles', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 20);
            $table->string('description', 100)->nullable();
        });
    }
    public function down()
    {
        Schema::dropIfExists('ref_user_roles');
    }
}