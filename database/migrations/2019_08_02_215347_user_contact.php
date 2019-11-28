<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class UserContact extends Migration
{
    public function up(){
        Schema::create('user_contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nomor', 20);
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('is_delete')->default($value = 0);
        });
    }
    public function down(){
        Schema::dropIfExists('users');
    }
}
