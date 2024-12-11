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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->double('purchase_amount')->default(0);
            $table->enum('tax_type',['fixed','percentage'])->nullable()->default(null);
            $table->double('tax_amount')->default(0);
            $table->double('total_amount')->default(0);
            $table->string('file')->nullable()->default(null);
            $table->enum('is_paid',['yes','no'])->default('no');
            $table->enum('payment_method',['card','cash'])->nullable()->default(null);
            $table->timestamp('paid_at')->nullable()->default(null);
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
        Schema::dropIfExists('invoices');
    }
};
