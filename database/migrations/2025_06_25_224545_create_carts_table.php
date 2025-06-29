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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('employee_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('shipping_manager_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('accountant_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->integer('cart_number')->unique();
            $table->integer('is_submit')->default(0);
            $table->integer('shipment_status')->default('0');
            $table->integer('accountant_status')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
