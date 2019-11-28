<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class User extends Migration
{
    public function up(){
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email', 200)->unique();
            $table->string('password', 50)->nullable();
            $table->string('provider', 100)->nullable();
            $table->string('provider_unique_id', 100)
            ->nullable()->unique();
            $table->string('activation_token',64)->nullable();
            $table->unsignedTinyInteger('user_status_id');
            $table->foreign('user_status_id')->references('id')
            ->on('ref_user_statuses')
            ->onDelete('restrict')->onUpdate('cascade');
            $table->unsignedTinyInteger('user_role_id');
            $table->foreign('user_role_id')->references('id')
            ->on('ref_user_roles')
            ->onDelete('restrict')->onUpdate('cascade');
            $table->string('remember_token',100)->nullable();
            $table->timestamp('last_activity')->useCurrent();
            $table->timestamps();
        });
    }
    public function down(){
        Schema::dropIfExists('users');
    }
}
