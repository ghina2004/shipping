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
        Schema::create('shipment_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id');
            $table->integer('invoice_number');
            $table->string('invoice_type');
            $table->float('initial_amount');
            $table->float('customs_fee');
            $table->float('service_fee');
            $table->float('company_profit');
            $table->float('final_amount');
            $table->string('notes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_invoices');
    }
};
