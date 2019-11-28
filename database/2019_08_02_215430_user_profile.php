<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserProfile extends Migration
{
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('first_name', 50);
            $table->string('last_name', 100)->nullable();
            $table->string('phone_number', 17)->nullable();
            $table->enum('gender', ['M', 'L'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('avatar', 100)->nullable();
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
}
