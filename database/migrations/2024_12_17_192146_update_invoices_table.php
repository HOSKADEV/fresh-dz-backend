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
        Schema::table('invoices', function (Blueprint $table) {
          $table->double('discount_amount')->after('tax_amount')->default(0);
          $table->string('discount_code')->after('total_amount')->nullable()->default(null);
          $table->string('payment_account')->after('payment_method')->nullable()->default(null);
          $table->string('payment_receipt')->after('payment_account')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            //
        });
    }
};
