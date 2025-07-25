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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('employee_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('shipping_manager_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('accountant_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('original_company_id')->nullable()->constrained('original_shipping_companies')->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->boolean('status')->default('0'); //confirm or not
            $table->boolean('placement')->default('0');
            $table->boolean('has_accountant')->default('0');
<<<<<<<< HEAD:database/migrations/2025_06_18_155835_create_orders_table.php
========
            $table->foreignId('original_company_id')->nullable()->constrained('original_shipping_companies')->onDelete('cascade');
>>>>>>>> e8329084d9746156efc35a20626b96a68516a202:database/migrations/2025_07_23_001844_create_orders_table.php
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
