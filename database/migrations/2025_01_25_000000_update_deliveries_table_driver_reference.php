<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->foreign('driver_id')->references('id')->on('admins');
        });
    }

    public function down()
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->foreign('driver_id')->references('id')->on('drivers');
        });
    }
};
