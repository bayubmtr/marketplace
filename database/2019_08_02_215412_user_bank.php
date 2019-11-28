<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class UserBank extends Migration
{
    public function up()
    {
        Schema::create('user_banks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bank_name', 100);
            $table->string('account_name', 100);
            $table->string('account_number', 150);
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('is_delete')->default($value = 0);
        });
    }
    public function down()
    {
        Schema::dropIfExists('user_banks');
    }
}
