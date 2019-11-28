<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserWithdraw extends Migration
{
    public function up()
    {
        Schema::create('user_withdraws', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('user_bank_id');
            $table->unsignedTinyInteger('withdraw_status_id');
            $table->integer('amount');
            $table->string('description', 100);
            $table->foreign('user_id')->references('id')
            ->on('users')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_bank_id')->references('id')
            ->on('user_banks')
            ->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('withdraw_status_id')->references('id')
            ->on('ref_withdraw_statuses')
            ->onDelete('restrict')->onUpdate('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('user_withdraws');
    }
}
