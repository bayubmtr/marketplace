<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefStoreStatus extends Migration
{
    public function up()
    {
        Schema::create('ref_store_statuses', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 20);
            $table->string('description', 100)->nullable();
        });
    }
    public function down()
    {
        Schema::dropIfExists('ref_store_statuses');
    }
}
