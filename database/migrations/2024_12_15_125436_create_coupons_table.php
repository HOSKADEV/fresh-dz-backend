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
        Schema::create('coupons', function (Blueprint $table) {
          $table->id();
          $table->string('code')->unique();
          $table->double('discount');
          $table->string('name')->nullable()->default(null);
          $table->integer('max_uses')->nullable()->default(null);
          $table->timestamp('start_date')->nullable()->default(null);
          $table->timestamp('end_date')->nullable()->default(null);
          $table->timestamps();
          $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
};
