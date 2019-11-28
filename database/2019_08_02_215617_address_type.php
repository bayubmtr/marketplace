<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddressType extends Migration
{
    public function up()
    {
        Schema::create('address_types', function (Blueprint $table) {
            $table->unsignedInteger('address_id');
            $table->unsignedTinyInteger('address_type_id');
            $table->foreign('address_type_id')->references('id')
            ->on('ref_address_types')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('address_id')->references('id')
            ->on('addresses')
            ->onDelete('cascade')->onUpdate('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('address_types');
    }
}
