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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->nullable();
            $table->foreignId('order_id')->nullable();
            $table->foreignId('category_id');
            $table->foreignId('Supplier_id')->nullable();
            $table->integer('number')->unique();
            $table->date('shipping_date');
            $table->string('service_type')->nullable();
            $table->string('origin_country')->nullable();
            $table->string('destination_country');
            $table->string('shipping_method');
            $table->integer('cargo_weight')->nullable();
            $table->integer('containers_size')->nullable();
            $table->integer('containers_numbers')->nullable();
            $table->text('employee_notes')->nullable();
            $table->text('customer_notes')->nullable();
            $table->integer('is_information_complete')->default(0);
            $table->integer('is_confirm')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
