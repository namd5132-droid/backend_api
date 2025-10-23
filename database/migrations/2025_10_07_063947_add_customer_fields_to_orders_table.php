<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        // $table->string('customer_name')->after('id');
        // $table->string('customer_email')->after('customer_name');
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        // $table->dropColumn(['customer_name', 'customer_email']);
    });
}

};
