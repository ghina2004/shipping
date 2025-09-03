<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('employee_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('shipping_manager_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('accountant_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->boolean('status')->default(0); //confirm or not
            $table->boolean('placement')->default(0);
            $table->boolean('has_accountant')->default(0);
            $table->string('payment_status')->default('unpaid');
            $table->string('order_status')->default('In process');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
