<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('elements', function (Blueprint $table) {
          $table->id();
          $table->unsignedBigInteger('group_id');
          $table->unsignedBigInteger('subcategory_id');
          $table->foreign('group_id')->references('id')->on('groups');
          $table->foreign('subcategory_id')->references('id')->on('subcategories');
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('elements');
    }
};
