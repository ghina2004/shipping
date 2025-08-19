<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_payments', function (Blueprint $table) {
            $table->string('gateway')->nullable()->after('status');
            $table->string('gateway_invoice_id')->nullable()->after('gateway');
            $table->string('gateway_payment_id')->nullable()->after('gateway_invoice_id');
        });
    }

    public function down(): void
    {
        Schema::table('order_payments', function (Blueprint $table) {
            $table->dropColumn(['gateway', 'gateway_invoice_id', 'gateway_payment_id']);
        });
    }

};
